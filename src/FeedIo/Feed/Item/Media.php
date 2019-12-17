<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

use FeedIo\Feed\ArrayableInterface;

abstract class MediaConstant
{
    /**
     * @param string|null $value
     */
    public static function fromXML(?string $value) : ?int
    {
        return static::VALUES[$value ? strtolower($value) : $value] ?? null;
    }
}

abstract class MediaDescriptionType extends MediaConstant
{
    const Plain = 1;
    const HTML = 2;

    const VALUES = array(
        null => MediaDescriptionType::Plain,
        "plain" => MediaDescriptionType::Plain,
        "html" => MediaDescriptionType::HTML,
    );
}

abstract class MediaTitleType extends MediaConstant
{
    const Plain = 1;
    const HTML = 2;

    const VALUES = array(
        null => MediaTitleType::Plain,
        "plain" => MediaTitleType::Plain,
        "html" => MediaTitleType::HTML,
    );
}


class Media implements MediaInterface, ArrayableInterface
{
    /**
     * @var string
     */
    protected $nodeName;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $length;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $rights;

    /**
     * @var int
     */
    protected $titleType;

    /**
     * @var int
     */
    protected $descriptionType;

    /**
     * @var array
     */
    protected $keywords = array();

    /**
     * @var array
     */
    protected $comments = array();

    /**
     * @var array
     */
    protected $responses = array();

    /**
     * @var array
     */
    protected $backlinks = array();

    /**
     * @var array
     */
    protected $credits = array();

    /**
     * @var array
     */
    protected $texts = array();

    /**
     * @var array
     */
    protected $prices = array();

    /**
     * @var array
     */
    protected $subTitles = array();

    /**
     * @var array
     */
    protected $scenes = array();

    /**
     * @var MediaContentInterface
     */
    protected $content;

    /**
     * @var MediaThumbnailInterface
     */
    protected $thumbnail;

    /**
     * @var MediaCategoryInterface
     */
    protected $category;

    /**
     * @var MediaHashInterface
     */
    protected $hash;

    /**
     * @var MediaEmbedInterface
     */
    protected $embed;

    /**
     * @var MediaLicenseInterface
     */
    protected $license;

    /**
     * @var MediaCommunityInterface
     */
    protected $community;

    /**
     * @var MediaRestrictionInterface
     */
    protected $restriction;

    /**
     * @var MediaRatingInterface
     */
    protected $rating;

    /**
     * @var MediaCopyrightInterface
     */
    protected $copyright;

    /**
     * @var MediaPlayerInterface
     */
    protected $player;

    /**
     * @var MediaStatusInterface
     */
    protected $status;

    /**
     * @var MediaPeerLinkInterface
     */
    protected $peerLink;

    /**
     * @return string
     */
    public function getNodeName() : string
    {
        return $this->nodeName;
    }

    /**
     * @param string $nodeName
     * @return MediaInterface
     */
    public function setNodeName(string $nodeName) : MediaInterface
    {
        $this->nodeName = $nodeName;

        return $this;
    }

    /**
     * @return string
     */
    public function getType() : ? string
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return MediaInterface
     */
    public function setType(?string $type) : MediaInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() : ? string
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return MediaInterface
     */
    public function setUrl(?string $url) : MediaInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getLength() : ? string
    {
        return $this->length;
    }

    /**
     * @param  string $length
     * @return MediaInterface
     */
    public function setLength(?string $length) : MediaInterface
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() : ? string
    {
        return $this->title;
    }

    /**
     * @param  string $title
     * @return MediaInterface
     */
    public function setTitle(?string $title) : MediaInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() : ? string
    {
        return $this->description;
    }

    /**
     * @param  string $description
     * @return MediaInterface
     */
    public function setDescription(?string $description) : MediaInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getRights() : ?int
    {
        return $this->rights;
    }

    /**
     * @param  int $rights
     * @return MediaInterface
     */
    public function setRights(int $rights) : MediaInterface
    {
        $this->rights = $rights;

        return $this;
    }

    /**
     * @return int
     */
    public function getTitleType() : ?int
    {
        return $this->titleType;
    }

    /**
     * @param  int $titleType
     * @return MediaInterface
     */
    public function setTitleType(?int $titleType) : MediaInterface
    {
        $this->titleType = $titleType;

        return $this;
    }


    /**
     * @return int
     */
    public function getDescriptionType() : ?int
    {
        return $this->descriptionType;
    }

    /**
     * @param  int $descriptionType
     * @return MediaInterface
     */
    public function setDescriptionType(?int $descriptionType) : MediaInterface
    {
        $this->descriptionType = $descriptionType;

        return $this;
    }

    /**
     * @return array
     */
    public function getKeywords() : array
    {
        return $this->keywords;
    }

    /**
     * @param  array $keywords
     * @return MediaInterface
     */
    public function setKeywords(array $keywords) : MediaInterface
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return array
     */
    public function getComments() : array
    {
        return $this->comments;
    }

    /**
     * @param  array $comments
     * @return MediaInterface
     */
    public function setComments(array $comments) : MediaInterface
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return array
     */
    public function getResponses() : array
    {
        return $this->responses;
    }

    /**
     * @param  array $responses
     * @return MediaInterface
     */
    public function setResponses(array $responses) : MediaInterface
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * @return array
     */
    public function getBacklinks() : array
    {
        return $this->backlinks;
    }

    /**
     * @param  array $backlinks
     * @return MediaInterface
     */
    public function setBacklinks(array $backlinks) : MediaInterface
    {
        $this->backlinks = $backlinks;

        return $this;
    }

