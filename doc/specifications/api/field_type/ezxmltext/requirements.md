eZ Publish: Requirements for a new internal Rich Text format
============================================================

Introduction
------------

This document summarizes the requirements for a new internal rich text format
that will be used in eZ Publish 5 to represent rich content within its
corresponding FieldType.

Current status
--------------

The XML Block datatype is currently used to store rich content and its format is
[documented](http://doc.ez.no/eZ-Publish/Technical-manual/4.x/Reference/XML-tags).

eZ Systems previously created the eZ Components, now known as Zeta Components,
and contains [requirements/design documents](https://github.com/zetacomponents/Document/tree/master/design)
as well as working code under the [Document component](https://github.com/zetacomponents/Document).

This component has been developped in the mind of being at the heart of a
refactoring of eZ Publish 3/4 and as a result of that, contains many relevant
information and should therefor be considered as a very good candidate for
handling rich text.

Requirements
------------

 * The internal format **MUST** be capabale of storing any kind of content
   that can be represented with (X)HTML5 since the primary output target of
   eZ Publish is the Web.
   The following content elements **MUST** be supported:
   * title hierarchies;
   * text with the following notions:
     * paragraphs,
     * strong,
     * emphasized,
     * preformatted,
   * hyperlinks;
   * anchors;
   * sections;
   * tables;
   * embedded media:
     * image,
     * flash,
     * video,
     * audio,
     * Java applets,
     * ...;
     With a possibility to attach a caption;
   * ordered and unordered lists;
   * line breaks;
   * (horizontal) rules;
   The following content elements **SHOULD** be supported:
   * text with the following notions:
     * inserted,
     * deleted;
     * quotations;
     * definitions;
     * keyboard input;
     * addresses,
     * acronyms,
     * details;
   * definition lists;
   * page breaks;
 * The nesting of elements that is possible in (X)HTML5 **MUST** be supported.
 * It **MUST** be capable of representing any Unicode characters;
 * An XML format **MIGHT** be preferred although not strictly required. In any
   cases, the selected format **MUST** be:
   * easy to parse in PHP without any specific requirement;
   * fast to parse in PHP without any specific requirement;
   * easy to validate in PHP without any specific requirement.
   Those requirements makes XML formats probably the
 * It **MUST** be extendable by eZ developers as well as by eZ Publish 5
   customers that need to add their own notions.
 * Internal content relation **MUST** be possible.
 * More advanced concept **SHOULD** be possible as well:
   * footnotes;
   * bibliography references;
   * math formulas (MathML ?);
   * storing semantic data (RDF ?);
   * [Ruby text]
 * Conversion to and from other formats **MUST** be possible:
  * any kind of XML structure, but in particular:
    * (X)HTML5,
    * HTML4 / XHTML 1.x,
    * eZ XML;
  * Open Document Text;
  * MS OpenXML;
  * ReStructured text;
  * various Wiki Markup;
  * PDF (only converting to);

    [Ruby text]: http://www.w3.org/TR/ruby/
