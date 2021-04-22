# Grant Proposal for Mïmis

Mïmis is a P2P collaborative filesystem. Users specify trees from other users to incorporate with their own 

# Manifesto

Intellectually we are dying of thirst sitting in a stream. We have the tools to make huge quantities of information available. I want to create an overlay on the Ceramic network to save all the contextualizations for all the data.

I want for a user to be able to pick an object from the category of discutable things and create a set of "paths" which refer to that concept. Paths capture both objective (`/book/by/Scott Peck/The Road Less Traveled/`) and subjective (`/favorite/books/psychology/1/`) contextualizations.

The user publishes their context forest into a DAG in the Ceramic network where the leaves are IPFS resources (primarily). When a resource is requested, the [resolution algorithm](https://github.com/MetaFam/mimis/#algorithm) builds a render tree from the source tree.

# Problem

* I would like to feed 100,000 books, semantically marked-down, to a chat bot and see how it talks.

* I'd like to be able to view a list of the most influential creative works for anyone on the planet who decided to curate one, along with the ability view those works.

* I'd like to be able to send a cryptocurrency tip for a work and have it configurably divided amongst the creators and curators of that work.

* I'd like for artists to be able to compose images where specific areas as designated as drawing boards for others.

* I'd like to be able to edit the files of any website I visit and have those changes always appear to me locally. Also, allow others to incorporate those changes and bring them to the attention of the original author.

* I'd like for myself and everyone else to have a plethora of options for how to spend our time without the worry of cost, and without the threat of homelessness or starvation. 

# Solution

Mïmis is built on a node-sharded graph stored in Ceramic. Each node in the tree is a separate updatable document. Paths are resolved by walking the graph and building a render tree based on the configuration at each position.

The output for any path is available as both content and context. The context for a location is produced by traversing the render tree after generation is complete. It includes which DIDs were responsible for providing which content and how many contacts' trees included whichever resource.

# Product

What I can get done is highly dependent on the money. I have an increasingly complete vision for how a group of software systems could interact. Mïmis is the base of it all.

Mïmis houses and organizes all the data. As peers are consuming data, they are also sharing their cache of the source tree. Those source trees can be public or private

In addition to Ceramic, I would like users to be able to store a set of nodes locally. These nodes are also used to generate intrefaces and manage the cache, but it is not public that the user is sharing them.

This would allow for `/book/by/Ursula K. LeGuinn/` to resolve to `ceramic://1…` and for `ceramic://1…` to be retrivable from the network reliably even though no one is explicitly hosting it.

Software-wise, there is the client, and Ceramic is really the "server". All the logic for how to expand the source tree to the render tree is local.

# Progress

I have an algorithm for converting a source tree to a render tree. I have maybe 20% of a reasonable visualization.

On the larger front, I can only really focus in depth on one project at a time. I first became convinced I could be an active part of a rapid societal change over a decade ago. I've contemplated hundreds of different use cases for apps in that period, but I really only have the time to reason thoroughly through five or six.

Again, my stretch goal is the end of war, so what percentage of that do I have figured out? 65‒70%. There are three independent striations – the personal, the social, and the planetary – that this change will take place in, and I think I have a reasonably well grounded plan for each.

# Differentiation

No one else is doing this because it tramples over copyright laws, massively simplifies building a child pornography collection, and is great for doxing someone.

# Team

I, Will Holcomb, "Δυς", will take anything you give me up to $10k, match it, and give it to Raid Guild.

# Grant Request

* MC@$7.5k + Δυς@$7.5k = $15k is about the least I'd want to offer Raid Guid for a client library that can resolve resource paths and a web app for creating and browsing those paths in Ceramic.

* MC@$10k + Δυς@$10k = $20k would let us add a command line client that can pull down a directory tree and write one back ala. Git.

* MC@$10k + Δυς@$10k + ETH@$10k = $30k would allow the addition of private source graph storage, a content recommendation engine based on combining forests, and tools for revising the contents of points.

* MC@$10k + Δυς@$10k + ETH@$10k + MG@$10k + TREE@$10k = $50k, and we add the dashcam app. Part of what this system will do is propagate DIDs for everyone. This digital identity can be leveraged to coordinate the movement of agents in the real world.

* $100k and I can build a Warehouse ala. Warhol where talented developers push out finished versions of my half-formed creations.

Project development takes place in terms of sprints aligned to the lunar cycle. Team leads have rotating stays at various luxurious locations in conjunction with other leads. Meetings from these locations are all livestreamed.

* $100k + mass-spectroscopy equipment, and it's full-steam ahead. We open a testing facility for drug supplies that serves as the basis for a reformed (violence, theft, and impurity-free) black market.

* $100k + mass-spectroscopy equipment + an aircraft carrier, uh-oh, this is breaking too fast. Too many people are having unpleasant awakenings to the concept that Jesus is just another historical figure and looking for someone to blame. Bullets are cheap, and crazies aplenty. Time to hit the open seas and become unassassinable for a few years.

# Help

How I would like to see this executed is via recorded pair programming. Eventually, I would like the information to be indexed such that I could choose a line of code and see the discussion for when it was created.

Help at this point, is finding money to give to other people to give to other people, I guess.

Really, if you want to get my Revolutionary calendar to align with the Gregorian one, such that I'm writing on 0/♈/12 rather than -3/♉/22, because historically Joe Biden's was the first Presidency in the Revolution and not his successor, would be for him to stop the dropping of bombs, and to clear the way for us to reform the black market.

# Additional Resources

