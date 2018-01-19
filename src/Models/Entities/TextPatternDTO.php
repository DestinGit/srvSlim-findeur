<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 04/01/2018
 * Time: 11:48
 */

namespace app\Entities;


class TextPatternDTO
{
    private $ID;
    private $posted;
    private $expires;
    private $authorID;
    private $lastMod;
    private $lastModID;
    private $title;
    private $titleHtml;
    private $body;
    private $bodyHtml;
    private $excerpt;
    private $excerptHtml;
    private $image;
    private $category1;
    private $category2;
    private $annotate;
    private $annotateInvite;
    private $commentsCount;
    private $status;
    private $textileBody;
    private $textileExcerpt;
    private $section;
    private $overrideForm;
    private $keywords;
    private $urlTitle;
    private $custom1;
    private $custom2;
    private $custom3;
    private $custom4;
    private $custom5;
    private $custom6;
    private $custom7;
    private $custom8;
    private $custom9;
    private $custom10;
    private $uid;
    private $feedTime;
    private $custom11;
    private $custom12;
    private $custom13;
    private $custom14;
    private $custom15;
    private $custom16;
    private $custom17;
    private $custom18;
    private $custom19;
    private $custom20;
    private $custom21;
    private $custom22;
    private $custom23;
    private $custom24;
    private $custom25;
    private $custom26;
    private $custom27;
    private $custom28;
    private $custom29;
    private $custom30;
    private $custom31;
    private $custom32;
    private $custom33;
    private $custom34;

