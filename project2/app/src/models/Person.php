<?php

namespace App\Models;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class Person extends DataObject
{
    private static $table_name = "People";
    private static $singular_name = "Person";
    private static $plural_name = "People";

    private static $db = [
        'VaultID' => 'Varchar',         // id
        'FirstName' => 'Varchar',       // firstName
        'LastName' => 'Varchar',        // lastName
        'Username' => 'Varchar',        // username
        'Role' => 'Varchar',            // role
        'Email' => 'Varchar',           // email
        'Position' => 'Varchar',        // position
        'PhoneMobile' => 'Varchar',     // number [type] => Mobile
        'Description'=> 'Text',          // CUSTOM, gives description of person
        'Inactive' => 'Boolean',
        'SortOrder' => 'Int',
    ];

    private static $has_one = [
        "Photo" => VaultImage::class,   // photo->original
        'CMSPhoto' => Image::class,     // CMS photo, which should override the vault

    ];

    private static $has_many = [

    ];

    private static $many_many = [
        "Offices" => OfficeLocation::class,
    ];

    private static $belongs_many_many = [
        "Properties" => Property::class
    ];

    private static $many_many_extraFields = [
        'Offices' => [
            'Sort' => 'Int' // Required for all many_many relationships
        ],
    ];

    private static $owns = [
        'Photo',
        'CMSPhoto'
    ];

    private static $default_sort = 'SortOrder';

    private static $summary_fields = [
        'generateAdminThumbnail' => 'Photo',
        'ID' => 'ID',
        'FullName' => 'Full Name',
        'Email',
        'Role',
        'Position',
        'PhoneMobile' => 'Mobile',
        'PrettyInactive' => 'Status'
    ];

    private static $searchable_fields = [
        'FirstName',
        'LastName',
        'Email',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(["SortOrder", "Photo", "showOnWebSite", "Office", 'VaultID', 'FirstName',
            'LastName', 'Username', 'Role', 'Email', 'Position', 'PhoneMobile', 'Description', 'CMSPhoto']);

        $fields->addFieldsToTab('Root.Main', [
            ReadonlyField::create('VaultID'),
            TextField::create('FirstName'),
            TextField::create('LastName'),
            TextField::create('Username'),
            DropdownField::create("Role", "Staff Role", [
                "residentialSales" => "Residential Sales",
                "propertyManagement" => "Property Manager",
                "frontoffice" => "Front Office",
                "management" => "Management"
            ])->setEmptyString('-- select a role --'),
            TextField::create('Position'),
            UploadField::create('Photo', 'Vault Photo')
                ->setFolderName('agents')
                ->setDescription('This is the photo automatically uploaded from the Vault'),
            UploadField::create('CMSPhoto')
                ->setDescription('You can upload and use any image here.
                TAKE NOTE, if this field is used, it will override the Vault Photo field above'),
            TextField::create('Email'),
            TextField::create('PhoneMobile'),
            TextareaField::create('Description'),

        ]);
//        $fields->addFieldToTab('Root.Main',
//            DropdownField::create("PrimaryOfficeID", "Primary Office",
//                OfficeLocation::get()->map("ID", "Title"))->setEmptyString("Please select")
//        );
        $fields->addFieldToTab('Root.Main',
            CheckboxSetField::create('Offices', 'Offices', OfficeLocation::get()->map())
        );

        return $fields;
    }

    public function getFullName(): string
    {
        return $this->FirstName . " " . $this->LastName;
    }

    public function getTitle()
    {
        return $this->getFullName();
    }

    public function getPrettyInactive()
    {
        return $this->Inactive ? 'INACTIVE' : '-';
    }

    public function generateAdminThumbnail()
    {
        if ($this->CMSPhoto()->exists()) {
            return $this->CMSPhoto()
                ->Fill(150, 110);
        }
        else if ($this->Photo()->exists()) {
            return $this->Photo()
                ->Fill(150, 110);
        }
        else {
            return '';
        }
    }

    public function scaledProfileImage()
    {
        return $this->scaledImage(400);
    }

    public function scaledPropertyImage()
    {
        return $this->scaledImage(120);
    }

    public function scaledImage($size)
    {
        if ($this->CMSPhoto()->exists()) {
            return $this->CMSPhoto()
                ->ScaleWidth($size)->URL;
        }
        else if ($this->Photo()->exists()) {
            return $this->Photo()
                ->ScaleWidth($size)->URL;
        }
        else {
            return 'app/images/PeoplePlaceholder.svg';
        }
    }

    public function getMyListingsLink()
    {
        switch ($this->Role) {
            case 'propertyManagement':
                return "/rent/?staff_id={$this->ID}";
                break;
            case 'residentialSales':
                return "/buy/?staff_id={$this->ID}";
                break;
            default:
                return '';
                break;
        }
    }

    public function getMySalesLink()
    {
        switch ($this->Role) {
            case 'residentialSales':
                return "/buy/?type=sold&staff_id={$this->ID}";
                break;
            default:
                return '';
                break;
        }
    }

    public function getOffices() {
        $officeString = "";
        foreach($this->Offices() as $i => $office) {
            if ($i > 0) {
                $officeString = $officeString . ', ';
            }
            $officeString = $officeString . $office->Title;
        }
        return $officeString;
    }
}
