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

/**
 * Describe a Media instance
 *
 * most of the time medias are defined as enclosure in the XML document
 *
 * Atom :
 *     <link rel="enclosure" href="http://example.org/video.mpeg" type="video/mpeg" />
 *
 * RSS :
 *     <enclosure url="http://example.org/video.mpeg" length="12216320" type="video/mpeg" />
 *
 * <code>
 *     // will display http://example.org/video.mpeg
 *     echo $media->getUrl();
 * </code>
 */
interface MediaInterface
{
    /**
     * @return string
     */
    public function getNodeName() : string;

    /**
     * @param  string $nodeName
     * @return MediaInterface
     */
    public function setNodeName(string $nodeName) : MediaInterface;

    /**
     * @return string
     */
    public function getType() : ? string;

    /**
     * @param  string $type
     * @return MediaInterface
     */
    public function setType(?string $type) : MediaInterface;

    /**
     * @return string
     */
    public function getUrl() : ? string;

    /**
     * @param  string $url
     * @return MediaInterface
     */
    public function setUrl(?string $url) : MediaInterface;

    /**
     * @return string
     */
    public function getLength() : ? string;

    /**
     * @param  string $length
     * @return MediaInterface
     */
    public function setLength(?string $length) : MediaInterface;

    /**
     * @return string
     */
    public function getTitle() : ? string;

    /**
     * @param  string $title
     * @return MediaInterface
     */
    public function setTitle(?string $title) : MediaInterface;

    /**
     * @return string
     */
    public function getDescription() : ? string;

    /**
     * @param  string $description
     * @return MediaInterface
     */
    public function setDescription(?string $description) : MediaInterface;

    /**
     * @return int
     */
    public function getRights() : ?int;

    /**
     * @param  int $rights
     * @return MediaInterface
     */
    public function setRights(int $rights) : MediaInterface;



    /**
     * @return int
     */
    public function getTitleType() : ?int;

    /**
     * @param  string $titleType
     * @return MediaInterface
     */
    public function setTitleType(?int $titleType) : MediaInterface;


    /**
     * @return int
     */
    public function getDescriptionType() : ?int;

    /**
     * @param  string $descriptionType
     * @return MediaInterface
     */
    public function setDescriptionType(?int $descriptionType) : MediaInterface;

    /**
     * @return array
     */
    public function getKeywords() : array;

    /**
     * @param  string $keywords
     * @return MediaInterface
     */
    public function setKeywords(array $keywords) : MediaInterface;

    /**
     * @return array
     */
    public function getComments() : array;

    /**
     * @param  array $comments
     * @return MediaInterface
     */
    public function setComments(array $comments) : MediaInterface;

    /**
     * @return array
     */
    public function getResponses() : array;

    /**
     * @param  string $responses
     * @return MediaInterface
     */
    public function setResponses(array $responses) : MediaInterface;

    /**
     * @return array
     */
    public function getBacklinks() : array;

    /**
     * @param  string $backlinks
     * @return MediaInterface
     */
    public function setBacklinks(array $backlinks) : MediaInterface;

    /**
     * @return array
     */
    public function getCredits() : array;

    /**
     * @param  string $credits
     * @return MediaInterface
     */
    public function setCredits(array $credits) : MediaInterface;

    /**
     * @return array
     */
    public function getTexts() : array;

    /**
     * @param  string $texts
     * @return MediaInterface
     */
    public function setTexts(array $texts) : MediaInterface;

    /**
     * @return array
     */
    public function getPrices() : array;

    /**
     * @param  string $prices
     * @return MediaInterface
     */
    public function setPrices(array $prices) : MediaInterface;


    /**
     * @return array
     */
    public function getSubTitles() : array;

    /**
     * @param  string $subTitles
     * @return MediaInterface
     */
    public function setSubTitles(array $subTitles) : MediaInterface;


    /**
     * @return array
     */
    public function getScenes() : array;

    /**
     * @param  string $scenes
     * @return MediaInterface
     */
    public function setScenes(array $scenes) : MediaInterface;

    /**
     * @return MediaContentInterface
     */
    public function getContent() : MediaContentInterface;

    /**
     * @param  MediaContentInterface $content
     * @return MediaInterface
     */
    public function setContent(MediaContentInterface $content) : MediaInterface;

    /**
     * @return MediaThumbnailInterface
     */
    public function getThumbnail() : MediaThumbnailInterface;

    /**
     * @param  MediaThumbnailInterface $content
     * @return MediaInterface
     */
    public function setThumbnail(MediaThumbnailInterface $content) : MediaInterface;

    /**
     * @return string
     */
    public function getCategory() : MediaCategoryInterface;

    /**
     * @param  string $category
     * @return MediaInterface
     */
    public function setCategory(MediaCategoryInterface $category) : MediaInterface;

    /**
     * @return string
     */
    public function getPlayer() : MediaPlayerInterface;

    /**
     * @param  string $player
     * @return MediaInterface
     */
    public function setPlayer(MediaPlayerInterface $player) : MediaInterface;

    /**
     * @return string
     */
    public function getHash() : MediaHashInterface;

    /**
     * @param  string $hash
     * @return MediaInterface
     */
    public function setHash(MediaHashInterface $hash) : MediaInterface;

    /**
     * @return string
     */
    public function getEmbed() : MediaEmbedInterface;

    /**
     * @param  string $embed
     * @return MediaInterface
     */
    public function setEmbed(MediaEmbedInterface $embed) : MediaInterface;

    /**
     * @return string
     */
    public function getLicense() : MediaLicenseInterface;

    /**
     * @param  string $license
     * @return MediaInterface
     */
    public function setLicense(MediaLicenseInterface $license) : MediaInterface;

    /**
     * @return string
     */
    public function getCommunity() : MediaCommunityInterface;

    /**
     * @param  string $community
     * @return MediaInterface
     */
    public function setCommunity(MediaCommunityInterface $community) : MediaInterface;

    /**
     * @return string
     */
    public function getRestriction() : MediaRestrictionInterface;

    /**
     * @param  string $restriction
     * @return MediaInterface
     */
    public function setRestriction(MediaRestrictionInterface $restriction) : MediaInterface;

    /**
     * @return string
     */
    public function getRating() : MediaRatingInterface;

    /**
     * @param  string $rating
     * @return MediaInterface
     */
    public function setRating(MediaRatingInterface $rating) : MediaInterface;

    /**
     * @return string
     */
    public function getCopyright() : MediaCopyrightInterface;

    /**
     * @param  string $copyright
     * @return MediaInterface
     */
    public function setCopyright(MediaCopyrightInterface $copyright) : MediaInterface;

    /**
     * @return string
     */
    public function getStatus() : MediaStatusInterface;

    /**
     * @param  string $status
     * @return MediaInterface
     */
    public function setStatus(MediaStatusInterface $status) : MediaInterface;

    /**
     * @return string
     */
    public function getPeerLink() : MediaPeerLinkInterface;

    /**
     * @param  string $peerLink
     * @return MediaInterface
     */
    public function setPeerLink(MediaPeerLinkInterface $peerLink) : MediaInterface;
}
