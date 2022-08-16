![Header](https://github.com/dhappy/forests/raw/main/public/header.svg)

### 1. What is your project?

𝔐𝔦̈𝔪𝔦𝔰 spiders an IPFS filesystem (comprehending symlinks) into a Neo4j graph database instance in a tree rooted using their ENS name obtained from Sign-In With Ethereum.

𝔐𝔦̈𝔪𝔦𝔰 as a “filesystem” differs from a traditional directory tree in several ways. Resources can appear at multiple paths through the tree *(akin to symlinking)*, and each location resolves to an ordered list of resources. Ideally, *any* coherent path to a resource should resolve to it. Also, selection can be done using a single path or by conglomerating several.

### 2. Project Links

* [Github Repository](https://github.com/dhappy/forests/)
* [Demo](https://mimisb.run)

*My project is currently licensed using the [Creative Commons Zero License](https://creativecommons.org/share-your-work/public-domain/cc0/) which puts it in the public domain. I assume that's acceptable.*

### 3. How is IPFS, Filecoin, or related technology used in this project?

The Neo4j instance stores context about blobs stored in IPFS.

### 4. How will you improve your project with this grant? What steps will you take to meet this objective?

For HackFS, I did a simple app to spider an IPFS filesystem, decode symlinks, and store the structure in a Neo4j instance. There's also an interface for inputting a set of paths and displaying the images at their intersection.

I want to [add a page](//github.com/dhappy/forests/issues/6) giving details about a resource: which paths lead to it, and what the alternatives are for completion.

I'd like for this system to serve as the basis for a proofreading framework. A Git commit history is essentially a blockchain where each commit refers to the previous one. I want to [mimic that structure](//github.com/dhappy/forests/issues/14) in Neo4j and add versioning.

This grant would cover the cost of a few months of Aura, Neo4j's cloud instances which run ~$75 / month at the low end.

Ultimately, I want to use this interface to allow users to create composites from hundreds of thousands of SVGs to design wraps for 13 Cybertrucks which I get to have access to for a year before they go to the auction winners.

<p align="center">
  <img src="https://github.com/dhappy/forests/raw/main/public/cybertruck.svg" 
  width="350"/>
</p>

To this end, I want to allow for a structure where a group of images are shown in a conglomerate at higher in the tree, but then it's possible to decompose the image into accessible parts.

### 5. Do you agree to share grant reports upon request, including a final grant report at the end of the three month period?

I do.

### 6. Does your proposal comply with our [Community Code of Conduct](https://github.com/filecoin-project/community/blob/master/CODE_OF_CONDUCT.md)?

It does.

### 7. Links and Submissions

[HackFS 2022](https://ethglobal.com/showcase/mimis-zd5sn)

### 8. Additional Questions

#### Personal Information

* I'm [dysbulic](https://dhappy.org)
* Principal Architect & Developer of 𝔐𝔦̈𝔪𝔦𝔰
* E-mailable at [dys@dhappy.org](mailto:dys@dhappy.org)
* [@dysbulic on GitHub](https://github.com/dysbulic)

#### How did you learn about our microgrant program?

An e-mail from [niki.gokani@protocol.ai](mailto:niki.gokani@protocol.ai).

<p align="center">
  <img src="https://github.com/dhappy/forests/raw/main/public/logo.svg" width="200"/>
</p>
