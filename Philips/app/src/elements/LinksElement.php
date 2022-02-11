<?php

namespace App\Elements;

use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;

class LinksElement extends BaseElement {
  private static $table_name = "LinksElement";
  private static $singular_name = "Links Element";
  private static $plural_name = "Links Elements";

  private static $inline_editable = false;
  private static $icon = 'font-icon-link';

  private static $db = [
    'Title' => 'Text',
    'ShowTitle' => 'Boolean',
    'Content' => 'Text'
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

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->removeFieldsFromTab('Root.Main', [
      'Title', 'Content', 'Links', 'ShowTitle'
    ]);
    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title'),
      CheckboxField::create('ShowTitle', 'Show Title?'),
      TextareaField::create('Content'),
      LinkField::create('Links', 'Links', $this)
    ]);
    return $fields;
  }

  public function getType()
  {
    return self::$singular_name;
  }
}