<?php

namespace App\Models;

use App\Pages\HomePage;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\View\Parsers\ShortcodeParser;

class Article extends DataObject
{
  private static $table_name = "Articles";
  private static $singular_name = "Article";
  private static $plural_name = "Articles";

  private static $db = [
    'Title' => 'Text',
    'Date' => 'Date',
    'Type' => 'Text',
    'Content' => 'HTMLText',
    'SortOrder' => 'Int',
    'Subsite' => 'Text',
    'Latitude' => 'Text',
    'Longitude' => 'Text',
    'CarTime' => 'Text',
    'FlightTime' => 'Text',
  ];

  private static $has_one = [
    'Image' => Image::class,
    'DisplayOnMap' => HomePage::class
  ];

  private static $has_many = [
    'Stories' => Story::class
  ];

  private static $owns = [
    'Image'
  ];

  private static $default_sort = "Created DESC";

  private static $summary_fields = [
    'Title', 'Created', 'Date', 'Subsite', 'Type',
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

    $type = [
      'mission' => 'Mission',
      'article' => 'News Article'
    ];

    $fields = parent::getCMSFields();
    $fields->removeFieldsFromTab('Root.Main', [
      'SortOrder', 'Title', 'Date', 'Content', 'Image', 'Type', 'Latitude', 'Longitude', 'CarTime', 'FlightTime', 'DisplayOnMap'
    ]);
    $fields->addFieldsToTab("Root.Main", [
      TextField::create('Title'),
      DropdownField::create('Subsite', 'Belongs to Subsite', $subsite)
        ->setEmptyString('-- select a subsite --')
        ->setDescription('This links the mission to a subsite and sets it theme.  To display it on the map, check map display settings in the top right to set important configurations.'),
      DropdownField::create('Type', 'Article Type', $type)->setEmptyString('- select an article type -'),
      DateField::create('Date'),
      UploadField::create('Image'),
      HTMLEditorField::create('Content')
        ->setDescription('Provide as much information as necessary.'),
    ]);

    $fields->addFieldsToTab('Root.Map Display Settings', [
      DropdownField::create('DisplayOnMapID', 'Display on Map', HomePage::get()->map('ID', 'Title'))->setEmptyString('-- UNLINKED, select a map --')
        ->setDescription('Determines whether this mission will be displayed on the map, when the map is set to display Subsite Missions Only.  Select the correct map to display on.'),
      HeaderField::create('hfloction', 'Location on the Map'),
      TextField::create('Latitude'),
      TextField::create('Longitude'),
      TextField::create('FlightTime')
        ->setDescription('The amount of time taken to reach this destination by helicopter, as displayed on the map tooltip'),
      TextField::create('CarTime')
        ->setDescription('The amount of time taken to reach this destination by car, as displayed on the map tooltip'),
    ]);

    return $fields;
  }

  public function Link($incldArticlePage = false)
  {
    $segment = $this->Subsite . "/news/article/{$this->ID}";

    if ($incldArticlePage) {
      $articlePages = Article::get()->filter('ID', $this->ID);
      $articlePage = $articlePages
        ->first();

      if (!$articlePage) {
        $articlePage = $articlePages->first();
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
    if (!$this->Type) {
      $result->addError('Article Type Required');
    }
    if (!$this->Title) {
      $result->addError('Title required');
    }
    if (!$this->Subsite) {
      $result->addError('Subsite Required');
    }

    return $result;
  }


  public function dateFormatter($date, $format = 'd F y')
  {
    $dateParsed = new \DateTime($date);
    return date_format($dateParsed, $format);
  }

  public function teaser($input, $length = 200)
  {
    $input = ShortcodeParser::get_active()->parse($input);
    $input = strip_tags($input);
    $input = trim(html_entity_decode($input), " \t\n\r\0\x0B\xC2\xA0");

    if (strlen($input) <= $length)
      return $input;

    $parts = explode(" ", $input);

    while (strlen(implode(" ", $parts)) > $length)
      array_pop($parts);

    return implode(" ", $parts) . "...";
  }

  public function getArticleType()
  {
    if ($this->Type == 'mission') {
      return 'Mission';
    } else {
      return 'News Article';
    }
  }

  public function getArticleClass()
  {
    if ($this->Type == 'mission') {
      return 'mission';
    } else {
      return 'news';
    }
  }

  private function getPlaceholder()
  {
    if ($this->Image->URL) {
      $img = $this->Image->URL;
    } else {
      $img = '/themes/app/images/placeholders/' . $this->Subsite . '_placeholder.png';
    }
    return $img;
  }

  public function getModelData()
  {
    return [
      'id' => $this->ID,
      'title' => $this->Title,
      'type' => $this->getArticleType(),
      'link' => $this->Link(true),
      'articleClass' => $this->getArticleClass(),
      'image' => $this->getPlaceholder(),
      'date' => $this->dateFormatter($this->Date),
      'monthyear' => $this->dateFormatter($this->Date, 'm-Y'),
      'summary' => $this->teaser($this->Content)
    ];
  }
}
