<?php

namespace App\Pages;

use Page;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;

class LandingPage extends Page
{
  private static $table_name = "LandingPage";
  private static $singular_name = "Landing Page";
  private static $plural_name = "Landing Page";

  private static $db = [
    'HeaderTitle' => 'Text',
    'HeaderSubtitle' => 'Text',
    'Content' => 'Text',
    'BlockTitle' => 'Text',
  ];

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->removeByName('Banner');

    $fields->removeFieldsFromTab('Root.Main' ,[
      'Content',
      'BlockTitle',
      'RemoveFromHeader',
      'HeaderTitle',
      'HeaderSubtitle'
    ]);
    $fields->addFieldsToTab("Root.Main", [
      HeaderField::create('hf1', 'Header'),
      TextField::create('HeaderTitle', 'Header Title'),
      TextField::create('HeaderSubtitle', 'Header Subtitle'),

      HeaderField::create('hf2', 'Content Area'),
      TextField::create('BlockTitle', 'Title')
      ->setDescription('The title displayed in the main block of text'),
      TextareaField::create('Content')
      ->setDescription('The text content underneath the title')
    ]);

    return $fields;
  }

  public function isHome()
  {
    return true;
  }
}