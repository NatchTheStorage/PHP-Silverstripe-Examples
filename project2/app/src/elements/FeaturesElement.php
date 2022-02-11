<?php

namespace App\Elements;

use App\Models\Feature;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

class FeaturesElement extends BaseElement {
    private static $table_name = "FeaturesElement";
    private static $singular_name = "Features Element";
    private static $plural_name = "Features Elements";
    private static $inline_editable = false;
    private static $icon = 'font-icon-block-carousel';

    private static $db = [
        'DisplayMode' => 'Text',
    ];

    private static $has_many = [
        'Features' => Feature::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName("Features");
        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create('DisplayMode', 'Display Mode', [
                '0'=>'Default Mode',
                '1'=>'Mobile List'
            ])->setEmptyString('- choose an option -')->setDescription('Default Mode means that features will be displayed as a slider on Mobile View,
                and as a list on Tablet and Desktop View.  Mobile List means that the features will be displayed in list
                form on Mobile view, similar to tablet and desktop instead of in a slider'),
            GridField::create('Features', 'Features', $this->Features(),
                GridFieldConfig_RecordEditor::create(10)
                    ->addComponent(new GridFieldSortableRows('SortOrder')))
        ]);


        return $fields;
    }

    public function getType()
    {
        return self::$singular_name;
    }
}