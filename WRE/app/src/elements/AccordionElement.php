<?php

namespace App\Elements;

use App\Models\Accordion;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class AccordionElement extends BaseElement
{
    private static $table_name = "AccordionElement";
    private static $singular_name = "Accordion Element";
    private static $plural_name = 'Accordion Elements';
    private static $inline_editable = false;
    private static $icon = 'font-icon-block-accordion';

    private static $has_many = [
        'Accordions' => Accordion::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            "Accordions"
        ]);

        $fields->addFieldsToTab("Root.Main", [
            GridField::create(
                "Accordions",
                "Accordions",
                $this->Accordions(),
                GridFieldConfig_RecordEditor::create()
                    ->addComponent(new GridFieldOrderableRows("SortOrder")))
        ]);

        return $fields;
    }

    public function getType()
    {
        return self::$singular_name;
    }
}