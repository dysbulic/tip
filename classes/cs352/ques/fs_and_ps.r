library(foreign)

testdata <- read.csv(commandArgs()[5])
tdat <- t.test(testdata$use_Orchestration_1,testdata$use_Orchestration_2,paired=T)
tdat
tdat$pvalue
#pt(testdata$use_Orchestration_1,testdata$use_Orchestration_2)
#print(testdata)

