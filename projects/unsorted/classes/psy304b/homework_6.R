require(gplots) # for textplot function
pdf(file = "homework_6_solutions.pdf", width = 8.5, height = 11, family = "Helvetica",
    title = "PSY304B Homework #6", bg = "white")

require(nlme)

sep = "\n**----**\n" # Output separator
options(show.signif.stars = FALSE)

clip.frame =
  data.frame(clip_1 = c(51, 62, 67, 56, 60, 63, 65, 60, 65, 34),
             clip_2 = c(50, 52, 33, 59, 51, 54, 68, 51, 60, 43),
             clip_3 = c(56, 53, 38, 41, 33, 49, 64, 49, 50, 53),
             clip_4 = c(66, 73, 74, 71, 51, 49, 57, 60, 68, 54),
             clip_5 = c(46, 65, 60, 53, 42, 61, 57, 64, 40, 55))
clip.frame.stack = stack(clip.frame)
colnames(clip.frame.stack) = c("Response", "Clip_Number")

boxplot(clip.frame)
# lmList(Response ~ Clip_Number, data = clip.frame.stack)

clip.aov = aov(Response ~ Clip_Number, data = clip.frame.stack)
clip.summary = summary(clip.aov)
clip.summary

clip.lme.1 <- lme(Response ~ Clip_Number, random = ~ 1 | Clip_Number, data = clip.frame.stack)
clip.lme.2 <- lm(Response ~ Clip_Number, data = clip.frame.stack)
anova(clip.lme.1, clip.lme.2)

MSB = clip.summary[[1]]$"Mean Sq"[1]
MSW = clip.summary[[1]]$"Mean Sq"[2]
n = 10

clip.var.rho = (MSB - MSW) / (MSB + (n - 1) * MSW)

MSB; MSW; clip.var.rho

drug.frame =
  data.frame(response = c(81, 91, 67, 109, 93, 95, 106, 105, 109,
                          86, 75, 79, 105, 111, 95, 111, 106, 102,
                          89, 95, 99, 106, 115, 102, 106, 115, 102,
                          106, 111, 103, 115, 117, 106, 111, 118, 114),
             hospital = rep(c("LA_General", "Chicago_VA",
                              "Des_Moines_Baptist", "Nashville_Centennial"),
                            each = 9),
             drug = rep(LETTERS[1:3], each = 3))

with(drug.frame, tapply(response, INDEX = list(hospital, drug), mean))
# output = c(output, "", capture.output(
with(drug.frame, tapply(response, drug, mean))
with(drug.frame, tapply(response, hospital, mean))
mean(drug.frame$response)

# fixed effects
drug.aov = aov(response ~ hospital * drug, data = drug.frame)
summary(drug.aov)

drug.lme = lme(response ~ drug * hospital, random = ~ 1 | hospital, data = drug.frame)
summary(drug.lme)


