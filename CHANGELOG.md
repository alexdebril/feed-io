CHANGELOG
=========

0.8.0 (2015-04-25)
-------------------

* RuleSet's default rule can be set
* Atom and RSS enclosures support

0.7.0 (2015-04-21)
-------------------

* RSS/RDF Format support

0.6.0 (2015-04-15)
-------------------

* OptionalField is replaced by Element
* item supports multiple elements of same names
* Element supports multiple attributes
* Fixer system to repair feeds after parsing -(like missing date)

0.5.2 (2015-04-01)
-------------------

* alias system to fetch same data from different nodes (like lastBuildDate and lastPubDate in RSS)
* lastBuildDate is now an alias of lastPubDate in RSS standard

0.5.1 (2015-03-23)
-------------------

* atom specifications : "content" node instead of "description"

0.5.0 (2015-02-23)
------------------

* FeedIo main class

0.4.0 (2015-01-25)
------------------

* Guzzle Client

0.3.1 (2015-01-18)
------------------

* updated the Changelog

0.3.0 (2014-12-13)
------------------

* Removed ParserAbstract, Parser/Rss Parser/Atom
* Added Parser class. This class parses a Feed using a Standard
* Added StandardAbstract
* Added Standard/Atom
* Added Standard/Rss
* Added Formatter class. This class formats a Feed using a Standard 

0.2.0 (2014-11-23)
------------------

* added ClientInterface to handle HTTP queries
* added ParserAbstract
* added Parser/Rss
* added Parser/atom
* Feed now extends Item

0.1.0 (2014-09-06)
------------------

* added FeedInterface to represent a Feed which is the whole RSS/Atom document
* added NodeInterface to represent a Node which contains basic attributes of the whole document or an item
* added ItemInterface to represent an Item
* project structure
