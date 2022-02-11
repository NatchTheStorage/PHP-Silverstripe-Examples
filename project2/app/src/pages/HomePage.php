<?php

namespace App\Pages;

use App\Controllers\HomePageController;
use App\Models\HomeCTA;
use Page;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class HomePage extends Page
{
    private static $table_name = "Homepage";
    private static $singular_name = "Homepage";
    private static $plural_name = "Homepages";
    private static $controller_name = HomePageController::class;

    private static $db = [
        "BannerTitle" => "Varchar(100)",
        "VideoLink"=> "Varchar(200)"
    ];

    private static $has_one = [
        "BannerBackground" => Image::class
    ];

    private static $has_many = [
        "CTAs" => HomeCTA::class
    ];

    private static $owns = [
        "BannerBackground"
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName("Banner");

        $fields->addFieldsToTab('Root.Main', [
            TextField::create("VideoLink", "Video Link")
                ->setDescription("Add a Youtube Video link here!"),
            TextField::create("BannerTitle")->addExtraClass("stacked"),
            UploadField::create("BannerBackground")->addExtraClass("stacked"),
            GridField::create(
                "CTAs",
                "CTAs",
                $this->CTAs(),
                GridFieldConfig_RecordEditor::create()
                    ->addComponent(new GridFieldOrderableRows("SortOrder"))),
            HTMLEditorField::create("Content")->addExtraClass("stacked")
        ], 'Metadata');

        return $fields;
    }

    public function isHome()
    {
        return true;
    }
}