<?php

namespace App\Elements;

use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\Link\Models\Link;
use SilverShop\HasOneField\HasOneButtonField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;

class ContentElement extends BaseElement
{
    private static $table_name = "ContentElement";
    private static $singular_name = 'Content Element';
    private static $plural_name = 'Content Elements';
    private static $icon = 'font-icon-p-document';

    private static $db = [
        "Content" => "HTMLText",
        'ShowTitle' => 'Boolean'
    ];

    private static $has_one = [
        "ContentElementLink" => Link::class,
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('ContentElementLinkID');
        $fields->removeFieldsFromTab('Root.Main', [
            'ContentElementLinkID', 'Title', 'Content'
        ]);
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title'),
            CheckboxField::create('ShowTitle', 'Display Title?'),
            HTMLEditorField::create("Content"),
            HasOneButtonField::create($this->owner, 'ContentElementLink'),
        ]);
        return $fields;
    }

    public function getType()
    {
        return self::$singular_name;
    }
}