<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\Item\MediaCategory;
use FeedIo\Feed\Item\MediaCommunity;
use FeedIo\Feed\Item\MediaContent;
use FeedIo\Feed\Item\MediaContentMedium;
use FeedIo\Feed\Item\MediaContentExpression;
use FeedIo\Feed\Item\MediaCopyright;
use FeedIo\Feed\Item\MediaDescriptionType;
use FeedIo\Feed\Item\MediaEmbed;
use FeedIo\Feed\Item\MediaTitleType;
use FeedIo\Feed\Item\MediaHash;
use FeedIo\Feed\Item\MediaHashAlgo;
use FeedIo\Feed\Item\MediaLicense;
use FeedIo\Feed\Item\MediaCreditScheme;
use FeedIo\Feed\Item\MediaPeerLink;
use FeedIo\Feed\Item\MediaPriceType;
use FeedIo\Feed\Item\MediaPlayer;
use FeedIo\Feed\Item\MediaTextType;
use FeedIo\Feed\Item\MediaRating;
use FeedIo\Feed\Item\MediaRestriction;
use FeedIo\Feed\Item\MediaRestrictionRelationship;
use FeedIo\Feed\Item\MediaRestrictionType;
use FeedIo\Feed\Item\MediaRightsStatus;
use FeedIo\Feed\Item\MediaStatus;
use FeedIo\Feed\Item\MediaStatusValue;
use FeedIo\Feed\Item\MediaCredit;
use FeedIo\Feed\Item\MediaPrice;
use FeedIo\Feed\Item\MediaSubtitle;
use FeedIo\Feed\Item\MediaText;
use FeedIo\Feed\Item\MediaThumbnail;
use FeedIo\Feed\Item\MediaScene;
use FeedIo\Feed\NodeInterface;
use FeedIo\Parser\UrlGenerator;
use FeedIo\RuleAbstract;

class Media extends RuleAbstract
{
    const NODE_NAME = 'enclosure';

    const MRSS_NAMESPACE = "http://search.yahoo.com/mrss/";

    protected $urlAttributeName = 'url';

    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    public function __construct(string $nodeName = null)
    {
        $this->urlGenerator = new UrlGenerator();
        parent::__construct($nodeName);
    }

    /**
     * @return string
     */
    public function getUrlAttributeName() : string
    {
        return $this->urlAttributeName;
    }

