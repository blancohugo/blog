@title How to put together a software scalability plan
@author Hugo Blanco
@date 2021-06-07
@summary Understand some scalability concepts and the importance of having a plan
@published true

This is part of a Ubitalk I presented and you can see the video here:
<iframe width="100%" height="500px" src="https://www.youtube.com/embed/2odaSDonkMA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

## What is scalability?

I was looking for a good definition and found this one, from Full Scale Blog, that perfectly defines scalability: 

> Software scalability is an attribute of a tool or a system to increase its capacity and functionalities based on its users’ demand. Scalable software can remain stable while adapting to changes, upgrades, overhauls, and resource reduction.

So if we have a higher user demand on a tool or a system, it should scale proportionally, also keeping it stable. The definition is simple, but the ways of doing that are not so straightforward.

## Bottlenecks are a reality

Believe it or not, every application has a bottlenect, and that's a fact!

A bottleneck is a situation that stops a process or activity from progressing.

![Bottleneck](https://i.imgur.com/WvlOAz1.png?1)

We all will discover that our software has flaws, sooner or later, such as:
- Database will be unavailable after a certain number of concurrent users
- Application server won’t handle the amount of memory used to get a large dataset
- Processing delay will be significant, for large input/output files
- etc.

Of course, the idea here is to discover that sooner than later and the be prepared. And have no doubts: failures will happen.

## So having a scalability plan?

Now that we know we’ll fail and we have a bottleneck at some level, why having a scalability plan? Because we need to understand the points of failure and be prepared to them.

The scalability plan allows your business to remain stable and productive, specifically because of the benefits in the long run:
- Lower costs - we don’t have to spent a lot of time working on a fix or making drastic changes we didn’t expect
- Higher agility - having a plan for what to do in case of emergencies will save a lot of time spent discussing the best possible implementation
- Cheaper upgrade expenses - we will discuss this very soon, but the upgrades can be really expensive if we don't have a plan

Right, let's talk about some definitions, before.

## Scalability types

These are not academic definitions and I don't believe that there's a consensus, but I based the next topics on my observations and the experiences I had on my career.

### Resource scalability

How do we scale resources? Just by making more resources available. That simple.

And we have 2 ways of doing that:
- Horizontal scaling (scale out)
- Vertical scaling (scale up)

#### Horizontal scaling

Horizontal scaling is done by adding more machines to your pool of resources and it can be configured to be elastic — new instances added automatically as load increases.

We have, for example, the ELB on Amazon AWS, that is Elastic Load Balancer. And ELB can simply add or remove resources, depending on the load.
As load increases, new server replicas are added to the pool and when they are not needed anymore, they are removed.

![Horizontal Scaling](https://i.imgur.com/SVPrE3I.png?1)

#### Vertical scaling

Vertical scaling is done by adding more power (e.g. CPU, RAM) to an existing machine. It's most likely the emergency plan when there's no plan.

When we don't think about the infrastructure we're using and how it can be implemented, the initial reaction is actually to add more power to the machine. But when we talk about distributed architecture, we’re talking about having clusters. 

We have different names for the additional machines, depending on the server, like replicas, nodes or shards, but they are all referring to processing division.

![Vertical Scaling](https://i.imgur.com/zLnnZzdm.png?1)

### Data scalability

We can measure this by *how well the data layer responds to additional load coming from processing layer*.

Supposing that we're using an elastic load balacing (ELB) and adding more machines to a poll of resources, as the system’s processing layer scales, more load is placed on the data tier. If this data tier is shared, your database engine will eventually run out of steam and more radical changes are needed.

This process requires more than resource scaling because, although databases are still resources, the data read and write operations will be impacted differently than the processing layer.

#### Single-node databases

It’s possible to have availability and consistency at the same time with single-node databases because they have, as the name suggests, a single node to process read and write requests - there's also no subnet to cause any disruption.

![Single Node 1](https://i.imgur.com/StQng94.png?2)

But, if something goes wrong with the database, there is no response and the data is not accessible.

![Single Node 2](https://i.imgur.com/SLXXtlt.png?1)

#### Multi-node databases

Multi-node databases use horizontal scalability, adding more machines to the poll of resources, being more predictable, cheaper and more manageable.
These are less susceptible to the problem listed above, but in order to have network partition tolerance, some other points can be compromised.

#### CAP theorem

To discuss the pros and cons of each database engine, and to identify what fits best for each type of application, we should understand the CAP theorem - also named Brewer’s theorem.

![CAP theorem](https://i.imgur.com/mkszOgmm.png?1)

The theorem created by Eric Brewer, a professor and vice-president of infrastructure at Google, states that it’s impossible for distributed datastore to provide all three guarantees at the same time.

And what are these guarantees?

- C — Consistency
  All clients see the same data at the same time, no matter which node they connect to
- A — Availability
  Any client making a request for data gets a response, even if one or more nodes are down
- P — Partition tolerance
  The cluster must continue to work despite any number of communication breakdowns between nodes in the system

#### CAP in noSQL databases

##### Mongo — CP (Consistency, Partition tolerance)

Mongo resolves network partitions by maintaining consistency, while compromising on availability. It has a single-master system where, if the single writer becomes unavailable, a reader will be promoted but the write operations will be unavailable.

![Single-master](https://i.imgur.com/EA1fAq2.png?1)

Promoting a different node, after the original master became unavailable:
![Promoting master](https://i.imgur.com/jyboe7k.png?1)

##### Cassandra — AP (Availability, Partition tolerance)

Cassandra has a masterless architecture, when there is "main" node in a cluster. It provides eventual consistency by allowing clients to write to any nodes at any time and reconciling inconsistencies as quickly as possible.

![Masterless](https://i.imgur.com/8k5cQS3.png?1)

#### CAP in relational databases

Some database engines can support single-node and multi-node. As clusters, some of them may sacrifice availability, having eventual consistency, with the single-master architecture. 

### Feature scalability

We can measure this by _how expandable and modifiable the application is, without drastic changes_.
Let's separate it into 2 sections:
- Functionality scability
- Code scalability

#### Scaling functionality

Laying out a foundation is crucial, so have a good understanding of how far the application can go and think about a solid base. Consider the factors of developing new features and make sure the architecture is flexible enough to grow.

But don't go too far at the start - think of the crucial features and develop an MVP (Minimum Viable Product) for the opportunity to "fail fast".

#### Scaling code

Scaling code is a bit different than scaling functionality, because the goal is to have a larger, yet effective codebase.

A scalable code is not just code that can be understood by the machine, but one that is understood by humans. Because after all, we write code for humans. So clean code plays an essential role in scaling.
The code should be well-written and well-documented, especially for our selves of the future — who never looked at their own code from months ago and didn't understand how it worked?

Also, be careful with over-engineering, avoiding too many abstractions and looking too far into in the future. We might be trying to solve a problem we will never have.

### Additional tips

Other than the scalability types listed above, here are some additional tips.

#### Internal testing

Before release a product or a feature, invest in internal testing, doing "doogfooding sessions" and gathering feedback from the people that were not directly involved in the creation process.

#### Observability

How do we know if our application is working? How do we check the infrastructure changes? Making them observable.
So make sure the application has good meaninful metrics, log messages and the stakeholders are alerted prior to receiving customer complaints.

#### Cache

Cache can be your ally, so use it wisely. You can avoid making unnecessary resource requests caching the results. 

## How do I put together a plan, then?

Alright, we talked about resource, data, feature and code scalability, but how do I put together a plan? Where do I start?

- Consider it early and, when it is most needed, you can reap the benefits in agility
- Understand your user and how they will use your application
- Have a good understanding of your data layer and the best infrastructure for it
- Try to predict the growth — check the usage of similar applications
- Always opt for resources that allow horizontal scaling, since they are more predictable
- Write a good documentation for the project and gather feedback from all stakeholders — devs, leads, architects etc.
- Have good runbooks for scaling, when it is needed

Prioritization of scalability prepares your project for success!