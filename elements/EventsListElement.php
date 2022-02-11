<?php

namespace App\Elements;

use App\Models\Event;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Control\Director;
use SilverStripe\Forms\TextField;

class EventsListElement extends BaseElement
{
  private static $table_name = "EventsListElements";
  private static $singular_name = 'Events List Element';
  private static $plural_name = 'Events List Elements';
  private static $description = 'Shows a few events';
  private static $icon = 'font-icon-block-content';

  private static $db = [
    'Title' => 'Text'
  ];

  private static $inline_editable = false;

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->removeFieldsFromTab('Root.Main', [
      'Title', 'Content'
    ]);
    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title')
    ]);

    return $fields;
  }

  public function getType()
  {
    return self::$singular_name;
  }

  public function GetEventsMobileTablet() {
    return Event::get()->limit(2)->filter('Subsite', $this->getSubsite());
  }
  public function GetEventsDesktop() {
    return Event::get()->limit(3)->filter('Subsite', $this->getSubsite());
  }

  public function getSubsite($lowercase = null) {
    $page = Director::get_current_page();
    if ($lowercase)
      return strtolower($page->getParent()->Theme);
    return $page->getParent()->Theme;
  }
}