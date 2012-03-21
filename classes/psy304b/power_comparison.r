# Author: Will Holcomb <wholcomb@gmail.com>
# Date: March 2008
#
# Attempting to better understand how to empirically cerify the ANOVA
# power calculations using simulations.

# Cohen's effect size f is the ratio between the deviation between the
# populations to the deviation within the population. A medium effect
# size is .25. For a given effect size there is a particular sample
# size required to detect it.

n = 25 # size of each group
J = 4  # number of groups
mean = 0
deviation = 10

sample = rep(rnorm(n = n, mean = mean, sd = deviation), J); sample
#sample.frame = data.frame(name = rep(letters[1:J], each = n),
                          
sample.frame
var(sample.frame)
q()


between.var = var(means)
within.var =  between.var / cohen.f ^ 2
sig.level= 0.1
power = .8

# First compute the power for the ANOVA

power.anova.test(groups = groups,
                 between.var = between.var, within.var =  within.var,
                 sig.level = sig.level,
                 power = power)

# For a two sample case, the anova power and t-test power should be the same
if(groups == 2) {
  # Compare the ANOVA's power to power for the t-test.
  power.t.test(n = NULL, delta = means[1] - means[2], sd = sqrt(within.var),
               sig.level = sig.level, power = power,
               type = c("two.sample"), alternative = c("two.sided"), strict = FALSE)
}

# This is copied from the source for power.anova.test and will
# reproduce the results above

p.body <- quote({
  lambda <- (groups - 1) * n * (between.var / within.var)
  pf(qf(sig.level, groups - 1, (n - 1) * groups, lower.tail = FALSE),
     groups - 1, (n - 1) * groups, lambda, lower.tail = FALSE)
})
n.r = ceiling(uniroot(function(n) eval(p.body) - power, c(2, 1e+05))$root)
n.r

# This is identical to above, but the multiplier for lambda is changed
# from the number of groups to the number - 1

p.body <- quote({
  lambda <- groups * n * (between.var / within.var)
  pf(qf(sig.level, groups - 1, (n - 1) * groups, lower.tail = FALSE),
     groups - 1, (n - 1) * groups, lambda, lower.tail = FALSE)
})
n.adj = ceiling(uniroot(function(n) eval(p.body) - power, c(2, 1e+05))$root)
n.adj

# An empirical method for eliminating theoretical issues in detemining
# which power calculation is correct is to simply simulate.

anova.sim = function(sampleSize, means, within.var, numSimulations = 1000) {
  # Generate a p-value for a given set of means using the global within group variance
  anova.p = function(means) {
    sim.data = lapply(means, function(mean) rnorm(n = sampleSize, mean = mean, sd = sqrt(within.var)))
    sim.frame = do.call(data.frame, sim.data)
    names(sim.frame) = LETTERS[1:length(sim.frame)]
    sim.fit = lm(values ~ ind, data = stack(sim.frame))
    sim.fstat = summary(sim.fit)$fstatistic
    1 - pf(sim.fstat[[1]], sim.fstat[[2]], sim.fstat[[3]])
  }
  unlist(lapply(1:numSimulations, function(x) anova.p(means)))
}
power

numSimulations = 1000

adj.pvalues = anova.sim(n.adj, means, within.var, numSimulations)
adj.power = length(adj.pvalues[adj.pvalues < sig.level]) / length(adj.pvalues); adj.power

r.pvalues = anova.sim(n.r, means, within.var, numSimulations)
r.power = length(r.pvalues[r.pvalues < sig.level]) / length(r.pvalues); r.power
