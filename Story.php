<?php

namespace App\Models;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;

class Story extends DataObject
{
  private static $table_name = "Stories";
  private static $singular_name = "Story";
  private static $plural_name = "Stories";

  private static $db = [
    'Name' => 'Text',
    'Email' => 'Text',
    'PhoneNumber' => 'Text',
    'Title' => 'Text',
    'Date' => 'Date',
    'Content' => 'HTMLText',
    'VideoLink' => 'Text',
    'SortOrder' => 'Int',
    'Subsite' => 'Text',
    'Display' => 'Boolean'
  ];

  private static $has_one = [
    'Image' => File::class,
    'Image2' => File::class,
    'Image3' => File::class,
    'Image4' => File::class,
    'Mission' => Article::class
  ];

  private static $owns = [
    'Image',
    'Image2',
    'Image3',
    'Image4',
  ];

  private static $default_sort = "Created DESC";

  private static $summary_fields = [
    'Title', 'Date', 'Created', 'Subsite', 'Display'
  ];

  public function getCMSFields()
  {
    $subsite = [
      "Waikato" => "Waikato Westpac Rescue Helicopter",
      "TECT" => "Tect Rescue Helicopter",
      "Greenlea" => "Greenlea Rescue Helicopter",
      "Palmerston" => "Palmerston North Rescue Helicopter",
      "Westpac" => "Westpac Air Ambulance",
    ];

    $fields = parent::getCMSFields();
    $fields->removeFieldsFromTab('Root.Main', [
      'SortOrder', 'StoryPageID', 'Name', 'Email', 'PhoneNumber', 'Title', 'Date', 'Content',
      'ImageID', 'VideoLink', 'Image', 'Image2', 'Image3', 'Image4', 'MissionID'
    ]);
    $fields->addFieldsToTab("Root.Main", [
      DropdownField::create('Subsite', 'Belongs to subsite', $subsite)
        ->setEmptyString('-- select a subsite --'),
      DropdownField::create('MissionID', 'Belongs to mission', Article::get()->filter('Type', 'Mission')->map('ID', 'Title'))
      ->setEmptyString('-- select a mission --'),
      CheckboxField::create('Display', 'Display Story on Site?'),

      TextField::create('Title'),
      DateField::create('Date'),
      HTMLEditorField::create('Content')
        ->setDescription('Provide as much information as necessary.'),
      UploadField::create('Image', 'Display Image'),
      UploadField::create('Image2'),
      UploadField::create('Image3'),
      UploadField::create('Image4'),
      TextField::create('VideoLink')
    ]);

    $fields->addFieldsToTab('Root.Submitter', [
      ReadonlyField::create("Name"),
      ReadonlyField::create("Email"),
      ReadonlyField::create("PhoneNumber", 'Phone number'),
    ]);

    return $fields;
  }

  public function Link($inclStoryPage = false)
  {
    $segment = "$this->Subsite/stories/story/{$this->ID}";

    if ($inclStoryPage) {
      $storyPages = Story::get()->filter('ID', $this->ID);
      $storyPage = $storyPages
        ->first();

      if (!$storyPage) {
        $storyPage = $storyPages->first();
      }
    }
    return $segment;
  }

  public function validate()
  {
    $result = parent::validate();

    if (!$this->Date) {
      $result->addError('Date Required');
    }
    if (!$this->Title) {
      $result->addError('Title required');
    }
    if (!$this->Subsite) {
      $result->addError('Subsite Required');
    }

    return $result;
  }
}