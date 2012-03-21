# Author: Will Holcomb <wholcomb@gmail.com>
# Date: January 2008
#
# Simple exploratory data analysis for the purpose of learning R 

# The data may be entered manually into the program source

# drugs = c("Zoloft", "Naltrexone", "Valium")
# spiderdata =
#   data.frame(Zoloft = c(9, 11, 5, 12, 15, 14, 13, 12, 7, 6),
#              Naltrexone = c(15, 16, 12, 12, 18, 19, 23, 20, 13, 17),
#              Valium = c(9, 11, 12, 5, 13, 15, 11, 8, 6, 9))

# Data may also be read from a CSV file

pdf(file = "homework_1_solutions.pdf", width = 8.5, height = 11, family = "Helvetica",
    title = "PSY304B Homework #1", bg = "white")

library(foreign)
spiderdata <- read.csv("spider_data.csv")

# The CSV file format is incorrect for the analysis however. Each
# factor is a header on a column and has to be duplicated.

drugs = lapply(names(spiderdata),
               FUN = function(x) rep(x, each = length(spiderdata[,x])))
spiderdata = data.frame(Drug = unlist(drugs),
                        Response = unlist(spiderdata, use.names = F))

# This allows the columns to be uneven lengths
spiderdata = na.omit(spiderdata)

J = length(unique(spiderdata$Drug))
N = length(spiderdata[,1])

# First, compute a one-way ANOVA by hand simply to verify the numbers

SSW = sum(ave(spiderdata$Response, spiderdata$Drug,
              FUN = function(x) x - mean(x)) ^ 2)
SSB = sum(tapply(spiderdata$Response, spiderdata$Drug,
                 FUN = function(x) length(x) *
                                   (mean(x) - mean(spiderdata$Response)) ^ 2))
MSW = SSW / (N - J)
MSB = SSB / (J - 1)
F = MSB / MSW
f_prob = pf(F, J - 1, N - J)
reject_point = qf(.95, J - 1, N - J)

summary(spiderdata)
cat("SSW:", SSW, ", MSW:", MSW, ", SSB:", SSB, ", MSB:", MSB, ", ",
    "F:", F, ", P(F):", f_prob, ", P(", reject_point, ") = .95\n", sep = "")

# Next use the automated tools to generate the same information

anova(lm(Response ~ Drug, data = spiderdata))

spideraov = aov(Response ~ Drug, data = spiderdata, projections = T)
summary(spideraov)
plot(TukeyHSD(spideraov, "Drug"))
