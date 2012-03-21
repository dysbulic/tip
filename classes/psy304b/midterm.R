require(gplots) # for textplot function
pdf(file = "midterm_solutions.pdf", width = 8.5, height = 11, family = "Helvetica",
    title = "PSY304B Midterm Exam", bg = "white")

sep = "\n**----**\n" # Output separator

# A researcher is interested in comparing the efficacy of 3
# alternative treatments for speech anxiety. Treatment #1 is behavior
# therapy (BT), treatment #2 is the beta-adrenergic blocker Atenolol
# (AT), and treatment #3 is placebo (PL). Out of a total sample size
# of 30, 12 patients are randomly assigned to the BT group, 12
# patients are randomly assigned to the AT group, and 6 patients are
# randomly assigned to the PL group. The primary dependent measure is
# an overall rating of public speaking ability conducted by trained
# raters who are experts in non-verbal aspects of public speaking.
# These ratings were made on a 1 to 10 scale, with higher scores
# indicating better public speaking. The ratings for each group are
# shown below:

# In data.frame if the vectors are not explicitly made the same
# length, their values will repeat

padded.data.frame = function(...) {
  args = list(...)
  maxl = max(sapply(args, length))
  frame = do.call(data.frame,
                  lapply(args, function(x) { length(x) = maxl; x }))
  frame
}

anxiety.data =
  padded.data.frame(Behavior_Therapy = c(6, 7, 6, 5, 5, 5, 7, 8, 9, 6, 6, 7),
                    Atenolol = c(4, 8, 10, 3, 6, 5, 6, 3, 7, 5, 4, 6),
                    Placebo = c(0, 7, 0, 7, 0, 7))

anxiety.data.stack = stack(anxiety.data)
names(anxiety.data.stack) = c("Speaking_Ability", "Treatment")

#anxiety.data$Treatment =
#  with(anxiety.data.stack, factor(Treatment, levels = unique(Treatment)))

# Conduct a one-way analysis of variance to test the null hypothesis
# using SAS. After presenting the results, indicate whether or not the
# results of this test lead you to reject the null hypothesis.

anxiety.lm = lm(formula = Speaking_Ability ~ Treatment,
                data = anxiety.data.stack)
anxiety.anova = anova(anxiety.lm)

output = c("Speech Anxiety Data Summary\n",
           capture.output(summary(anxiety.data)))
output = c(output, sep, capture.output(anxiety.anova))

# Conduct a statistical test assessing whether the assumption of
# homogeneity of variance is violated.

require(car)
output =
  c(output, sep,
    capture.output(with(anxiety.data.stack,
                        levene.test(Speaking_Ability, Treatment))))

# Based on the results of this test, what do you conclude?

printvars = function (frame, title = "Data Variances") {
  names = format(names(frame), justify = "right")
  vars = lapply(as.list(na.omit(frame)), var)
  vars.lines = paste(names, " = ", format(vars, digits = 3), sep = "")
  cat(title, "\n\n")
  cat(vars.lines, sep = "\n")
}

output =
  c(output, sep,
    capture.output(printvars(anxiety.data,
                   title = "Speech Anxiety Data Variances")))

# Conduct an alternative test of the null hypothesis that you tested
# in part a. Unlike the ANOVA, this test would not assume homogeneity
# of the population variances.

anxiety.welch =
  oneway.test(formula = Speaking_Ability ~ Treatment,
              data = anxiety.data.stack,
              var.equal = FALSE)

output = c(output, sep, capture.output(anxiety.welch))

textplot(output, halign = "left", valign = "top")
title("Variance Analysis for Speech Anxiety Treatments")

layout(matrix(c(1:4),2,2))
plot(anxiety.lm)
#title("Variance Graphs for Speech Anxiety Treatments")

# A researcher is interested in assessing the effects of environmental
# stimulation (high/low) and predictability of diet
# (predictable/unpredictable) on levels of the hormone prolactin (PRL)
# in rat pups. 32 rat pups are randomly assigned to the 4 conditions,
# with 8 pups per condition. The 4 conditions are as follows:
  
rat.data = data.frame("S+/P+" = c(25,23,18,16,12,19,20,21),
                      "S+/P-" = c(18,17,16,11,14,15,21,12),
                      "S-/P+" = c(20,12,15,13,8,17,17,18),
                      "S-/P-" = c(12,15,17,10,18,10,9,14),
                      check.names = FALSE)
rat.data.stack = stack(rat.data)
names(rat.data.stack) = c("Response", "Effect")

output = capture.output(summary(rat.data))

output = c(output, sep, "Variances\n", capture.output(var(rat.data)))

rat.lm = lm(formula = Response ~ Effect, data = rat.data.stack)

