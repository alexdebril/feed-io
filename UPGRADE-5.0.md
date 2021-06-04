# UPGRADE FROM 4.x to 5.0

Several major changes in version 5.0:
 - Full migration to PHP 8.0
 - `readSince()` has been removed alongside with the `FilterInterface`
 - `readAsync()` has been removed
 - `description` is an attribute of the **feed**, whereas the **item** contains `summary` and `content`

## readSince() removal

Despite how convenient `readSince()` can be, it doesn't work in every case. It's because some feeds doesn't include the publication date for each item, making impossible to filter the outdated ones and keep only the fresh material. As a consequence, the client application is forced to check the item's existence in its database exactly like it would do without the date filtering. That makes `readSince()` pointless, so it's better to remove it.

The `FilterInterface` is also removed as it's not used for any other matter.

## readAsync() removal

It's a complicated piece of code, complex to use and that could be easily replaced with another architecture to perform concurrent reads. It's not worth keeping it, so the best choice is to stick with the most used reading method.

## `description` / `content` naming change

get/setDescription is now at the Feed's level, items expose get/setSummary and get/setContent.

## No more complaining about malformed date

When a feed-io is malformed, feed-io won't throw an exception anymore. It will return current date instead
