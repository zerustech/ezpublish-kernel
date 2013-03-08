eZ Publish: Multi Site, Requirements
====================================


Introduction
------------

This document is divided into two sections. The first section presents the
multi site system in eZ Publish 3/4. The second section discusses the
requirements of the multi site system for eZ Publish 5.

eZ Publish 3/4
--------------

### SiteAccess

eZ Publish legacy have a very simple and abstracted approach to multi sites.
It has a SiteAccess system, where the word "site" does not really mean a site, so
you can look at it as a list of valid accesses to the system. This has nothing to
do with permissions either, a SiteAccess rather represent a set of settings.

One of the SiteAccesses is selected based on a list of match orders which contains
what kind of matching is enabled, could be host, uri, port, environment variable or
some of the combinations of them. This matching types further contain a set of matching
rules and first one that matches the current request will specify which SiteAccess is
the current one. 

This SiteAccess contains all configuration needed for the access, which database, which
languages, which design and by that also if it represent backend or front-end. 

This system has served eZ Publish well, it is flexible enough to allow very different
sites and different entry points to a site to exists on the same eZ Publish installation.
Together with the extensions system it has both allowed extensions to be loaded depending
on these setting, and allowed extensions to represent a full site with all needed
SiteAccesses to represent it.


### Muti site features

As-is it allows several sites to live side by side using different databases and
different storage / cache folders. Language switching was for a long time logic residing
in templates, but in 3.10 and later versions a language switching system was created with
a set of settings to go with it for which siteaccess represent which language in a site,
and a system cable of doing reverse lockup by loading the settings for given sisteacces.

Also for multi site installations in same database certain settings was added over the
years to make it feasible. The most prominent once are one for specifying the content root
of the site, one for specifying path prefix to strip from urls, one for index page and
certain others.


### Issues

This system is not without issues:
* Given it's abstract nature it is impossible to know what a SiteAccess represent
** There are no conventions, certain partners and solutions defines conventions
   and it works for them. But the base system has none, and every multi site feature
   basically adds yet more settings and systems to manage to work.
* A lot of repeated configuration, cases you would have to create a new siteaccess:
** if you want a new site language
** if you want to create a alternative website, for mobile, TV, car or something else
** If you want to add an additional site you would need to create several
* As most of the multi site, multi language features are built on top of the kernel, they
  all have different edge cases where they don't fully work, or break down.
* Multisite same-db setups have no separation between the different sites, they share
  users, media files, content, permissions, (..). A url to some of these that belong to
  another site will work, and can often end up being indexed by search spiders.
* 


eZ Publish 5
------------

### Limitations

Before discussing the requirements, we must first list limitations for a multi site system
in eZ Publish 5, both in terms of backwards compatibility with legacy and with what
is already shipped as part of eZ Publish 5.0/5.1.

Limitations:
* Must be able to map to legacy siteaccess for 5.x to legacy integration to work
* Can not break the existing Public (PHP) API
* Must not-break/provide-clear-upgrade-path-for exiting 5.x sites using it's improved
  SiteAccess system [improvements are: SiteAccess grouping & mapping extensibility]


### Platform Goal

An overall goal for multi site on eZ Publish Platform would be to allow admins to be able
to much more easily create new sites using GUI in same-database (same "Repository")
or with new database (additional "Repositories"). The new site could start out as either
a empty site using a package/site-template or clone existing site. Another high level goal
would be to improve maintainability of multisite installations & make them easier to grasp.


### Requirements

Given the goal and limitations, requirements for multi site in eZ Publish 5.x Core are:
* Must haves:
    * Be native to the configuration system
    * Add strong conventions for how languages, sites, channels & backend is handled
* Should haves:
    * Native model knowledge of what a site is for multisite same-db installs so GUI to
      create new sites, and managing sites is possible.
* Could haves:
    * Better separation of content in same-db installs so links to other sites content
      does not get exposed, but same-db setups should continue to play on the strengths
      of eZ Publish, thus within permission rules the media files, users and also content
      can be used across sites.

Author note: The last item is a could as it might contradict limitations #2