    private static $columnMap = [
        'user_id' => 'ID',
        'Posted' => 'posted',
        'Expires' => 'expires',
        'AuthorID' => 'authorID',
        'LastMod' => 'lastMod',
        'LastModID' => 'lastModID',
        'Title' => 'title',
        'Title_html' => 'titleHtml',
        'Body' => 'body',
        'Body_html' => 'bodyHtml',
        'Excerpt' => 'excerpt',
        'Excerpt_html' => 'excerptHtml',
        'Image' => 'image',
        'Category1' => 'category1',
        'Category2' => 'category2',
        'Annotate' => 'annotate',
        'AnnotateInvite' => 'annotateInvite',
        'comments_count' => 'commentsCount',
        'Status' => 'status',
        'textile_body' => 'textileBody',
        'textile_excerpt' => 'textileExcerpt',
        'Section' => 'section',
        'override_form' => 'overrideForm',
        'Keywords' => 'keywords',
        'url_title' => 'urlTitle',
        'custom_1' => 'custom1',
        'custom_2' => 'custom2',
        'custom_3' => 'custom3',
        'custom_4' => 'custom4',
        'custom_5' => 'custom5',
        'custom_6' => 'custom6',
        'custom_7' => 'custom7',
        'custom_8' => 'custom8',
        'custom_9' => 'custom9',
        'custom_10' => 'custom10',
        'uid' => 'uid',
        'feed_time' => 'feedTime',
        'custom_11' => 'custom11',
        'custom_12' => 'custom12',
        'custom_13' => 'custom13',
        'custom_14' => 'custom14',
        'custom_15' => 'custom15',
        'custom_16' => 'custom16',
        'custom_17' => 'custom17',
        'custom_18' => 'custom18',
        'custom_19' => 'custom19',
        'custom_20' => 'custom20',
        'custom_21' => 'custom21',
        'custom_22' => 'custom22',
        'custom_23' => 'custom23',
        'custom_24' => 'custom24',
        'custom_25' => 'custom25',
        'custom_26' => 'custom26',
        'custom_27' => 'custom27',
        'custom_28' => 'custom28',
        'custom_29' => 'custom29',
        'custom_30' => 'custom30',
        'custom_31' => 'custom31',
        'custom_32' => 'custom32',
        'custom_33' => 'custom33',
        'custom_34' => 'custom34'
    ];

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, self::$columnMap)) {
            $attributeName = self::$columnMap[$name];
            $this->$attributeName = $value;
        }
    }


    /**
     * @param array $data
     */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $val) {
            $methodName = "set" . ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->$methodName($val);
            } else {
                if (array_key_exists($key, self::$columnMap)) {
                    $methodName = $methodName = "set" . ucfirst(self::$columnMap[$key]);
                    $this->$methodName($val);
                }
            }
        }
    }

    //************************************************************* SETTERS

    /**
     * @param mixed $ID
     * @return TextPatternDTO
     */
    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @param mixed $posted
     * @return TextPatternDTO
     */
    public function setPosted($posted)
    {
        $this->posted = $posted;
        return $this;
    }

    /**
     * @param mixed $expires
     * @return TextPatternDTO
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @param mixed $authorID
     * @return TextPatternDTO
     */
    public function setAuthorID($authorID)
    {
        $this->authorID = $authorID;
        return $this;
    }

    /**
     * @param mixed $lastMod
     * @return TextPatternDTO
     */
    public function setLastMod($lastMod)
    {
        $this->lastMod = $lastMod;
        return $this;
    }

    /**
     * @param mixed $lastModID
     * @return TextPatternDTO
     */
    public function setLastModID($lastModID)
    {
        $this->lastModID = $lastModID;
        return $this;
    }

    /**
     * @param mixed $title
     * @return TextPatternDTO
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param mixed $titleHtml
     * @return TextPatternDTO
     */
    public function setTitleHtml($titleHtml)
    {
        $this->titleHtml = $titleHtml;
        return $this;
    }

    /**
     * @param mixed $body
     * @return TextPatternDTO
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param mixed $bodyHtml
     * @return TextPatternDTO
     */
    public function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;
        return $this;
    }

    /**
     * @param mixed $excerpt
     * @return TextPatternDTO
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    /**
     * @param mixed $excerptHtml
     * @return TextPatternDTO
     */
    public function setExcerptHtml($excerptHtml)
    {
        $this->excerptHtml = $excerptHtml;
        return $this;
    }

    /**
     * @param mixed $image
     * @return TextPatternDTO
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param mixed $category1
     * @return TextPatternDTO
     */
    public function setCategory1($category1)
    {
        $this->category1 = $category1;
        return $this;
    }

    /**
     * @param mixed $category2
     * @return TextPatternDTO
     */
    public function setCategory2($category2)
    {
        $this->category2 = $category2;
        return $this;
    }

    /**
     * @param mixed $annotate
     * @return TextPatternDTO
     */
    public function setAnnotate($annotate)
    {
        $this->annotate = $annotate;
        return $this;
    }

    /**
     * @param mixed $annotateInvite
     * @return TextPatternDTO
     */
    public function setAnnotateInvite($annotateInvite)
    {
        $this->annotateInvite = $annotateInvite;
        return $this;
    }

    /**
     * @param mixed $commentsCount
     * @return TextPatternDTO
     */
    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;
        return $this;
    }

    /**
     * @param mixed $status
     * @return TextPatternDTO
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param mixed $textileBody
     * @return TextPatternDTO
     */
    public function setTextileBody($textileBody)
    {
        $this->textileBody = $textileBody;
        return $this;
    }

    /**
     * @param mixed $textileExcerpt
     * @return TextPatternDTO
     */
    public function setTextileExcerpt($textileExcerpt)
    {
        $this->textileExcerpt = $textileExcerpt;
        return $this;
    }

    /**
     * @param mixed $section
     * @return TextPatternDTO
     */
    public function setSection($section)
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @param mixed $overrideForm
     * @return TextPatternDTO
     */
    public function setOverrideForm($overrideForm)
    {
        $this->overrideForm = $overrideForm;
        return $this;
    }

    /**
     * @param mixed $keywords
     * @return TextPatternDTO
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * @param mixed $urlTitle
     * @return TextPatternDTO
     */
    public function setUrlTitle($urlTitle)
    {
        $this->urlTitle = $urlTitle;
        return $this;
    }

    /**
     * @param mixed $custom1
     * @return TextPatternDTO
     */
    public function setCustom1($custom1)
    {
        $this->custom1 = $custom1;
        return $this;
    }

    /**
     * @param mixed $custom2
     * @return TextPatternDTO
     */
    public function setCustom2($custom2)
    {
        $this->custom2 = $custom2;
        return $this;
    }

    /**
     * @param mixed $custom3
     * @return TextPatternDTO
     */
    public function setCustom3($custom3)
    {
        $this->custom3 = $custom3;
        return $this;
    }

    /**
     * @param mixed $custom4
     * @return TextPatternDTO
     */
    public function setCustom4($custom4)
    {
        $this->custom4 = $custom4;
        return $this;
    }

    /**
     * @param mixed $custom5
     * @return TextPatternDTO
     */
    public function setCustom5($custom5)
    {
        $this->custom5 = $custom5;
        return $this;
    }

    /**
     * @param mixed $custom6
     * @return TextPatternDTO
     */
    public function setCustom6($custom6)
    {
        $this->custom6 = $custom6;
        return $this;
    }

    /**
     * @param mixed $custom7
     * @return TextPatternDTO
     */
    public function setCustom7($custom7)
    {
        $this->custom7 = $custom7;
        return $this;
    }

    /**
     * @param mixed $custom8
     * @return TextPatternDTO
     */
    public function setCustom8($custom8)
    {
        $this->custom8 = $custom8;
        return $this;
    }

    /**
     * @param mixed $custom9
     * @return TextPatternDTO
     */
    public function setCustom9($custom9)
    {
        $this->custom9 = $custom9;
        return $this;
    }

    /**
     * @param mixed $custom10
     * @return TextPatternDTO
     */
    public function setCustom10($custom10)
    {
        $this->custom10 = $custom10;
        return $this;
    }

    /**
     * @param mixed $uid
     * @return TextPatternDTO
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @param mixed $feedTime
     * @return TextPatternDTO
     */
    public function setFeedTime($feedTime)
    {
        $this->feedTime = $feedTime;
        return $this;
    }

    /**
     * @param mixed $custom11
     * @return TextPatternDTO
     */
    public function setCustom11($custom11)
    {
        $this->custom11 = $custom11;
        return $this;
    }

    /**
     * @param mixed $custom12
     * @return TextPatternDTO
     */
    public function setCustom12($custom12)
    {
        $this->custom12 = $custom12;
        return $this;
    }

    /**
     * @param mixed $custom13
     * @return TextPatternDTO
     */
    public function setCustom13($custom13)
    {
        $this->custom13 = $custom13;
        return $this;
    }

    /**
     * @param mixed $custom14
     * @return TextPatternDTO
     */
    public function setCustom14($custom14)
    {
        $this->custom14 = $custom14;
        return $this;
    }

    /**
     * @param mixed $custom15
     * @return TextPatternDTO
     */
    public function setCustom15($custom15)
    {
        $this->custom15 = $custom15;
        return $this;
    }

    /**
     * @param mixed $custom16
     * @return TextPatternDTO
     */
    public function setCustom16($custom16)
    {
        $this->custom16 = $custom16;
        return $this;
    }

    /**
     * @param mixed $custom17
     * @return TextPatternDTO
     */
    public function setCustom17($custom17)
    {
        $this->custom17 = $custom17;
        return $this;
    }

    /**
     * @param mixed $custom18
     * @return TextPatternDTO
     */
    public function setCustom18($custom18)
    {
        $this->custom18 = $custom18;
        return $this;
    }

    /**
     * @param mixed $custom19
     * @return TextPatternDTO
     */
    public function setCustom19($custom19)
    {
        $this->custom19 = $custom19;
        return $this;
    }

    /**
     * @param mixed $custom20
     * @return TextPatternDTO
     */
    public function setCustom20($custom20)
    {
        $this->custom20 = $custom20;
        return $this;
    }

    /**
     * @param mixed $custom21
     * @return TextPatternDTO
     */
    public function setCustom21($custom21)
    {
        $this->custom21 = $custom21;
        return $this;
    }

    /**
     * @param mixed $custom22
     * @return TextPatternDTO
     */
    public function setCustom22($custom22)
    {
        $this->custom22 = $custom22;
        return $this;
    }

    /**
     * @param mixed $custom23
     * @return TextPatternDTO
     */
    public function setCustom23($custom23)
    {
        $this->custom23 = $custom23;
        return $this;
    }

    /**
     * @param mixed $custom24
     * @return TextPatternDTO
     */
    public function setCustom24($custom24)
    {
        $this->custom24 = $custom24;
        return $this;
    }

    /**
     * @param mixed $custom25
     * @return TextPatternDTO
     */
    public function setCustom25($custom25)
    {
        $this->custom25 = $custom25;
        return $this;
    }

    /**
     * @param mixed $custom26
     * @return TextPatternDTO
     */
    public function setCustom26($custom26)
    {
        $this->custom26 = $custom26;
        return $this;
    }

    /**
     * @param mixed $custom27
     * @return TextPatternDTO
     */
    public function setCustom27($custom27)
    {
        $this->custom27 = $custom27;
        return $this;
    }

    /**
     * @param mixed $custom28
     * @return TextPatternDTO
     */
    public function setCustom28($custom28)
    {
        $this->custom28 = $custom28;
        return $this;
    }

    /**
     * @param mixed $custom29
     * @return TextPatternDTO
     */
    public function setCustom29($custom29)
    {
        $this->custom29 = $custom29;
        return $this;
    }

    /**
     * @param mixed $custom30
     * @return TextPatternDTO
     */
    public function setCustom30($custom30)
    {
        $this->custom30 = $custom30;
        return $this;
    }

    /**
     * @param mixed $custom31
     * @return TextPatternDTO
     */
    public function setCustom31($custom31)
    {
        $this->custom31 = $custom31;
        return $this;
    }

    /**
     * @param mixed $custom32
     * @return TextPatternDTO
     */
    public function setCustom32($custom32)
    {
        $this->custom32 = $custom32;
        return $this;
    }

    /**
     * @param mixed $custom33
     * @return TextPatternDTO
     */
    public function setCustom33($custom33)
    {
        $this->custom33 = $custom33;
        return $this;
    }

    /**
     * @param mixed $custom34
     * @return TextPatternDTO
     */
    public function setCustom34($custom34)
    {
        $this->custom34 = $custom34;
        return $this;
    }

    //************************************************************* GETTERS

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @return mixed
     */
    public function getPosted()
    {
        return $this->posted;
    }

    /**
     * @return mixed
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @return mixed
     */
    public function getAuthorID()
    {
        return $this->authorID;
    }

    /**
     * @return mixed
     */
    public function getLastMod()
    {
        return $this->lastMod;
    }

    /**
     * @return mixed
     */
    public function getLastModID()
    {
        return $this->lastModID;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getTitleHtml()
    {
        return $this->titleHtml;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * @return mixed
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * @return mixed
     */
    public function getExcerptHtml()
    {
        return $this->excerptHtml;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getCategory1()
    {
        return $this->category1;
    }

    /**
     * @return mixed
     */
    public function getCategory2()
    {
        return $this->category2;
    }

    /**
     * @return mixed
     */
    public function getAnnotate()
    {
        return $this->annotate;
    }

    /**
     * @return mixed
     */
    public function getAnnotateInvite()
    {
        return $this->annotateInvite;
    }

    /**
     * @return mixed
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getTextileBody()
    {
        return $this->textileBody;
    }

    /**
     * @return mixed
     */
    public function getTextileExcerpt()
    {
        return $this->textileExcerpt;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return mixed
     */
    public function getOverrideForm()
    {
        return $this->overrideForm;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @return mixed
     */
    public function getUrlTitle()
    {
        return $this->urlTitle;
    }

    /**
     * @return mixed
     */
    public function getCustom1()
    {
        return $this->custom1;
    }

    /**
     * @return mixed
     */
    public function getCustom2()
    {
        return $this->custom2;
    }

    /**
     * @return mixed
     */
    public function getCustom3()
    {
        return $this->custom3;
    }

    /**
     * @return mixed
     */
    public function getCustom4()
    {
        return $this->custom4;
    }

    /**
     * @return mixed
     */
    public function getCustom5()
    {
        return $this->custom5;
    }

    /**
     * @return mixed
     */
    public function getCustom6()
    {
        return $this->custom6;
    }

    /**
     * @return mixed
     */
    public function getCustom7()
    {
        return $this->custom7;
    }

    /**
     * @return mixed
     */
    public function getCustom8()
    {
        return $this->custom8;
    }

    /**
     * @return mixed
     */
    public function getCustom9()
    {
        return $this->custom9;
    }

    /**
     * @return mixed
     */
    public function getCustom10()
    {
        return $this->custom10;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getFeedTime()
    {
        return $this->feedTime;
    }

    /**
     * @return mixed
     */
    public function getCustom11()
    {
        return $this->custom11;
    }

    /**
     * @return mixed
     */
    public function getCustom12()
    {
        return $this->custom12;
    }

    /**
     * @return mixed
     */
    public function getCustom13()
    {
        return $this->custom13;
    }

    /**
     * @return mixed
     */
    public function getCustom14()
    {
        return $this->custom14;
    }

    /**
     * @return mixed
     */
    public function getCustom15()
    {
        return $this->custom15;
    }

    /**
     * @return mixed
     */
    public function getCustom16()
    {
        return $this->custom16;
    }

    /**
     * @return mixed
     */
    public function getCustom17()
    {
        return $this->custom17;
    }

    /**
     * @return mixed
     */
    public function getCustom18()
    {
        return $this->custom18;
    }

    /**
     * @return mixed
     */
    public function getCustom19()
    {
        return $this->custom19;
    }

    /**
     * @return mixed
     */
    public function getCustom20()
    {
        return $this->custom20;
    }

    /**
     * @return mixed
     */
    public function getCustom21()
    {
        return $this->custom21;
    }

    /**
     * @return mixed
     */
    public function getCustom22()
    {
        return $this->custom22;
    }

    /**
     * @return mixed
     */
    public function getCustom23()
    {
        return $this->custom23;
    }

    /**
     * @return mixed
     */
    public function getCustom24()
    {
        return $this->custom24;
    }

    /**
     * @return mixed
     */
    public function getCustom25()
    {
        return $this->custom25;
    }

    /**
     * @return mixed
     */
    public function getCustom26()
    {
        return $this->custom26;
    }

    /**
     * @return mixed
     */
    public function getCustom27()
    {
        return $this->custom27;
    }

    /**
     * @return mixed
     */
    public function getCustom28()
    {
        return $this->custom28;
    }

    /**
     * @return mixed
     */
    public function getCustom29()
    {
        return $this->custom29;
    }

    /**
     * @return mixed
     */
    public function getCustom30()
    {
        return $this->custom30;
    }

    /**
     * @return mixed
     */
    public function getCustom31()
    {
        return $this->custom31;
    }

    /**
     * @return mixed
     */
    public function getCustom32()
    {
        return $this->custom32;
    }

    /**
     * @return mixed
     */
    public function getCustom33()
    {
        return $this->custom33;
    }

    /**
     * @return mixed
     */
    public function getCustom34()
    {
        return $this->custom34;
    }

}