scores = c()

# What would be the minimum difference between means necessary to find
# at least one significant effect if the researcher were to conduct a
# Bonferroni test

sig.level = .05
MSW = anova(rat.lm)[2, "Mean Sq"]; MSW
N = length(rat.data.stack[[1]]); N
J = length(names(rat.data)); J
K = 6

scores =
  c(scores,
    abs(qt(sig.level / (2 * K), N - J) * sqrt(MSW * 2 / (N / J))))

# What would be the minimum difference between means necessary to find
# at least one significant effect if the researcher were to conduct a
# Tukey HSD test

scores =
  c(scores,
    qtukey(1 - sig.level, nmeans = J, df = N - J) * sqrt(MSW / (N / J)))

# What would be the minimum difference between means necessary to find
# at least one significant effect if the researcher were to conduct a
# Sheffe test
scores =
  c(scores,
    sqrt((J - 1) * qf(1 - sig.level, J - 1, N - J) * MSW * (2 / (N / J))))

tests = c("Bonferroni", "Tukey", "Sheffe")
tests = format(tests, justify = "right")
tests.lines = paste(tests, " = ", format(scores, digits = 3), sep = "")
output = c(output, sep, "Minimum Mean Difference\n",
           capture.output(cat(tests.lines, sep = "\n")))

# Conduct all possible pairwise comparisons via the Tukey HSD method.

rat.aov = aov(Response ~ Effect, data = rat.data.stack)
output = c(output, sep, capture.output(summary(rat.aov)))

rat.tukey = TukeyHSD(rat.aov, "Effect", conf.level = .95, ordered = TRUE)
output = c(output, sep, capture.output(rat.tukey))

# Conduct all possible pairwise comparisons via the Scheffe method.

# Maximum contrast coefficients
rat.mean = mean(rat.data.stack$Response)
rat.max.coeff = length(rat.data) * (mean(rat.data) - rat.mean)

output = c(output, sep, "Maximum Contrast Coefficients\n",
           capture.output(rat.max.coeff))

# Maximum contrast
rat.max.contrast = sum(rat.max.coeff * mean(rat.data))

# There has to be a cleaner way to get this
rat.length = unlist(lapply(as.list(rat.data), length))

# Maximum Sum of Squares Contrast
rat.SSC = rat.max.contrast ^ 2 / sum(rat.max.coeff ^ 2 / rat.length)

# Maximum F-value for Contrsts
MSE = sum(resid(rat.aov) ^ 2) / rat.aov$df.residual
rat.contrast.max.f = rat.SSC / ((rat.aov$rank - 1) * MSE)
rat.contrast.max.f
rat.sig.level.scheffe =
  pf(rat.contrast.max.f, rat.aov$rank - 1, rat.aov$df.residual)

output = c(output, sep, "Scheffe Corrected Error Rate\n",
           capture.output(rat.sig.level.scheffe))

rat.model = model.tables(rat.aov, "means")
rat.means.table = rat.model$tables[-1]

name = names(rat.means.table)
means <- as.vector(rat.means.table[[name]]); means
# means <- as.vector(mtt); means
n <- rat.model$n[name][[name]]
n <- rep.int(n, length(means))
center <- outer(means, means, "-"); center
keep <- lower.tri(center); keep
center <- center[keep]; center
width <- (qf(rat.sig.level.scheffe, rat.aov$rank - 1, rat.aov$df.residual) *
          sqrt((MSE / 2) * outer(2 / n, 2 / n, "+"))[keep]); width
est <- center / (sqrt((MSE / 2) * outer(2 / n, 2 / n, "+"))[keep])
pvals <- pf(abs(est), rat.aov$rank - 1, rat.aov$df.residual); pvals
dnames <- list(NULL, c("diff", "lwr", "upr","p adj"))
names <- names(rat.data)
dnames[[1]] <- outer(names, names, paste, sep = " v ")[keep]
rat.scheffe.ci =
  array(c(center, center - width, center + width, pvals),
        c(length(width), 4), dnames); rat.scheffe.ci
output = c(output, sep, "Scheffe Confidence Intervals\n",
           capture.output(rat.scheffe.ci))

layout(matrix(c(1),1,1))
textplot(output, halign = "left", valign = "top")
title("Rat Stimulation Data Statistics")

layout(matrix(c(1),1,1))
plot(rat.tukey)

confidence.ellipse(rat.lm)

makeSVG = FALSE
for(e in commandArgs()) {
  if(e == "--svg") makeSVG = TRUE
}
if(makeSVG) {
  # When running without a X server, this code will segfault
  library(cairoDevice)
  Cairo_svg("midterm-tukey.svg", width = "9", height = "11.5")
  plot(rat.tukey)
}
