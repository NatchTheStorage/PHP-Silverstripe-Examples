<?php

namespace App\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;

class ContentElement extends BaseElement
{
  private static $table_name = "ContentElement";
  private static $singular_name = 'Content Element';
  private static $plural_name = 'Content Elements';
  private static $description = 'Contents HTML editable content';
  private static $icon = 'font-icon-block-content';

  private static $db = [
    "Content" => "HTMLText",
  ];

  private static $inline_editable = false;

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->removeFieldsFromTab('Root.Main', [
      'Title', 'ShowTitle', 'Content', 'ImageOnLeft', 'Image', 'TextImageLinkID'
    ]);
    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title')
      ->setDescription('You can add the title in the HTML content field below!
      It does not innately appear anywhere except the CMS, for organisational purposes'),
      HTMLEditorField::create('Content')
    ]);

    return $fields;
  }

  public function getType()
  {
    return self::$singular_name;
  }
}