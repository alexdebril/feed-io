# UPGRADE FROM 3.x to 4.0

The major change in version 4.0 is the full migration to PHP 7.1. It has an impact on interface implementations.

## Types are explicit

From now on, all types are explicits in method signatures. It has an impact on classes that implements :

 - \FeedIo\FeedInterface 
 - \FeedIo\Feed\ItemInterface
 - \FeedIo\Feed\NodeInterface
 - \FeedIo\Feed\ElementsAwareInterface
 - \FeedIo\Feed\Item\AuthorInterface
 - \FeedIo\Feed\Item\MediaInterface
 - \FeedIo\Feed\Node\CategoryInterface
 - \FeedIo\Feed\Node\ElementInterface

For instance, `FeedIo\FeedInterface::setUrl($url)` becomes :

```php
    /**
     * @param string $url
     * @return FeedInterface
     */
    public function setUrl(string $url) : FeedInterface;
```
As a consequence, you need to adapt any class that implements `FeedIo\FeedInterface` according to the new signature : 

```php
    /**
     * @param string $url
     * @return FeedInterface
     */
    public function setUrl($url)
    {
        $this->url = $url;
        
        return $this;
    }
```
becomes : 

```php
    /**
     * @param string $url
     * @return FeedInterface
     */
    public function setUrl(string $url) : FeedInterface
    {
        $this->url = $url;
        
        return $this;
    } 
```
You should refer to the new interfaces declaration to get the full list of concerned functions.
