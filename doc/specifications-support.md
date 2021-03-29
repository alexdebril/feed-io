# synopsis

This document explains which attributes are supported by feed-io and how to access them

## top level document : feed (atom) / channel (rss) / top-level (json)

interface : FeedInterface

| atom            | rss                     | json          | getter          | setter          |
| --------------- | ----------------------- | ------------- | --------------- | --------------- |
| title           | title                   | title         | getTitle        | setTitle        |
| link            | link                    | home_page_url | getLink         | setLink         |
| link (rel=self) | N/A                     | feed_url      | getLink         | setLink         |
| updated         | pubDate / lastBuildDate | N/A           | getLastModified | setLastModified |
| id              | N/A                     | N/A           | getPublicId     | setPublicId     |
| description     | description             | description   | getDescription  | setDescription  |
| category        | category                | N/A           | getCategories   | addCategory     |
| author          | author                  | author(s)     | getAuthor       | setAuthor       |
| contributor     | N/A                     | N/A           | not supported   | not supported   |
| logo            | image                   | icon          | getLogo         | setLogo         |
| rights          | copyright               | N/A           | not supported   | not supported   |
| subtitle        | N/A                     | N/A           | not supported   | not supported   |
| lang            | language                | N/A           | getLanguage     | setLanguage     |
| base            | N/A                     | N/A           | not supported   | not supported   |
| generator       | generator               | N/A           | not supported   | not supported   |
| N/A             | managingEditor          | N/A           | not supported   | not supported   |
| N/A             | webMaster               | N/A           | not supported   | not supported   |
| N/A             | docs                    | N/A           | not supported   | not supported   |
| N/A             | cloud                   | hubs          | not supported   | not supported   |
| N/A             | ttl                     | N/A           | not supported   | not supported   |
| N/A             | rating                  | N/A           | not supported   | not supported   |
| N/A             | textInput               | N/A           | not supported   | not supported   |
| N/A             | skipdays                | N/A           | not supported   | not supported   |
| N/A             | skipHours               | N/A           | not supported   | not supported   |
| N/A             | N/A                     | expired       | not supported   | not supported   |

## entry (atom) / item (rss) / item (json)

Interface : ItemInterface

| atom                | rss         | json                        | getter          | setter          |
| ------------------- | ----------- | --------------------------- | --------------- | --------------- |
| title               | title       | title                       | getTitle        | setTitle        |
| link                | link        | url                         | getLink         | setLink         |
| link                | enclosure   | image (get only)            | getMedias       | addMedia        |
| updated / published | pubDate     | date_published              | getLastModified | setLastModified |
| id                  | guid        | id                          | getPublicId     | setPublicId     |
| content             | description | content_html / content_text | getContent      | setContent      |
| summary             | N/A         | summary                     | getSummary      | setSummary      |
| source              | source      | N/A                         | not supported   | not supported   |
| category            | category    | tags (wip)                  | getCategories   | addCategory     |
| author              | N/A         | author                      | getAuthor       | setAuthor       |
| contributor         | N/A         | N/A                         | not supported   | not supported   |
| N/A                 | comments    | N/A                         | not supported   | not supported   |
| rights              | N/A         | N/A                         | not supported   | not supported   |
| N/A                 | N/A         | external_url                | not supported   | not supported   |
| N/A                 | N/A         | banner_image                | not supported   | not supported   |
| N/A                 | N/A         | attachments                 | not supported   | not supported   |
| N/A                 | N/A         | date_modified               | not supported   | not supported   |