    /**
     * @param  string $name
     */
    public function setUrlAttributeName(string $name) : void
    {
        $this->urlAttributeName = $name;
    }

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
        if ($node instanceof ItemInterface) {
            switch ($element->nodeName) {
                case 'media:content':
                    $this->handleMediaRoot($node, $element);
                    break;

                case 'media:group':
                    $this->handleMediaGroup($node, $element);
                    break;

                default:
                    $media = $node->newMedia();
                    $media->setNodeName($element->nodeName);
                    $media
                        ->setType($this->getAttributeValue($element, 'type'))
                        ->setLength($this->getAttributeValue($element, 'length'));
                    $this->setUrl($media, $node, $this->getAttributeValue($element, $this->getUrlAttributeName()));
                    $node->addMedia($media);
                    break;
            }
        }
    }

    /**
     * @param MediaInterface $media
     * @param NodeInterface $node
     * @param string|null $url
     */
    protected function setUrl(MediaInterface $media, NodeInterface $node, string $url = null): void
    {
        if (! is_null($url)) {
            $media->setUrl(
                $this->urlGenerator->getAbsolutePath($url, $node->getHost())
            );
        }
    }

    /**
     * @param  \DomDocument   $document
     * @param  MediaInterface $media
     * @return \DomElement
     */
    public function createMediaElement(\DomDocument $document, MediaInterface $media) : \DOMElement
    {
        $element = $document->createElement($this->getNodeName());
        $element->setAttribute($this->getUrlAttributeName(), $media->getUrl());
        $element->setAttribute('type', $media->getType() ?? '');
        $element->setAttribute('length', $media->getLength() ?? '');

        return $element;
    }

    /**
     * @param NodeInterface $node
     * @param \DomElement $element
     * @return void
     */
    protected function handleMediaGroup(NodeInterface $node, \DOMElement $element): void
    {
        foreach ($element->childNodes as $mediaContentNode) {
            if (is_a($mediaContentNode, "DOMNode") && $mediaContentNode->nodeName == "media:content") {
                $this->handleMediaRoot($node, $mediaContentNode);
            }
        }
    }

    /**
     * @param NodeInterface $node
     * @param \DomElement $element
     * @return void
     */
    protected function handleMediaRoot(NodeInterface $node, \DOMElement $element): void
    {
        $media = $node->newMedia();
        $this->setUrl($media, $node, $this->getAttributeValue($element, "url"));
        $media->setNodeName($element->nodeName);

        $this->handleMediaContent($element, $media);

        $tags = array(
            'media:rating' => 'handleMediaRating',
            'media:title' => 'handleMediaTitle',
            'media:description' => 'handleMediaDescription',
            'media:keywords' => 'handleMediaKeywords',
            'media:thumbnail' => 'handleMediaThumbnail',
            'media:category' => 'handleMediaCategory',
            'media:hash' => 'handleMediaHash',
            'media:player' => 'handleMediaPlayer',
            'media:credit' => 'handleMediaCredit',
            'media:copyright' => 'handleMediaCopyright',
            'media:text' => 'handleMediaText',
            'media:restriction' => 'handleMediaRestriction',
            'media:community' => 'handleMediaCommunity',
            'media:comments' => 'handleMediaComments',
            'media:embed' => 'handleMediaEmbed',
            'media:responses' => 'handleMediaResponses',
            'media:backLinks' => 'handleMediaBacklinks',
            'media:status' => 'handleMediaStatus',
            'media:price' => 'handleMediaPrice',
            'media:license' => 'handleMediaLicense',
            'media:subTitle' => 'handleMediaSubtitle',
            'media:peerLink' => 'handleMediaPeerlink',
            'media:rights' => 'handleMediaRights',
            'media:scenes' => 'handleMediaScenes',
        );

        foreach ($tags as $tag => $callback) {
            $nodes = $this->findTags($element, $tag);
            if ($nodes) {
                $this->$callback($nodes, $media);
            }
        }

        $node->addMedia($media);
    }

    /**
     * @param \DomElement $element
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaContent(?\DOMElement $element, MediaInterface $media) : void
    {
        $media->setUrl($this->getAttributeValue($element, "url"));
        $media->setType($this->getAttributeValue($element, "type"));

        $content = new MediaContent();
        $content->setFileSize(intval($this->getAttributeValue($element, "fileSize")));
        $content->setBitrate(intval($this->getAttributeValue($element, "bitrate")));
        $content->setFramerate(intval($this->getAttributeValue($element, "framerate")));
        $content->setSamplingrate(floatval($this->getAttributeValue($element, "samplingrate")));
        $content->setDuration(intval($this->getAttributeValue($element, "duration")));
        $content->setHeight(intval($this->getAttributeValue($element, "height")));
        $content->setWidth(intval($this->getAttributeValue($element, "width")));
        $content->setLang($this->getAttributeValue($element, "lang"));
        $content->setExpression(MediaContentExpression::fromXML(
            $this->getAttributeValue($element, "expression")
        ));
        $content->setMedium(
            MediaContentMedium::fromXML(
                $this->getAttributeValue($element, "medium")
            )
        );

        switch ($this->getAttributeValue($element, "isDefault")) {
            case "true":
                $content->setDefault(true);
                break;
            case "false":
                $content->setDefault(false);
                break;
        }
        $media->setContent($content);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaRating(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $rating = new MediaRating();

        $rating->setContent($element->nodeValue);
        $rating->setScheme($this->getAttributeValue($element, "scheme") ?: 'urn:simple');

        $media->setRating($rating);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaTitle(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $media->setTitle($element->nodeValue);
        $media->setTitleType(MediaTitleType::fromXML(
            $this->getAttributeValue($element, "type")
        ));
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaDescription(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $media->setDescription($element->nodeValue);
        $media->setDescriptionType(MediaDescriptionType::fromXML(
            $this->getAttributeValue($element, "type")
        ));
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaKeywords(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $media->setKeywords(array_map("trim", explode(
            ',',
            $element->nodeValue
        )));
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaThumbnail(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $thumbnail = new MediaThumbnail();

        $thumbnail->setUrl($this->getAttributeValue($element, "url"));
        $thumbnail->setWidth(intval($this->getAttributeValue($element, "width")));
        $thumbnail->setHeight(intval($this->getAttributeValue($element, "height")));
        if ($element->hasAttribute("time")) {
            $thumbnail->setTime(new \DateTime($this->getAttributeValue($element, "time")));
        }

        $media->setThumbnail($thumbnail);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaCategory(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $category = new MediaCategory();

        $category->setText($element->nodeValue);
        $category->setLabel($this->getAttributeValue($element, "label"));
        $default_scheme = "http://search.yahoo.com/mrss/category_schema";
        $category->setScheme($this->getAttributeValue($element, "scheme") ?: $default_scheme);

        $media->setCategory($category);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaHash(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $hash = new MediaHash();

        $hash->setContent($element->nodeValue);
        $hash->setAlgo(MediaHashAlgo::fromXML($this->getAttributeValue($element, "algo")));

        $media->setHash($hash);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaPlayer(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $player = new MediaPlayer();

        $player->setUrl($this->getAttributeValue($element, "url"));
        $player->setWidth(intval($this->getAttributeValue($element, "width")));
        $player->setHeight(intval($this->getAttributeValue($element, "height")));

        $media->setPlayer($player);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaCredit(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $credits = array();
        foreach ($elements as $element) {
            $credit = new MediaCredit();

            $credit->setValue($element->nodeValue);
            $credit->setScheme(MediaCreditScheme::fromXML($this->getAttributeValue($element, "scheme")));
            $credit->setRole($this->getAttributeValue($element, "role"));
            array_push($credits, $credit);
        }
        $media->setCredits($credits);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaCopyright(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $copyright = new MediaCopyright();

        $copyright->setContent($element->nodeValue);
        $copyright->setUrl($this->getAttributeValue($element, "url"));

        $media->setCopyright($copyright);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaText(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $texts = array();
        foreach ($elements as $element) {
            $text = new MediaText();

            $text->setValue($element->nodeValue);
            $text->setType(MediaTextType::fromXML($this->getAttributeValue($element, "type")));
            $text->setLang($this->getAttributeValue($element, "lang"));
            if ($element->hasAttribute("start")) {
                $text->setStart(new \DateTime($this->getAttributeValue($element, "start")));
            }
            if ($element->hasAttribute("end")) {
                $text->setEnd(new \DateTime($this->getAttributeValue($element, "end")));
            }
            array_push($texts, $text);
        }
        $media->setTexts($texts);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaRestriction(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $restriction = new MediaRestriction();

        $restriction->setContent($element->nodeValue);
        $restriction->setType(MediaRestrictionType::fromXML($this->getAttributeValue($element, "type")));
        $restriction->setRelationship(MediaRestrictionRelationship::fromXML($this->getAttributeValue($element, "relationship")));

        $media->setRestriction($restriction);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaCommunity(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $community = new MediaCommunity();

        $community->setStarRatingAverage(floatval($this->getChildAttributeValue($element, "starRating", "average", static::MRSS_NAMESPACE)));
        $community->setStarRatingCount(intval($this->getChildAttributeValue($element, "starRating", "count", static::MRSS_NAMESPACE)));
        $community->setStarRatingMin(intval($this->getChildAttributeValue($element, "starRating", "min", static::MRSS_NAMESPACE)));
        $community->setStarRatingMax(intval($this->getChildAttributeValue($element, "starRating", "max", static::MRSS_NAMESPACE)));
        $community->setNbViews(intval($this->getChildAttributeValue($element, "statistics", "views", static::MRSS_NAMESPACE)));
        $community->setNbFavorites(intval($this->getChildAttributeValue($element, "statistics", "favorites", static::MRSS_NAMESPACE)));

        $tags = array();
        $tagsValue = $this->getChildValue($element, "tags", static::MRSS_NAMESPACE);
        if ($tagsValue) {
            foreach (explode(",", $tagsValue) as $pair) {
                $values = explode(":", $pair);
                if (count($values) != 2) {
                    continue;
                }
                $key = trim($values[0]);
                $value = intval($values[1]);
                $tags[$key] = $value;
            }
        }
        $community->setTags($tags);

        $media->setCommunity($community);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaComments(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $comments = array();
        foreach ($element->childNodes as $node) {
            if (is_a($node, "DOMNode") && $node->nodeName == "media:comment") {
                array_push($comments, $node->nodeValue);
            }
        }
        $media->setComments($comments);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaEmbed(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $embed = new MediaEmbed();

        $embed->setUrl($this->getAttributeValue($element, "url"));
        $embed->setWidth(intval($this->getAttributeValue($element, "width")));
        $embed->setHeight(intval($this->getAttributeValue($element, "height")));

        $params = array();
        foreach ($element->childNodes as $node) {
            if (is_a($node, "DOMNode") && $node->nodeName == "media:param") {
                $key = $this->getAttributeValue($node, "name");
                $value = $node->nodeValue;
                $params[$key] = trim($value);
            }
        }

        $embed->setParams($params);

        $media->setEmbed($embed);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaResponses(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $responses = array();
        foreach ($element->childNodes as $node) {
            if (is_a($node, "DOMNode") && $node->nodeName == "media:response") {
                array_push($responses, $node->nodeValue);
            }
        }
        $media->setResponses($responses);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaBacklinks(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $backlinks = array();
        foreach ($element->childNodes as $node) {
            if (is_a($node, "DOMNode") && $node->nodeName == "media:backLink") {
                array_push($backlinks, $node->nodeValue);
            }
        }
        $media->setBacklinks($backlinks);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaStatus(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $status = new MediaStatus();

        $status->setValue(MediaStatusValue::fromXML($this->getAttributeValue($element, "state")));
        $status->setReason($this->getAttributeValue($element, "reason"));

        $media->setStatus($status);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaPrice(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $prices = array();
        foreach ($elements as $element) {
            $price = new MediaPrice();

            $price->setType(MediaPriceType::fromXML($this->getAttributeValue($element, "type")));
            $price->setValue(floatval($this->getAttributeValue($element, "price")));
            $price->setCurrency($this->getAttributeValue($element, "currency"));
            array_push($prices, $price);
        }
        $media->setPrices($prices);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaLicense(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $license = new MediaLicense();

        $license->setContent($element->nodeValue);
        $license->setUrl($this->getAttributeValue($element, "href"));
        $license->setType($this->getAttributeValue($element, "type"));

        $media->setLicense($license);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaSubtitle(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $subtitles = array();
        foreach ($elements as $element) {
            $subtitle = new MediaSubtitle();

            $subtitle->setType($this->getAttributeValue($element, "type"));
            $subtitle->setLang($this->getAttributeValue($element, "lang"));
            $subtitle->setUrl($this->getAttributeValue($element, "href"));
            array_push($subtitles, $subtitle);
        }
        $media->setSubTitles($subtitles);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaPeerlink(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);
        $peerLink = new MediaPeerLink();

        $peerLink->setUrl($this->getAttributeValue($element, "href"));
        $peerLink->setType($this->getAttributeValue($element, "type"));

        $media->setPeerLink($peerLink);
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaRights(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $media->setRights(MediaRightsStatus::fromXML($this->getAttributeValue($element, "status")));
    }

    /**
     * @param \DomElement $elements
     * @param MediaInterface $media
     * @return void
     */
    protected function handleMediaScenes(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $scenes = array();
        foreach ($element->childNodes as $node) {
            if (is_a($node, "DOMNode") && $node->nodeName == "media:scene") {
                $scene = new MediaScene();

                $scene->setTitle($this->getChildValue($node, "sceneTitle"));
                $scene->setDescription($this->getChildValue($node, "sceneDescription"));
                $startTime = $this->getChildValue($node, "sceneStartTime");
                $endTime = $this->getChildValue($node, "sceneEndTime");

                if ($startTime !== null) {
                    $scene->setStartTime(new \DateTime($startTime));
                }

                if ($endTime !== null) {
                    $scene->setEndTime(new \DateTime($endTime));
                }

                array_push($scenes, $scene);
            }
        }
        $media->setScenes($scenes);
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return $node instanceof ItemInterface && !! $node->getMedias();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        foreach ($node->getMedias() as $media) {
            $rootElement->appendChild($this->createMediaElement($document, $media));
        }
    }

    /**
     * From http://www.rssboard.org/media-rss#optional-elements
     *
     * Duplicated elements appearing at deeper levels of the document tree
     * have higher priority over other levels. For example, <media:content>
     * level elements are favored over <item> level elements. The priority
     * level is listed from strongest to weakest:
     * <media:content>, <media:group>, <item>, <channel>.
     */
    public function findTags(\DOMElement $mediaContentNode, string $tag) : ? \DOMNodeList
    {
        $xpath = new \DOMXpath($mediaContentNode->ownerDocument);
        $queries = [
            $xpath->query("./descendant::$tag", $mediaContentNode),
            $xpath->query("./ancestor::media:group/child::$tag", $mediaContentNode),
            $xpath->query("./ancestor::item/child::$tag", $mediaContentNode),
            $xpath->query("./ancestor::channel/child::$tag", $mediaContentNode),
        ];

        foreach ($queries as $query) {
            if ($query->count()) {
                return $query;
            }
        }

        return null;
    }
}
