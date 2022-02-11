<?php

namespace App\Elements;

use App\Models\TextImageImage;
use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\Link\Models\Link;
use SilverShop\HasOneField\HasOneButtonField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class TextImageElement extends BaseElement
{
  private static $table_name = "TextImageElement";
  private static $singular_name = 'Text Image Element';
  private static $plural_name = 'Text Image Elements';
  private static $description = 'Text and an image';
  private static $icon = 'font-icon-block-content';

  private static $db = [
    "Content" => "Text",
    "ImageOnLeft" => "Boolean",
    'ShowTitle' => 'Boolean',
  ];

  private static $has_one = [
    'TextImageLink' => Link::class
  ];

  private static $has_many = [
    "Images" => TextImageImage::class
  ];

  private static $owns = [
    'TextImageLink',
  ];

  private static $inline_editable = false;

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->removeFieldsFromTab('Root.Main', [
      'Title', 'ShowTitle', 'Content', 'ImageOnLeft', 'Image', 'TextImageLinkID'
    ]);
    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title'),
      CheckboxField::create('ShowTitle', 'Show Title?'),
      TextareaField::create("Content"),
      CheckboxField::create("ImageOnLeft", "Show image on left hand side of text"),
      GridField::create('Images', 'Images', $this->Images(),
        GridFieldConfig_RecordEditor::create()
          ->addComponent(new GridFieldOrderableRows("SortOrder"))),
      HasOneButtonField::create($this->owner, 'TextImageLink')
        ->setDescription('The title of this link will appear on the button.')
    ]);

    return $fields;
  }

  public function getType()
  {
    return self::$singular_name;
  }
}