<?php

namespace App\Models;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

class Tag extends DataObject {
    private static $table_name = "Tags";
    private static $singular_name = "Tag";
    private static $plural_name = "Tags";

    private static $db = [
        "Title" => "Text",
        "Colour" => "Varchar",
        "SortOrder" => "Int"
    ];

    private static $has_one = [
        "Property" => Property::class
    ];

    private static $default_sort = "SortOrder ASC";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            "PropertyID",
            "SortOrder"
        ]);

        $colours = [
            "tag--red" => "Red",
            "tag--green" => "Green",
        ];

        $fields->addFieldsToTab("Root.Main", [
            TextField::create("Title", "Title"),
            DropdownField::create('Colour', 'Colour', $colours)
        ]);

        return $fields;
    }

    public function canView($member = null)
    {
        return Permission::check('EDIT_DATAOBJECTS', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('EDIT_DATAOBJECTS', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('EDIT_DATAOBJECTS', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('EDIT_DATAOBJECTS', 'any', $member);
    }
}
