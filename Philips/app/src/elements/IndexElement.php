<?php

namespace App\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

class IndexElement extends BaseElement {
  private static $table_name = "IndexElement";
  private static $singular_name = "Index Element";
  private static $plural_name = "Index Elements";

  private static $inline_editable = false;
  private static $icon = 'font-icon-thumbnails';


  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->removeFieldsFromTab('Root.Main', [
      'Title', 'Content', 'ShowTitle'
    ]);
    return $fields;
  }

  public function getType()
  {
    return self::$singular_name;
  }

  public function Index()
  {
    // First, grab all elements in the same ElementalArea that this element is in
    $allElements = BaseElement::get()->filter("ParentID", $this->ParentID);

    // Verify that every element has a title to show
    $showElements = [];
    foreach ($allElements as $element) {
      if ($element->IndexTitle && $element->ID !== $this->ID) {
        $showElements [] = new ArrayData ([
          "Title" => $element->IndexTitle,
          "IDOnPage" => "e{$element->ID}"
        ]);
      }
    }

    // Return it
    return new ArrayList ($showElements);
  }
}
