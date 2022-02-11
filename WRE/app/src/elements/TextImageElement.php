<?php

namespace App\Elements;

use App\Utils\Utilities;
use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\Link\Models\Link;
use SilverShop\HasOneField\HasOneButtonField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;

class TextImageElement extends BaseElement
{
    private static $table_name = "TextImageElement";
    private static $singular_name = 'Text Image Element';
    private static $plural_name = 'Text Image Elements';
    private static $description = 'Text and an image';
    private static $icon = 'font-icon-block-content';

    private static $db = [
        "Content" => "HTMLText",
        "ImageOnLeft" => "Boolean",
        "VideoLink" => "Varchar(200)",
        'ShowTitle' => 'Boolean',
        'LessTopPadding' => 'Boolean'
    ];

    private static $has_one = [
        "Image" => Image::class,
        'TextImageLink' => Link::class
    ];

    private static $owns = [
        'TextImageLink',
        "Image",
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeFieldsFromTab('Root.Main', [
            'Title', 'ShowTitle', 'Content', 'ImageOnLeft', 'Image', 'VideoLink','TextImageLinkID', 'LessTopPadding'
        ]);
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title'),
            CheckboxField::create('ShowTitle', 'Show Title?'),
            HTMLEditorField::create("Content"),
            CheckboxField::create("ImageOnLeft", "Show image on left hand side of text"),
            CheckboxField::create('LessTopPadding', 'Less Whitespace if first element?')
            ->setDescription('If this is the first element on the page, setting this to true
            will reduce the amount of whitespace there is between it and the banner'),
            UploadField::create("Image"),
            TextField::create("VideoLink", "Video Link")
                ->setDescription("Add a Youtube Video link here!"),
            HasOneButtonField::create($this->owner, 'TextImageLink')
                ->setDescription('The title of this link will appear on the button.')
        ]);


        return $fields;
    }

    public function getType()
    {
        return self::$singular_name;
    }

    public function returnParsedLink()
    {
        return Utilities::manipulateYoutubeLink($this->VideoLink);
    }
}