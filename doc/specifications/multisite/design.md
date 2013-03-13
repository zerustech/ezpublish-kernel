eZ Publish: Multi Site, Design
==============================

AUTHOR NOTE: Everything with >>>A proposal<<<Or existing way>>> is to be
             considered as topic for consideration, but doubtful.

Status: DRAFT


Intro: Concept
--------------

The aim of this design proposal is to fulfill the requirements by adding new concepts
and add soft conventions on existing features within eZ Publish 5.x stack, while making
sure bc with legacy is kept. These new conventions are optional, but a requirement to be
compatible with any MultiSite functionality, and hence introduces bc break to existing
SiteAccess yml configuration from 5.0/5.1 if you choose to follow them.


### Difference between MultiSite & MultiRepository

With eZ Publish 5.x came the introduction of the Repository. Given a Repository
can technically be connected to different backends, which might be a database or
a NoSQL solution, referring to database or db can be miss-leading.

So instead of using existing terms like same-db and multi-db multi site
setup's with eZ Publish, we can clean up the terminology:

- MultiSite: Several sites within a Repository, shares content, media, users and permissions.
             Refereed to as same-db multi site setup before.
- MultiRepository: One or more sites pr repository only sharing eZ Publish installation and
             infrastructure. Referred to as multi-db  multi site setup before.

Just like in legacy, these can be combined in same eZ Publish installation.
But to get the difference, here is an overview of this proposal:
Server >-< Installation -< SiteGroup (- Repository) -< SiteAccess (- Site) -< LanguageAccess * DesignAccess

LanguageAccess and DesignAccess is here proposed as a sub access of SiteAccess, but
could potentially be configured globally for the install, per SiteGroup or per SiteAccess.


### Changes to existing concepts

This design proposal adds new conventions and meaning to some existing concepts.

- SiteAccess: Is now a representation of a site, and defines settings specific to the (web) site.
             Example is setting for specifying root location of Site, as SiteAccess and Site now
             has a one to one relationship.
- SiteGroup: A group of sites within the same repository, defines settings connected to the
             repository, like backend/persistence settings or REST settings.


### New Concepts

- Site: Is the <model> of the site, it is a content object in the content tree #1 defining
        the root of a site and #2 with loose conventions it can contain some configuration
        for the site for the site editor to edit.
- LanguageAccess: Defines a sub access for SiteAccess for matching which language is currently
        the correct one, this can be shared across several SiteAccesses and SiteGroups, but it
        should also be possible to define custom once for custom fallback rules using a convention.
        >>>Default matching: It should be possible to inject custom logic for default matching so
        language access can be matched by cookie / accept languages before redirect<<<Or we document
        this needs to be done in apache config / vcl>>>
- DesignAccess: Defines a sub access for SiteAccess for matching which design is currently
        the correct one, example would be custom design for tv, mobile, wap & web.
        >>>Default matching: It should be possible to inject custom logic for default matching so
        design access can be matched by cookie / user agent before redirect<<<Or we document
        this needs to be done in apache config / vcl>>>


### How does it affect existing features?

As before a eZ Publish repository shares content, media files, users and permissions, so what
Site and below adds to the mix is the definitions about how the front end should behave.


Certain features are attached at different levels and will ignore concepts like Site, designs
and languages if they don't apply to the feature in  question, here are some examples:

- RestBundle :     Connected to SiteGroup (Repository)
- AdminBundle:     --- " ---, but interface language is selected by language match.
                   Could potentially also support the design system if for mobile / tablet
                   representation, but by default just responsive.
                   >>>NOTE: need to map to legacy somehow, so maybe the /admin should be
                   mapped as a "design" but as route in Core and also map to "admin" design
                   for legacy bundle<<<Alt. welcome>>>
- EditorialBundle: Connected to Site, so provides access to all content languages
                   But interface language is selected by language match.
- DemoBundle :     Connected to Languages*Designs, content language is defined by these settings.
- LegacyBundle:    Connected to Languages*Designs, maps to legacy SiteAccess in form:
                   <Site>_<Design>_<Language>



Configuration
-------------








Model
-----
