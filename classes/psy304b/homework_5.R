require(gplots) # for textplot function
pdf(file = "homework_5_solutions.pdf", width = 8.5, height = 11, family = "Helvetica",
    title = "PSY304B Homework #5", bg = "white")

sep = "\n**----**\n" # Output separator
options(show.signif.stars = FALSE)

RespPers = c(34, 49, 38, 30)
RespNoPers = c(23, 29, 19, 44, 22, 30, 27, 30, 29, 27, 34)
NoRespPers = c(54, 49, 24, 25, 47, 50, 34, 39, 44)
NoRespNoPers = c(40, 36, 39)
prolactin.frame =
  data.frame(prolactin_level = c(RespPers, RespNoPers, NoRespPers, NoRespNoPers),
             responder = factor(c(rep(TRUE, length(RespPers) + length(RespNoPers)),
                                  rep(FALSE, length(NoRespPers) + length(NoRespNoPers))),
                                levels = c(TRUE, FALSE)),
             disorder = factor(c(rep(TRUE, length(RespPers)),
                                 rep(FALSE, length(RespNoPers)),
                                 rep(TRUE, length(NoRespPers)),
                                 rep(FALSE, length(NoRespNoPers))),
                                levels = c(TRUE, FALSE)))

prolactin.aov = aov(prolactin_level ~ responder * disorder, data = prolactin.frame)

output = c(capture.output(summary(prolactin.frame)))
output = c(output, sep, "Frequency Table\n",
           capture.output(with(prolactin.frame, table(responder, disorder))))
output = c(output, sep, "Cell Means\n",
           capture.output(with(prolactin.frame,
                               tapply(prolactin_level, INDEX = list(responder, disorder), mean))))
output = c(output, sep, "Two-Way Type I ANOVA\n",
           capture.output(summary(prolactin.aov)))
output = c(output, sep, "Two-Way Type III ANOVA\n",
           capture.output(drop1(prolactin.aov, .~., test = "F")))

textplot(output, halign = "left", valign = "top")
title("Effects of Antidepressant Response and Personality Disorder on Prolactin Levels")

#prolactin.tukey = TukeyHSD(prolactin.aov, "disorder", conf.level = .95, ordered = TRUE)
#prolactin.tukey
#plot(prolactin.tukey)
