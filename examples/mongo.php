<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/07/15
 * Time: 13:35
 */

require __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';

$mongo = new MongoClient();

$db = $mongo->selectDB('feedioTest');

$collection = $db->selectCollection('feeds');

class MongoFeed extends \FeedIo\Feed
{
    public $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}

$feed = new MongoFeed();
$feed->setTitle('title test');
$feed->setId(24);
$collection->save($feed);
