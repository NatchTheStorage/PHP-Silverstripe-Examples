<?php

namespace App\Models;

use App\Pages\PropertySearchPage;
use App\Utils\Utilities;
use PHP_CodeSniffer\Generators\Text;
use SilverStripe\Assets\File;
use SilverStripe\Control\Director;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class Property extends DataObject
{
    private static $table_name = "Properties";
    private static $singular_name = "Property";
    private static $plural_name = "Properties";

    private static $db = [
        'ForceHide' => 'Boolean',
        'Active' => 'Boolean',
        'VaultID' => 'Varchar',             // id
        'VaultLifeID' => 'Varchar',         // saleLifeId or leaseLifeId
        'Type' => 'Varchar',                // based on what endpoint we're reaching
        'Category' => 'Varchar',               // class->internalName
        'Modified' => 'Datetime',           // modified
        'Status' => 'Varchar',              // status
        'SoldDate' => 'Datetime',           // date the sold status was set
        'ListedDate' => 'Datetime',         // inserted

        'Headline' => 'Varchar',            // heading
        'Description' => 'Text',            // description

        'PriceVisibility' => 'Varchar',     // ???
        'PriceDisplay' => 'Varchar',        // displayPrice
        'PriceOpinion' => 'Varchar',         // agentPriceOpinion - sale properties on auction only
        'PriceSearch' => 'Varchar',         // searchPrice - rent per week for rentals and total price for sales
        'PriceFilter' => 'Varchar',         // sale: agentPriceOpinion ?? searchPrice    rental: searchPrice    the price we will actually filter by

        'Rates' => 'Varchar',               // rates->council
        'RateableValue' => 'Varchar',       // rateableValue

        'AvailableFlag' => 'Boolean',       // calculating on import using date - used mainly for search stuff
        'AvailableDate' => 'Varchar',       // availableDate

        'AuctionDate' => 'Datetime',
        'AuctionLocation' => 'Varchar',
        'Auctioneer' => 'Varchar',

        'AddressVisibility' => 'Varchar',   // addressVisibility
        'AddressDisplay' => 'Varchar',      // displayAddress
        'AddressUnit' => 'Varchar',         // address->unitNumber
        'AddressLevel' => 'Varchar',        // address->level
        'AddressStreetNum' => 'Varchar',    // address->streetNumber
        'AddressStreet' => 'Varchar',       // address->street

        'SearchableSuburb' => 'Varchar',     // address->suburb->name   purely used for ease of matching on search
//        'SearchableCity' => 'Varchar',       // address->state->name    purely used for ease of matching on search

        'GeoLat' => 'Varchar',              // geolocation->latitude
        'GeoLon' => 'Varchar',              // geolocation->longitude
        'GeoAccuracy' => 'Varchar',         // geolocation->accuracy

        'FeatureGarages' => 'Int',          // garages OR carSpaces
        'FeatureBeds' => 'Int',             // bed
        'FeatureBathrooms' => 'Int',        // bath

        'AreaLand' => 'Int',               // landArea
        'AreaFloor' => 'Int',              // floorArea

        'SortOrder' => 'Int',

        'VideoLink' => 'Varchar',           // externalLinks->type=Video
        'VirtualTourLink' => 'Varchar',     // externalLinks->type=Virtual Tour

        "TPSLink" => "Varchar",

        'HasFloorPlan' => 'Boolean',        // custom property - checks if any of the photos are of type floor plan
        // if so, mark this as true, else false
        'BookViewing' => 'Varchar',         // custom property
        'FeaturedProperty' => 'Boolean',    // custom property - make this a featured property on the front page
    ];

    private static $default_sort = "SortOrder";

    private static $has_one = [
        "Suburb" => Suburb::class,          // address->suburb
    ];

    private static $has_many = [
        "Images" => VaultImage::class,
        "OpenHomes" => OpenHome::class,
        "Files" => VaultFile::class,
        "Tags" => Tag::class
    ];

    private static $many_many = [
        "StaffContact" => Person::class // contactStaff
    ];

    private static $many_many_extrafields = [
        'StaffContact' => [
            'Sort' => 'Int', // Required for all many_many relationships
        ]
    ];

    private static $owns = [
        "Images",
        "Files"
    ];

    private static $searchable_fields = [
        'AddressDisplay',
        'VaultID'
    ];

    private static $summary_fields = [
//        'generateAdminThumbnail' => 'Photo',
        'Created',
        'VaultID',
        'AddressDisplay',
        'FeaturedProperty',
        'Type',
        'PrettyActive' => 'Active',
        'ForciblyHidden' => 'Force Hidden?'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            "SortOrder", 'ForceHide', 'Active',
            'VaultID', 'Type', 'Category', 'Modified', 'Status', 'Headline', 'Description', 'PriceVisibility', 'PriceDisplay',
            'AddressVisibility', 'AddressDisplay', 'AddressUnit', 'AddressLevel', 'AddressStreetNum', 'AddressStreet',
            'SearchableSuburb', 'SearchableCity', 'GeoLat', 'GeoLon', 'GeoAccuracy', 'FeatureGarages', 'FeatureBeds',
            'FeatureBathrooms', 'AreaLand', 'AreaFloor', 'Feature', 'FeaturedProperty', 'SuburbID', 'CityID',
            'AvailableFlag', 'AvailableDate', 'OpenHomeDateTime', 'VirtualTourLink', 'VideoLink', 'PriceOpinion',
            'PriceSearch', 'BookViewing', 'Tags', 'TPSLink', 'HasFloorPlan'
        ]);

        $siteConfig = SiteConfig::current_site_config();
        $tpsLinks = [
            "" => "No Link",
            $siteConfig->HamiltonTPSLink => "Hamilton account",
            $siteConfig->PutaruruTPSLink => "Putaruru account"
        ];

        $fields->addFieldsToTab("Root.Main", [
            CheckboxField::create('ForceHide', 'Force Hide Property?'),
            CheckboxField::create('Active'),
            DropdownField::create("Type", "Property Type", [
                "buy" => "Buy",
                "rent" => "Rent",

            ]),
            DropdownField::create("Status", "Property status", [
                "available" => "available",
                "occupied" => "occupied",
                "sold" => "sold"
            ]),
            TextField::create('Headline'),
            TextareaField::create('Description'),
            CheckboxField::create('FeaturedProperty')
                ->setDescription('Make this a property that can be displayed as a featured property?'),

            LiteralField::create('lf4',
                '<h1>PRICES SECTION</h1><br>'),
            TextField::create('PriceDisplay', 'Displayed Price'),
            CheckboxField::create('PriceVisibility', 'Display price of property?'),
            ReadonlyField::create('PriceOpinion', "Agent's Price Opinion"),
            ReadonlyField::create('PriceSearch', 'Search Price'),

            ReadonlyField::create('VaultID'),
            LiteralField::create('lf3',
                '<h1>ADDRESS SECTION</h1><br>'),
            ReadonlyField::create('AddressDisplay'),
            ReadonlyField::create('AddressUnit'),
            ReadonlyField::create('AddressLevel'),
            ReadonlyField::create('AddressStreetNum'),
            ReadonlyField::create('AddressStreet'),
            LiteralField::create('lf5',
                '<h1>LOCATION/LAND SECTION</h1><br>'),
            TextField::create('GeoLat'),
            TextField::create('GeoLon'),
            ReadonlyField::create('AreaLand'),
            ReadonlyField::create('AreaFloor'),
            ReadonlyField::create('HasFloorPlan'),

            LiteralField::create('lf6',
                '<h1>AVAILABILITY SECTION</h1><br>'),
            ReadonlyField::create('AvailableFlag'),
            ReadonlyField::create('AvailableDate'),


            LiteralField::create('lf2',
                '<h1>AMENITIES SECTION</h1><br>'),
            TextField::create('FeatureGarages'),
            TextField::create('FeatureBeds'),
            TextField::create('FeatureBathrooms'),

            LiteralField::create('lf1',
                '<h1>LINKS SECTION</h1><br>'),

            TextField::create('BookViewing', 'Link to View Booking')
                ->setDescription('Please enter link with "www." on front:<br>Example: www.google.co.nz<br>If none provided, will default to https://wre.viewingtracker.com/'),

            DropdownField::create('TPSLink', 'TPS Application Link', $tpsLinks),
            TextField::create('VirtualTourLink', 'Link to Virtual Tour'),
            TextField::create('VideoLink', 'Link to Video'),

        ]);

        $fields->addFieldsToTab("Root.Tags", [
            GridField::create(
                "Accordions",
                "Accordions",
                $this->Tags(),
                GridFieldConfig_RecordEditor::create()
                    ->addComponent(new GridFieldOrderableRows("SortOrder")))
        ]);

        return $fields;
    }

    public function PrettyActive()
    {
        return $this->Active ? "yes" : "no";
    }

    public function ForciblyHidden()
    {
        return $this->ForceHide ? 'hidden' : 'not hidden';
    }

    public function OpenOrAvailable($full = false)
    {
        if ($this->Type === "buy") {
            $nextOpen = $this->OpenHomes()->filter("Start:GreaterThan", date("Y-m-d H:i:s"))->first();

            if ($nextOpen) {
                $startDate = \DateTime::createFromFormat("Y-m-d H:i:s", $nextOpen->Start);
                $endDate = \DateTime::createFromFormat("Y-m-d H:i:s", $nextOpen->End);
                if ($startDate) {
                    if ($full) {
                        return "Next open home: " . $startDate->format("D j M h:iA") .
                            ($endDate ? " - " . $endDate->format("h:iA") : "");
                    }
                    return "Open: " . $startDate->format("D j M");
                }
                return "";
            }
            return "";
        }

        $date = \DateTime::createFromFormat("Y-m-d", $this->AvailableDate);
        return $date ? "Available: " . $date->format("D j M") : "";
    }

    public function openHomeAddEvent($end = false)
    {
        $nextOpen = $this->OpenHomes()->filter("Start:GreaterThan", date("Y-m-d H:i:s"))->first();

        if ($nextOpen) {
            if (!$end) {
                $startDate = \DateTime::createFromFormat("Y-m-d H:i:s", $nextOpen->Start);
//                NOTE: american style dates
                return $startDate ? $startDate->format("m/d/Y h:i A") : "";
            } else {
                $endDate = \DateTime::createFromFormat("Y-m-d H:i:s", $nextOpen->End);
//                NOTE: american style dates
                return $endDate ? $endDate->format("m/d/Y h:i A") : "";
            }
        }
        return "";
    }

    public function ListedFormat()
    {
        $date = \DateTime::createFromFormat("Y-m-d H:i:s", $this->ListedDate);
        return $date ? "Listed: " . $date->format("D j M") : "";
    }

    public function AuctionTime()
    {
        $date = \DateTime::createFromFormat("Y-m-d H:i:s", $this->AuctionDate);
        return $date ? "Auction: " . $date->format("D j M h:iA") : "";
    }

    public function calculateBond()
    {
        return $this->PriceSearch ? "Bond: $" . $this->PriceSearch * 4 : "";
    }

    public function isAvailable()
    {
        if ($this->AvailableDate) {
            $date = date("Y-m-d");
            if ($date > $this->AvailableDate) {
                return true;
            }
        }
        return false;
    }

    public function firstFourPics()
    {
        return $this->Images()->limit(4);
    }

    public function Link($inclPropertyPage = false, $includeDomain = false)
    {
        $segment = "listing/{$this->VaultID}";


        if ($inclPropertyPage) {
            $propertyPages = PropertySearchPage::get();
            $propertyPage = $propertyPages
                ->first();

            if (!$propertyPage) {
                $propertyPage = $propertyPages->first();
            }

            $pageLink = $propertyPage->Link();

            $segment = $pageLink . $segment;
        }

        if ($includeDomain) {
            $domain = Director::absoluteBaseURL();
            $segment = rtrim($domain, '/') . $segment;
        }

        return $segment;
    }

    public function AbsoluteLink()
    {
        return Director::absoluteURL($this->Link(true));
    }

    public function GetTypeTitle()
    {
        if ($this->Status == 'Sold') {
            return 'Sold';
        } else if ($this->Type == 'buy') {
            return 'For Sale';
        } else if ($this->Type == 'rent') {
            return 'For Rent';
        }
        return 'Property';
    }

    public function RelatedProperties()
    {
        $initialProperties = self::get()->filter([
            "Type" => $this->Type,
            "Active" => 1,
            "ID:not" => $this->ID
        ]);


        // if this property ISN'T sold, we don't want to show sold properties as recommendations
        // if it IS sold, keep those sold ones in there
        if (!$this->isSold()) {
            $initialProperties = $initialProperties->exclude([
                'status' => 'sold'
            ]);
        }

        if ($initialProperties->count() < 1) {
            return [];
        }

        $filters = [];
        if ($this->Suburb) {
            $suburbIDs = [];
            if ($this->Suburb->CityID) {
                $suburbs = $this->Suburb->City->Suburbs();
                foreach ($suburbs as $suburb) {
                    $suburbIDs[] = $suburb->ID;
                }
            } else {
                $suburbIDs[] = $this->SuburbID;
            }
            $filters["SuburbID"] = $suburbIDs;
        }

        $relatedProperties = $initialProperties->filter($filters);
//        if we have between 2 and 4 properties after filter, just return those
        if ($relatedProperties->count() <= 4) {
            return $relatedProperties->count() >= 2 ? $relatedProperties : $initialProperties->limit(4);
        }

//        we have enough properties to keep filtering

        if (!$this->PriceFilter) {
            return $relatedProperties->limit(4);
        }

//        -$150k +$250k or -$100 +$150
        if ($this->Type == "buy") {
            $min = (int)$this->PriceFilter - 150000;
            $max = (int)$this->PriceFilter + 250000;
        } else {
            $min = (int)$this->PriceFilter - 100;
            $max = (int)$this->PriceFilter + 150;
        }

        $relatedProperties->filter([
            "PriceFilter:GreaterThanOrEqual" => $min,
            "PriceFilter:LessThanOrEqual" => $max,
        ]);
        return $relatedProperties->count() >= 1 ? $relatedProperties->limit(4) : $initialProperties->limit(4);
    }

    public function isSold()
    {
        return $this->Status == "sold";
    }

    public function PrettyPrice()
    {
        if ($this->Type === 'rent') {
            return "$" . number_format($this->PriceFilter, 0) . " per week";
        } else {
            return $this->PriceDisplay;
        }
    }

    public function PrettyAddress()
    {
        return str_replace(" NZ", "", $this->AddressDisplay);
    }

    public function getMainImage()
    {
        if ($this->images()->first()) {
            return $this->images()->first();
        } else {
            return null;
        }

    }

    public function getMainImageURL()
    {
        $domain = Director::absoluteBaseURL();
        $image = $this->getMainImage();
        if ($image) {
            $imageURL = $image->Url;

            return rtrim($domain, "/") . $imageURL;
        }
        return null;
    }

    public function returnParsedLink()
    {
        return Utilities::manipulateYoutubeLink($this->VideoLink);
    }

    public function FormattedDescription()
    {
        return html_entity_decode($this->Description);
    }

    public function RatesText()
    {
        return $this->Rates && $this->Rates !== "0.0" ? "Rates: $" . number_format($this->Rates, 0, '.', ',') : "";
    }

    public function CVText()
    {
        return $this->RateableValue && $this->RateableValue !== "0.0" ? "CV: $" . number_format($this->RateableValue, 0, '.', ',') : "";
    }

    public function canView($member = false)
    {
        return true;
    }

    public function canEdit($member = false)
    {
        return true;
    }

    public function canDelete($member = null)
    {
        return true;
    }

    public function canCreate($member = null, $context = [])
    {
        return true;
    }
}
