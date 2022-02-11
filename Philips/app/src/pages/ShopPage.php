<?php

namespace App\Pages;

use Page;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;

class ShopPage extends Page
{
  private static $table_name = "ShopPage";
  private static $singular_name = "Shop Page";
  private static $plural_name = "Shop Pages";

  private static $db = [
    'BlockTitle' => 'Text',
    'BlockContent' => 'Text',

    'PrimaryColour' => 'Varchar(10)',
    'SecondaryColour' => 'Varchar(10)',
  ];

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $fields->removeFieldsFromTab('Root.Main' ,[
      'BlockContent', 'BlockTitle', 'Content'
    ]);
    $fields->addFieldsToTab("Root.Main", [
      TextField::create('BlockTitle', 'Title')
        ->setDescription('The title displayed in the main block of text'),
      TextareaField::create('BlockContent')
        ->setDescription('The text content underneath the title'),

      HeaderField::create('hf1', 'Shop Colours'),
      LiteralField::create('lf1', '<p>The colour that the controls in the shop will take,
        please do not change this without contacting the developers.</p><br>
        <p>Colours should be entered as hex codes prefixed with a # (#ffffff)</p>'),
      TextField::create('PrimaryColour', 'Primary Colour'),
      TextField::create('SecondaryColour', 'Secondary Colour')
        ->setDescription(''),
    ]);

    return $fields;
  }
}