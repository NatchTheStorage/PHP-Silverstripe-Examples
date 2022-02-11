<?php

namespace App\Elements;

use App\Utils\Utilities;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\TextField;

class VideoElement extends BaseElement
{
    private static $table_name = "VideoElement";
    private static $singular_name = 'Video Element';
    private static $plural_name = 'Video Elements';
    private static $description = 'An element containing a video';
    private static $icon = 'font-icon-block-video';

    private static $db = [
        "VideoLink"=> "Varchar(200)"
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main','Title');
        $fields->addFieldToTab("Root.Main", TextField::create("Title", "Title"));
        $fields->addFieldToTab("Root.Main", TextField::create("VideoLink", "Video Link")
        ->setDescription("Add a Youtube Video link here!"));

        return $fields;
    }

    public function getType()
    {
        return self::$singular_name;
    }

    // Returns just the video ID of the youtube link
    public function returnParsedLink()
    {
        return Utilities::manipulateYoutubeLink($this->VideoLink);
    }
}