    /**
     * @return array
     */
    public function getCredits() : array
    {
        return $this->credits;
    }

    /**
     * @param  array $credits
     * @return MediaInterface
     */
    public function setCredits(array $credits) : MediaInterface
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * @return array
     */
    public function getTexts() : array
    {
        return $this->texts;
    }

    /**
     * @param  array $texts
     * @return MediaInterface
     */
    public function setTexts(array $texts) : MediaInterface
    {
        $this->texts = $texts;

        return $this;
    }

    /**
     * @return array
     */
    public function getPrices() : array
    {
        return $this->prices;
    }

    /**
     * @param  array $prices
     * @return MediaInterface
     */
    public function setPrices(array $prices) : MediaInterface
    {
        $this->prices = $prices;

        return $this;
    }

    /**
     * @return array
     */
    public function getSubTitles() : array
    {
        return $this->subTitles;
    }

    /**
     * @param  array $subTitles
     * @return MediaInterface
     */
    public function setSubTitles(array $subTitles) : MediaInterface
    {
        $this->subTitles = $subTitles;

        return $this;
    }

    /**
     * @return array
     */
    public function getScenes() : array
    {
        return $this->scenes;
    }

    /**
     * @param  array $scenes
     * @return MediaInterface
     */
    public function setScenes(array $scenes) : MediaInterface
    {
        $this->scenes = $scenes;

        return $this;
    }

    /**
     * @return MediaContentInterface
     */
    public function getContent() : MediaContentInterface
    {
        return $this->content;
    }

    /**
     * @param  MediaContentInterface $content
     * @return MediaInterface
     */
    public function setContent(MediaContentInterface $content) : MediaInterface
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return MediaThumbnailInterface
     */
    public function getThumbnail() : MediaThumbnailInterface
    {
        return $this->thumbnail;
    }

    /**
     * @param  MediaThumbnailInterface $thumbnail
     * @return MediaInterface
     */
    public function setThumbnail(MediaThumbnailInterface $thumbnail) : MediaInterface
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }

    /**
     * @return MediaCategoryInterface
     */
    public function getCategory() : MediaCategoryInterface
    {
        return $this->category;
    }

    /**
     * @param  MediaCategoryInterface $category
     * @return MediaInterface
     */
    public function setCategory(MediaCategoryInterface $category) : MediaInterface
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return MediaPlayerInterface
     */
    public function getPlayer() : MediaPlayerInterface
    {
        return $this->player;
    }

    /**
     * @param  MediaPlayerInterface $player
     * @return MediaInterface
     */
    public function setPlayer(MediaPlayerInterface $player) : MediaInterface
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @return MediaHashInterface
     */
    public function getHash() : MediaHashInterface
    {
        return $this->hash;
    }

    /**
     * @param  MediaHashInterface $hash
     * @return MediaInterface
     */
    public function setHash(MediaHashInterface $hash) : MediaInterface
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return MediaEmbedInterface
     */
    public function getEmbed() : MediaEmbedInterface
    {
        return $this->embed;
    }

    /**
     * @param  MediaEmbedInterface $embed
     * @return MediaInterface
     */
    public function setEmbed(MediaEmbedInterface $embed) : MediaInterface
    {
        $this->embed = $embed;

        return $this;
    }

    /**
     * @return MediaLicenseInterface
     */
    public function getLicense() : MediaLicenseInterface
    {
        return $this->license;
    }

    /**
     * @param  MediaLicenseInterface $license
     * @return MediaInterface
     */
    public function setLicense(MediaLicenseInterface $license) : MediaInterface
    {
        $this->license = $license;

        return $this;
    }

    /**
     * @return MediaCommunityInterface
     */
    public function getCommunity() : MediaCommunityInterface
    {
        return $this->community;
    }

    /**
     * @param  MediaCommunityInterface $community
     * @return MediaInterface
     */
    public function setCommunity(MediaCommunityInterface $community) : MediaInterface
    {
        $this->community = $community;

        return $this;
    }

    /**
     * @return MediaRestrictionInterface
     */
    public function getRestriction() : MediaRestrictionInterface
    {
        return $this->restriction;
    }

    /**
     * @param  MediaRestrictionInterface $restriction
     * @return MediaInterface
     */
    public function setRestriction(MediaRestrictionInterface $restriction) : MediaInterface
    {
        $this->restriction = $restriction;

        return $this;
    }

    /**
     * @return MediaRatingInterface
     */
    public function getRating() : MediaRatingInterface
    {
        return $this->rating;
    }

    /**
     * @param  MediaRatingInterface $rating
     * @return MediaInterface
     */
    public function setRating(MediaRatingInterface $rating) : MediaInterface
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return MediaCopyrightInterface
     */
    public function getCopyright() : MediaCopyrightInterface
    {
        return $this->copyright;
    }

    /**
     * @param  MediaCopyrightInterface $copyright
     * @return MediaInterface
     */
    public function setCopyright(MediaCopyrightInterface $copyright) : MediaInterface
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * @return MediaStatusInterface
     */
    public function getStatus() : MediaStatusInterface
    {
        return $this->status;
    }

    /**
     * @param  MediaStatusInterface $status
     * @return MediaInterface
     */
    public function setStatus(MediaStatusInterface $status) : MediaInterface
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return MediaPeerLinkInterface
     */
    public function getPeerLink() : MediaPeerLinkInterface
    {
        return $this->peerLink;
    }

    /**
     * @param  MediaPeerLinkInterface $peerLink
     * @return MediaInterface
     */
    public function setPeerLink(MediaPeerLinkInterface $peerLink) : MediaInterface
    {
        $this->peerLink = $peerLink;

        return $this;
    }
}
