<?php

namespace App\Elements;

use App\Models\Staff;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\TextField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;


class TeamElement extends BaseElement
{
  private static $table_name = "TeamElement";
  private static $singular_name = 'Team Element';
  private static $plural_name = 'Team Elements';
  private static $description = 'Displays team members for the about us page';
  private static $icon = 'font-icon-block-content';

  private static $db = [
    'Title' => "Text",
    'DisplayTitle' => 'Text',
  ];

  private static $has_many = [
    'TeamMembers' => Staff::class
  ];

  private static $inline_editable = false;

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $fields->removeByName('TeamMembers');
    $fields->removeFieldsFromTab('Root.Main', [
      'Title', 'DisplayTitle'
    ]);

    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title')
        ->setDescription('This title is used for filtering/organisation.  Please do not change this one'),
      TextField::create('DisplayTitle', 'Display Title')
        ->setDescription('The title displayed on the page, you can change this one.  If left empty, it will default to TEAM'),

      GridField::create('TeamMembers', 'Team Members', $this->TeamMembers(), GridFieldConfig_RecordEditor::create()
        ->addComponent(new GridFieldOrderableRows("SortOrder"))
      )
    ]);

    return $fields;
  }

  public function GetTeam() {
    return $this->TeamMembers()->sort('SortOrder', 'ASC');
  }

  public function getType()
  {
    return self::$singular_name;
  }
}
