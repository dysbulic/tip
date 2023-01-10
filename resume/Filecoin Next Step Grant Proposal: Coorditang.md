<img src="https://raw.githubusercontent.com/MetaFam/rewards/main/public/splash.paths.svg" height="300" style="display: block; margin: auto">

### 1. What is your project, and what problem does it solve?

[SourceCred](https://sourcecred.io) is a system for the algorithmic evaluation of contributions to an organization. A graph is generated of events in Discord, GitHub, and Discourse; then PageRank is used to determine a token distribution.

SourceCred was grant supported for the first few years of its existence, but switched to a purely volunteer basis about nine months ago for lack of a developer community. Since it has received only minimal support.

My proposal is to fix some fundamental issues with the system, then add a modified version of the [Coordinape](https://coordinape.com) backed by the Ceramic network to add a more subjective component to the evaluation.

### 2. Project Links
  
[GitHub Repo](https://github.com/MetaFam/rewards/)
[Demo](https://coorditang.vercel.app)
License: Creative Commons Zero

### 3. a) How is IPFS, Filecoin, or related technology used in this project? (max 200 words)
<!-- Outline your project's technical design, including details of how it uses IPFS, Filecoin, or related technologies include any APIs, services, or tools -->

The Ceramic network is a distributed mutability layer backed by IPFS. I will use it to store the information about token distributions and the attestations of contributions to the organization that participants create to boost the probability they are given tokens.

### 3. b) Is this project contributing to the upcoming FVM launch?

No.

### 4. How will you improve your project with this grant? What steps will you take to meet this objective?

My first task will be to address two significant performance issues with SourceCred: the entire graph is loaded into memory to perform the token distribution and the entire history of each service is downloaded for each run.

To deal with the memory usage issue, I will implement an alternative backing store in the graph database Neo4j. This will both eliminate the system taxing memory problem and allow for the development of complex visualizations and explanations of the system's operation.

To reduce the traffic associated with a complete refresh, I will add parameters to the scaping script to allow a rolling window of updates which will do a complete sync over the course of say a week.

The next step will be new work to create a new input to the graph using the token distribution method popularized by Coordinape. Each epoch participants are given a token allotment that they distribute to their peers based on their evaluation of the value of their contributions.

Coordinape is currenly backed by Hasura. My version will be distributed using the Ceramic network. Also it will add the concept of a multitiered approach where a top-level circle decides how much tokens each guild will get, then the guild circles distribute tokens to participants.

### 5. Do you agree to share grant reports upon request, including a final grant report at the end of the three month period?

Yes. Actually, I have an [existing grant](https://github.com/filecoin-project/devgrants/issues/873), and I would like this grant to replace that one which hasn't seen the progress I had hoped for.

### 6. Does your proposal comply with our Community Code of Conduct?

Yes.

### 7. Links and Submissions

This project is borne of [MetaGame](https://metagame.wtf)'s use of SourceCred since its inception and the issues we have encountered.

### 8. Additional Questions

### 8. a) Contact Information

[dysbulic](https://twitter.com/dysbulic)
[dys@dhappy.org](mailto:dysbulic%20%3Cdys@dhappy.org%3E)
[dysbulic on GitHub](https://github.com/dysbulic)
Sole Architect & Developer

### 8. b) How did you learn about our microgrant program?

I was initally contacted by [niki.gokani@protocol.ai](mailto:niki.gokani@protocol.ai) about my HackFS submission.
