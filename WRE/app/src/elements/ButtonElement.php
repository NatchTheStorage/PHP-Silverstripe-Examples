<?php

namespace App\Elements;

use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;

class ButtonElement extends BaseElement
{
    private static $singular_name = 'Button Element';
    private static $plural_name = 'Button Elements';
    private static $description = 'An element with some number of buttons';
    private static $icon = 'font-icon-block-layout-5';

    private static $db = [

    ];

    private static $many_many = [
        "Links" => Link::class
    ];

    private static $many_many_extraFields = [
        'Links' => [
            'Sort' => 'Int' // Required for all many_many relationships
        ]
    ];

    private static $owns = [
        "Links"
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Links');
        $fields->addFieldsToTab('Root.Main', [
            LinkField::create('Links', 'Links', $this)
        ]);
        return $fields;
    }

    public function getType()
    {
        return self::$singular_name;
    }
}