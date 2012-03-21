require(gplots) # for textplot function
pdf(file = "homework_4_solutions.pdf", width = 8.5, height = 11, family = "Helvetica",
    title = "PSY304B Homework #4", bg = "white")

sep = "\n**----**\n" # Output separator
options(show.signif.stars = FALSE)

# Using simple repetition for exposure requires the data to be of the form:
# Middle/Short, Old/Medium, Middle/Long, Old/Short, Middle/Medium, Old/Long
# Which is less than intuitive. Using each to repeat the exposure, it becomes:
# Middle/Short, Old/Short, Middle/Medium, Old/Medium, Middle/Long, Old/Long

stimulus.frame = data.frame(response = c(29, 25, 33, 29, 44, 36),
                            age = c("Middle", "Old"),
                            exposure = rep(c("Short", "Medium", "Long"), each = 2))

# The estimates of variances are given rather than derived
stimulus.variance = c(105, 110, 102, 94, 108, 111)

# The size of the groups
stimulus.n = 8

stimulus.aov = aov(response ~ 1 + age + exposure + age:exposure, data = stimulus.frame)
stimulus.summary = summary(stimulus.aov)
# str(stimulus.summary)

layout(matrix(c(1),1))

output = c("Means\n")

# since there's only one of each, this just prints the data
output =c(output,
          capture.output(with(stimulus.frame,
                              tapply(response, INDEX = list(age, exposure), mean))))

#output = c(output, sep, "Frequency Table\n")
#with(stimulus.frame, table(age, exposure))

output = c(output, "", capture.output(with(stimulus.frame, tapply(response, age, mean))))
output = c(output, "", capture.output(with(stimulus.frame, tapply(response, exposure, mean))))

output = c(output, sep, capture.output(model.tables(stimulus.aov)))

# The mean square within is needed for the ANOVA
stimulus.SSW = (stimulus.n - 1) * sum(stimulus.variance)
stimulus.MSW = stimulus.SSW / (length(stimulus.frame$response) * (stimulus.n - 1))

# Note that the above weights and then deweights according to group size
stimulus.MSW = sum(stimulus.variance) / length(stimulus.frame$response)

stimulus.squares = data.frame(term = attr(terms(stimulus.aov), "term.labels"),
                              dof = stimulus.summary[[1]]$"Df",
                              sum.sq = stimulus.summary[[1]]$"Sum Sq" * stimulus.n,
                              mean.sq = stimulus.summary[[1]]$"Mean Sq" * stimulus.n)
stimulus.squares$F = stimulus.squares$mean.sq / stimulus.MSW
stimulus.squares$"Pr(>F)" = with(stimulus.squares, pf(F, dof, length(stimulus.frame$response) * (stimulus.n - 1)))

output = c(output, sep, "Two-Way ANOVA\n", capture.output(stimulus.squares))

textplot(output, halign = "left", valign = "top")
title("Visual Stimulus Response Data")

layout(matrix(c(1:2),2,1))

lattice::xyplot(response ~ age + exposure,
                data = stimulus.frame, type = c('g', 'p', 'r'))
with(stimulus.frame, interaction.plot(age, exposure, response))
with(stimulus.frame, interaction.plot(exposure, age, response))

#
# Problem #2
#

snake.n = 8
snake.frame =
  data.frame(avoidance = c(16, 13, 12, 15, 11, 12, 14, 13, 16, 10, 11, 12, 6, 8, 14, 12,
                           14, 16, 17, 15, 13, 17, 15, 16, 13, 7, 3, 10, 4, 2, 4, 9,
                           15, 15, 12, 14, 13, 11, 11, 12, 15, 10, 11, 7, 5, 12, 6, 8),
             therapy = rep(c("Desensitization", "Implosion", "Insight"), each = snake.n * 2),
             phobia = rep(c("Mild", "Severe"), each = snake.n))

snake.means = with(snake.frame, tapply(avoidance, INDEX = list(phobia, therapy), mean))

output = c("Means", capture.output(snake.means))
output = c(output, "", capture.output(with(snake.frame, tapply(avoidance, therapy, mean))))
output = c(output, "", capture.output(with(snake.frame, tapply(avoidance, phobia, mean))))
output = c(output, "", paste("Grand Mean = ", with(snake.frame, mean(avoidance))))

