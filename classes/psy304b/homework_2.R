# Author: Will Holcomb <wholcomb@gmail.com>
# Date: January 2008
#
# Stress test analysis for Homework #2; Problem #1

require(gplots) # for textplot function
pdf(file = "homework_2_solutions.pdf", width = 8.5, height = 11, family = "Helvetica",
    title = "PSY304B Homework #2 -- Stress Test", bg = "white")

print.varlist <- function(x, ...) {
  cat(paste(format(names(x), width = 15, justify = "right"),
            format(x), sep = " = "), sep = "\n")
}

stress.data = data.frame(High = c(77, 63, 80, 45, 86, 72),
                         Medium = c(34, 72, 60, 44, 39, 48),
                         Low = c(51, 44, 29, 48, 33, 32))

stress.data = stack(stress.data)
colnames(stress.data) = c("Cortisol_Secretion", "Stress_Level")
stress.data$Stress_Level =
  with(stress.data, factor(Stress_Level, levels = unique(Stress_Level)))

# Perform the ANOVA
stress.lm = lm(formula = Cortisol_Secretion ~ Stress_Level, data = stress.data)
stress.anova = anova(stress.lm)

stress.info = capture.output(summary(stress.data))
stress.info = c(stress.info, "---------------", capture.output(summary(stress.lm)))
stress.info = c(stress.info, "---------------", capture.output(stress.anova))

J = length(levels(stress.data$Stress_Level))
# Calculate Cohen's f
JdivN = with(stress.data, (J - 1) / length(Cortisol_Secretion))
# attributes(stress.anova)
MSB = stress.anova[1, 'Mean Sq']; MSB
MSW = stress.anova[2, 'Mean Sq']; MSW

r.square = summary(stress.lm)$adj.r.squared
SSB = stress.anova[1, 'Sum Sq']; SSB
SST = SSB + stress.anova[2, 'Sum Sq']; SST

f.out =
  list("Cohen's f (from mean squares)" = sqrt((JdivN * abs(MSB - MSW)) / MSW),
       "Cohen's f (from R^2)" = sqrt(r.square / (1 - r.square)),
       "omega^2" = (SSB - (J - 1) * MSW) / (SST + MSW))

stress.info = c(stress.info, "---------------",
                capture.output(print.varlist(f.out)))

textplot(stress.info, valign="top")
title("Cortisol Secretion by Stress Level Stats")

layout(matrix(c(1,2,3,4),2,2))
plot(stress.lm)
# abline(stress.lm)

layout(matrix(c(1,2,3,4),1,1))
with(stress.data, boxplot(Cortisol_Secretion ~ Stress_Level))
title("Cortisol Secretion by Stress Level Boxplot")

# You assume that the within-population standard deviations all equal
# 9. You set the Type 1 error rate at alpha = .05. You presume that
# the population means will have the following values: 17.5, 19, 25,
# 20.5. You intend to run 80 subjects in all, with equal n's across
# all 4 groups. Compute your power to reject the null hypothesis under
# these conditions.

within.var = 9 ^ 2
means = c(17.5, 19, 25, 20.5)
N = 80
J = length(means)

# Noncentrality parameter for balanced ANOVA (equal n's)
noncentral.param = (N - (N / J)) * (var(means) / within.var); noncentral.param

means.info =
  capture.output(power.anova.test(groups = J, n = N / J,
                                  between.var = var(means),
                                  within.var = within.var,
                                  sig.level = 0.05))

# You have the same Type 1 error rate and make the same assumptions
# about the population standard deviation and the population means as
# in part a. You still have 80 subjects in all but now you want to
# know how power might change by running 10 subjects in groups A, B,
# and D and 50 subjects in group C. Using GLMPOWER, determine would be
# the power under this subject allocation scheme.

# Quantile of the cutoff point in the central F
central.quart = qf(.05, J - 1, N - J, lower.tail = FALSE)

weighted.means = data.frame(Mean = means, n = c(10, 10, 50, 10))
N = sum(weighted.means$n)

weighted.mean = weighted.mean(weighted.means$Mean, weighted.means$n)

# Noncentrality parameter for unbalanced ANOVA
noncentral.param =
  sum(weighted.means$n * (weighted.means$Mean - weighted.mean) ^ 2) / within.var

# Probability of central quantile in noncentral distribution
noncentral.p = pf(central.quart, J - 1, N - J, noncentral.param,
                  lower.tail = FALSE)

f.out = list("Central 95% Quartile" = central.quart,
             "Non-centrality Parameter" = noncentral.param,
             "Non-central Probability" = noncentral.p)
means.info = c(means.info, "---------------",
               capture.output(print.varlist(f.out)))

desired.cohen.f = .25
groups = length(means)
between.var = within.var * desired.cohen.f ^ 2
sig.level = 0.05
power = .8

means.info =
  c(means.info, "---------------",
    capture.output(power.anova.test(groups = groups,
                                    between.var = between.var,
                                    within.var = within.var,
                                    power = power, sig.level = sig.level)))

# This is identical to above, but the multiplier for lambda is changed
# from the number of groups to the number - 1
p.body <- quote({
  lambda <- groups*n*(between.var/within.var)
  pf(qf(sig.level, groups - 1, (n - 1) * groups, lower.tail = FALSE),
     groups-1, (n - 1) * groups, lambda, lower.tail = FALSE)
})
n = uniroot(function(n) eval(p.body) - power, c(2, 1e+05))$root

n

capture.output(power.anova.test(groups = length(means),
                                between.var = within.var * desired.cohen.f ^ 2,
                                within.var = within.var,
                                n = n, sig.level = 0.1))

textplot(means.info, valign="top")
title("Power Calculations for Means")
