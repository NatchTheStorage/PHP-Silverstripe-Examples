<?php

namespace App\Elements;

use App\Models\SketchObject;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\TextField;


class SketchObjectsElement extends BaseElement
{
  private static $table_name = "SketchObjectsElement";
  private static $singular_name = 'Sketch Objects Element';
  private static $plural_name = 'Sketch Objects Elements';
  private static $description = 'Displays sketch3d iframes';
  private static $icon = 'font-icon-block-content';

  private static $db = [
    'Title' => "Text",
  ];

  private static $has_many = [
    'Objects' => SketchObject::class
  ];

  private static $inline_editable = false;

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $fields->removeFieldsFromTab('Root.Main', [
      'Title',
    ]);

    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title')
      ->setDescription('For organisational purposes only')
    ]);

    return $fields;
  }

  public function MoreThanOne() {
    if ($this->Objects()->count() > 1) {
      return true;
    }
    return false;
  }

  public function FirstObject() {

    return $this->Objects()->first();
  }

  public function getType()
  {
    return self::$singular_name;
  }
}
