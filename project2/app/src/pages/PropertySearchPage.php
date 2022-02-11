<?php

namespace App\Pages;

use App\Controllers\PropertySearchPageController;
use Page;
use SilverStripe\Forms\DropdownField;

class PropertySearchPage extends Page
{
    private static $table_name = "PropertySearchPages";
    private static $singular_name = "Property Search Page";
    private static $plural_name = "Property Search Pages";
    private static $controller_name = PropertySearchPageController::class;

    private static $db = [
        "Type" => "Varchar(30)"
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Content');

        $fields->addFieldsToTab("Root.Main", [
            DropdownField::create("Type", "Listing Type", [
                "buy" => "Buy",
                "rent" => "Rent",
                "All" => "All"
            ])->setEmptyString("-- select one --")
        ], "Metadata");

        return $fields;
    }
}