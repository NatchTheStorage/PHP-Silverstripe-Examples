<?php

namespace App\Pages;

use App\Controllers\HomePageController;
use App\Models\Article;
use Page;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class HomePage extends Page
{
  private static $table_name = "Homepage";
  private static $singular_name = "Homepage";
  private static $plural_name = "Homepages";
  private static $controller_name = HomePageController::class;

  private static $db = [
    "Theme" => "Varchar(50)",
    'MapMode' => 'Text',
    'MapTitle' => 'Text',
    'Latitude' => 'Text',
    'Longitude' => 'Text',
    'LatitudeIcon' => 'Text',
    'LongitudeIcon' => 'Text',
    'SocialFacebook' => 'Text',
    'SocialYoutube' => 'Text',
    'SocialInstagram' => 'Text',
    'SocialLinkedIn' => 'Text',
    'ContactFormEmail' => 'Text',
    'HelicopterFormEmail' => 'Text',
    'MediaHubEmail' => 'Text',
    'SubmitDetailsEmail' => 'Text',
    'ContactPhoneNumber' => 'Text',
    'CharityNumber' => 'Text'
  ];

  private static $has_one = [
    "Image" => Image::class,
    "MapImage" => Image::class
  ];

  private static $has_many = [
    "Missions" => Article::class
  ];

  private static $owns = [
    'Image',
    'MapImage'
  ];

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $themes = [
      "" => "None",
      "Waikato" => "Waikato Westpac Rescue Helicopter",
      "TECT" => "TECT Rescue Helicopter",
      "Greenlea" => "Greenlea Rescue Helicopter",
      "Palmerston" => "Palmerston North Rescue Helicopter",
      "Westpac" => "Westpac Air Ambulance",
    ];

    $fields->removeFieldsFromTab('Root.Main', [
      'RemoveFromHeader', 'AddToFooter'
    ]);

    $fields->addFieldsToTab("Root.Main", [
      DropdownField::create('Theme', "Theme", $themes),
      UploadField::create('Image'),
    ]);

    $fields->addFieldsToTab('Root.Contact Details', [
      HeaderField::create('hfcontacts', 'Contact Details')
        ->setDescription('Contact information in the footer is entered here for each subsite'),
      TextField::create('ContactFormEmail', 'Contact Form / General Email')
      ->setDescription('Contact form submissions are sent here'),
      TextField::create('HelicopterFormEmail', 'Hangar Enquiry Email')
        ->setDescription('Hangar Enquiry submissions are sent here'),
      TextField::create('MediaHubEmail', 'Media Hub Email')
      ->setDescription('Media Hub access requests are sent here'),
      TextField::create('SubmitDetailsEmail', 'Ambulance Form Email')
      ->setDescription('Only used on Westpac Air Ambulance, can be left empty for other subsites'),
      TextField::create('ContactPhoneNumber', 'Contact Phone Number'),
      TextField::create('CharityNumber', 'Charity Number'),
    ]);

    $fields->addFieldsToTab('Root.Map Settings', [
      DropdownField::create('MapMode', 'Map Mode', [
        0=>"Subsite Missions Only", 1=>"Image Mode (Westpac)"])
        ->setDescription('Subsite missions only shows missions/bases belonging to this subsite (default), Image Mode requires a static image to be uploaded in the field further down.'),
      TextField::create('MapTitle', 'Map Title'),
      HeaderField::create('hflocation', 'Location of Base')
        ->setDescription('This is the location of the Helicopter home base, as displayed on the map'),
      TextField::create('Latitude'),
      TextField::create('Longitude'),
      HeaderField::create('hficon', 'Location of Icon')
        ->setDescription('This is the location of the decorative helicopter icon placed next to the base'),
      TextField::create('LatitudeIcon', 'Latitude'),
      TextField::create('LongitudeIcon', 'Longitude'),
      UploadField::create('MapImage', 'Map Image')
      ->setDescription('This will only be used if the Map Mode is set to Image Mode')
    ]);

    $fields->addFieldsToTab('Root.Socials', [
      HeaderField::create('hfsocials', 'Social Links')
        ->setDescription('Social media links for this subsite are added here'),
      TextField::create('SocialFacebook', 'Facebook Link'),
      TextField::create('SocialYoutube', 'Youtube Link'),
      TextField::create('SocialInstagram', 'Instagram Link'),
      TextField::create('SocialLinkedIn', 'LinkedIn Link'),
    ]);

    $fields->addFieldsToTab('Root.Displayed Missions', [
      GridField::create('Missions', 'Missions on Display', $this->Missions(), GridFieldConfig_RelationEditor::create()
        ->addComponent(new GridFieldOrderableRows("SortOrder")))
    ]);

    return $fields;
  }

  public function HomePage()
  {
    return true;
  }

  public function getThis() {
    return $this;
  }

  public function getThisMissions() {
    return Article::get()->filter('Subsite', $this->Theme);
  }

  public function getLinkedMissions() {
    return $this->Missions();
  }
}