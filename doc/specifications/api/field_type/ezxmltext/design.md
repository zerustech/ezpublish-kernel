eZ Publish: Design for a new internal Rich Text format
======================================================

Introduction
------------

The general architecture would enable input and output of rich content in
several formats thanks to converters.

The internal rich text format should therefor not be influenced by capabilities
of a WYSIWYG web editors, nor by the legacy eZ XML dialect.

The [Document](https://github.com/zetacomponents/Document/) component of the
[Zeta Components](https://github.com/zetacomponents) contains several
requirements and design documents that shares very similar goals than the ones
we try to achieve. It leads to the conclusion that using a subset of Docbook is
what covers the most semantic markup structures of the used formats and is easy
to process and extend. The document available at:
https://github.com/zetacomponents/Document/blob/master/design/ezp_markup.txt
is the best resource that summarize this design decision.

Remarks
-------

* Accommodating WYSIWYG editors to be able to produce a custom XML format has
  been proved to be very inefficient with eZOE. For this reason, standard
  (X)HTML5 would be produced with the fewest possible changes. There is however
  a need to be able to extend this format, whether it is to support customer
  feature or one that could be possible in the internal format, e.g.: footnotes.
  Some generated output like PDF could highly benefit from that kind of feature.
* In the case the internal format implements the same amount of *feature* than
  (X)HTML5 and not more, we can have some sort of direct matching between the
  two formats, e.g.:

    <paragraph>A paragraph</paragraph> <---> <p>A paragraph</p>

  However in the case of more advanced concept not directly existing in
  (X)HTML5 (e.g.: footnote), we might have to work with an (X)HTML5 conversion
  that differs whether this is for rendering or editing purpose. Example:

    <paragraph>
    First footnote follows<footnote>Footnote #1 content</footnote>, second
    footnote follows<footnote>Footnote #2 content</footnote>.
    </paragraph>

  Can be rendered as:

    <p>
    First footnote follows<a id="footnote-1-ref" href="#footnote-1">[1]</a>,
    second footnote follows<a id="footnote-2-ref" href="#footnote-2">[2]</a>.
    <p>
    <!-- ... some more content on this page -->
    <p id="footnote-1">
    1. Footnote #1 content. <a href="#footnote-1-ref">&#8617</a>
    </p>
    <p id="footnote-2">
    2. Footnote #2 content. <a href="#footnote-2-ref">&#8617</a>
    </p>

  However, a WYSIWYG editor aware of footnotes would have some difficulties to
  understand such a structure since it looses the original semantic meaning.
  An alternative (X)HTML5 would probably help but it **SHOULD**/**MUST** still
  be (X)HTML5 valid.
  A purely fictitious example:

    <p>
    First footnote follows<span class="custom footnote">Footnote #1 content</span>, second
    footnote follows<span class="custom footnote">Footnote #2 content</span>.
    </p>

Architecture
------------

### Proposed solution:

                                 +---------------------------+
                                 | Legacy Persistent Handler |
                                 |---------------------------|
                                 |                           |
                                 | +-------+    +----------+ |
    +---------------------+      | |       |    |          | |
    |                     |      | | eZXML +<-->+ Database | |
    | WYSIWYG Web Editors |      | |       |    |          | |
    |                     |      | +---+---+    +----------+ |
    +----------+----------+      |     ^                     |
               ^                 +-----|---------------------+
               |                       |                   +------------------+
               |                       |                   | NoSQL Persistent |
               |                       |                   |      Handler     |
               v                       v                   |------------------|
          +----------+             +----------+------+     |   +----------+   |
          |          |             |                 |     |   |          |   |
          | (X)HTML5 +<----------->+ Internal Format +<------->+ Database |   |
          |          |             |                 |     |   |          |   |
          +----+-----+             +---+-----+----+--+     |   +----------+   |
               |          +-----+      |     |    |        +------------------+
               |          |     |      |     |    |
               |  +-------+ PDF |<-----+     v    +-----------+
               |  |       |     |   +-------------------+     |
               v  v       +-----+   |                   |     |
         +----------+               | OpenDocument Text |     v
         |          |<--------------+                   | +---------+
         | End User |               +-------------------+ |         |
         |          |<------------------------------------+ OpenXML |
         +----------+                                     |         |
                                                          +---------+


### Implementation

The Document component of the Zeta Components seems like a perfect match to
handle both the conversion between formats as well as to provide an internal
format that would be it's own internal format being: [Docbook] (http://www.docbook.org/).

Since it has been a few years that this component has not been developed
further, the supported syntax of eZ XML is probably a tiny bit outdated, in
particular because of the following changes:
* https://github.com/ezsystems/ezpublish/commit/bfb2398d593f44618eada6e379faa1d3db0c65d3
* https://github.com/ezsystems/ezpublish/commit/e2956d1be8da6524f061155c6bdb1ebb38b75c92
* https://github.com/ezsystems/ezpublish/commit/a5600fc799db4e2b2cf687991a39e3d694746a64
