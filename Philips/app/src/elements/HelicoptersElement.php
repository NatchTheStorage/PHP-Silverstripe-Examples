<?php

namespace App\Elements;

use App\Models\Helicopter;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\TextField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;


class HelicoptersElement extends BaseElement
{
  private static $table_name = "HelicoptersElement";
  private static $singular_name = 'Helicopters Element';
  private static $plural_name = 'Helicopters Elements';
  private static $description = 'Displays some helicopter blocks';
  private static $icon = 'font-icon-block-content';

  private static $db = [
    'Title' => "Text",
    'DisplayTitle' => 'Text'
  ];

  private static $has_many = [
    'Helicopters' => Helicopter::class
  ];

  private static $inline_editable = false;

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $fields->removeByName('Helicopters');
    $fields->removeFieldsFromTab('Root.Main', [
      'Title', 'DisplayTitle'
    ]);

    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title')
      ->setDescription('Used for filtering purposes, please do not change'),
      TextField::create('DisplayTitle', 'Display Title')
      ->setDescription('The title displayed on the page, you can change this one.  If left empty, it will default to HELICOPTERS'),
      GridField::create('Helicopters', 'Helicopters', $this->Helicopters(),GridFieldConfig_RecordEditor::create()
        ->addComponent(new GridFieldOrderableRows("SortOrder"))
      )
    ]);

    return $fields;
  }

  public function getType()
  {
    return self::$singular_name;
  }
}