snake.aov = aov(avoidance ~ 1 + therapy + phobia + therapy:phobia, data = snake.frame)
snake.aov = aov(avoidance ~ phobia * therapy, data = snake.frame)
snake.summary = summary(snake.aov)
snake.MSW = snake.summary[[1]]$"Mean Sq"[4]
snake.MSW.df = snake.summary[[1]]$"Df"[4]

output = c(output, sep, "Variances",
           capture.output(with(snake.frame, tapply(avoidance, INDEX = list(phobia, therapy), var))))
output = c(output, "", capture.output(with(snake.frame, tapply(avoidance, therapy, var))))
output = c(output, "", capture.output(with(snake.frame, tapply(avoidance, phobia, var))))
output = c(output, "", paste("Mean Square Within = ", snake.MSW))

output = c(output, sep, capture.output(model.tables(snake.aov)))
output = c(output, sep, "Two-Way ANOVA\n", capture.output(snake.summary))

sig.level = .95

snake.tukey = TukeyHSD(snake.aov, "therapy", conf.level = sig.level, ordered = TRUE)
output = c(output, sep, capture.output(snake.tukey))

J = length(levels(snake.frame$therapy))
snake.min.diff = (qtukey(sig.level, nmeans = J, df = snake.MSW.df)
                  * sqrt(snake.MSW / (snake.n * J)))
output = c(output, sep, "Minimum Mean Differences\n",
           capture.output(cat("Tukey = ", snake.min.diff, "\n")))


layout(matrix(c(1),1,1))

textplot(output, halign = "left", valign = "top")
title("Snake Phobia Response Data")

output = c()

snake.contrast = function(coeff) {
  m = matrix(coeff, 2, 3)
  colnames(m) = levels(snake.frame$therapy)
  rownames(m) = levels(snake.frame$phobia) 
  print(m)
  
  psi = as.vector(snake.means) %*% as.vector(coeff)
  t = psi / sqrt((snake.MSW * sum(coeff ^ 2) / snake.n))
  print(paste("P(<t) = ", pt(t, snake.MSW.df)[[1]]))
  print(paste("P(<F) = ", pf(t ^ 2, 1, snake.MSW.df)[[1]]))
}

output = c(output, "desensitization v. implosion is conditional on severity", "",
           capture.output(snake.contrast(c(-1, 1, 1, -1, 0, 0))))
output = c(output, "", "desensitization v. insight is conditional on severity", "",
           capture.output(snake.contrast(c(-1, 1, 0, 0, 1, -1))))
output = c(output, "", "implosion v. insight is conditional on severity", "",
           capture.output(snake.contrast(c(0, 0, -1, 1, 1, -1))))

output = c(output, sep, "desensitization is conditional on severity", "",
           capture.output(snake.contrast(c(1, -1, 0, 0, 0, 0))))
output = c(output, "", "implosion is conditional on severity", "",
           capture.output(snake.contrast(c(0, 0, 1, -1, 0, 0))))
output = c(output, "", "insight is conditional on severity", "",
           capture.output(snake.contrast(c(0, 0, 0, 0, 1, -1))))

textplot(output, halign = "left", valign = "top")
#title("Snake Phobia Response Data")

## contrasts(snake.frame$therapy)
## contrasts(snake.frame$phobia)

## summary(lm(avoidance ~ phobia, data = snake.frame))
## contrasts(snake.frame$therapy) = snake.contrast(c(-1, 1, 0, 1, -1, 0))
## summary(lm(avoidance ~ phobia, data = snake.frame))

## contrasts(snake.frame$therapy) = snake.contrast(c(-1, 0, 1, 1, 0, -1))
## summary(aov(avoidance ~ therapy * phobia, data = snake.frame))

plot(snake.tukey)

lattice::xyplot(avoidance ~ therapy + phobia,
                data = snake.frame, type = c('g', 'p', 'r'))
boxplot(avoidance ~ therapy + phobia, data = snake.frame)
with(snake.frame, interaction.plot(phobia, therapy, avoidance))

# Don't have time to do these right:
pf(1.977, 2, 42) # Simple effects Mild phobia
pf(6.195, 2, 42) # Simple effects Severe phobia
