# synopsis

This document explains which attributes are supported by feed-io and how to access them

## top level document : feed (atom) / channel (rss)

interface : FeedInterface

| atom | rss | getter | setter 
| title | title | getTitle | setTitle 
| link | link | getLink | setLink
| updated | pubDate / lastBuildDate | getLastModified | setLastModified
| id | N/A | getPublicId | setPublicId
| N/A | description | getDescription | setDescription
| category | category | not supported | not supported
| author | N/A | not supported | not supported
| contributor | N/A | not supported | not supported
| icon / logo | image | not supported | not supported
| rights | copyright | not supported | not supported
| subtitle | N/A  | not supported | not supported
| lang | language | not supported | not supported
| base | N/A | not supported | not supported
| generator | generator | not supported | not supported
| N/A | managingEditor  | not supported | not supported
| N/A |   webMaster | not supported | not supported
| N/A |  docs | not supported | not supported
| N/A |  cloud | not supported | not supported
| N/A |  ttl | not supported | not supported
| N/A |  rating | not supported | not supported
| N/A | textInput  | not supported | not supported
| N/A | skipdays | not supported | not supported
| N/A | skipHours | not supported | not supported

## entry (atom) / item (rss)

Interface : ItemInterface

| atom | rss | getter | setter 
| title | title | getTitle | setTitle 
| link | link | getLink | setLink
| link | enclosure | getMedias | addMedia
| updated / published | pubDate | getLastModified | setLastModified
| id | guid | getPublicId | setPublicId
| content | description | getDescription | setDescription
| summary | N/A | not supported | not supported
| source | source | not supported | not supported
| category | category | not supported | not supported
| author | N/A | not supported | not supported
| contributor | N/A | not supported | not supported
| N/A | comments | not supported | not supported
| rights | N/A | not supported | not supported
