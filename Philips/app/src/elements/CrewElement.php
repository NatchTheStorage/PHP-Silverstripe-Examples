<?php

namespace App\Elements;

use App\Models\Crew;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;


class CrewElement extends BaseElement
{
  private static $table_name = "CrewElement";
  private static $singular_name = 'Crew Element';
  private static $plural_name = 'Crew Elements';
  private static $description = 'Displays crew members for the about us page';
  private static $icon = 'font-icon-block-content';

  private static $db = [
    'Title' => "Text",
    'DisplayTitle' => 'Text',
    'PilotsTitle' => 'Text',
    'PilotsDescription' => 'Text',
    'ParamedicsTitle' => 'Text',
    'ParamedicsDescription' => 'Text',
    'CrewmanTitle' => 'Text',
    'CrewmanDescription' => 'Text',
    'OperationsTitle' => 'Text',
    'OperationsDescription' => 'Text',
    'BoardsTitle' => 'Text',
    'BoardsDescription' => 'Text'
  ];

  private static $has_many = [
    'Crew' => Crew::class
  ];

  private static $inline_editable = false;

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $fields->removeByName('Crew');
    $fields->removeFieldsFromTab('Root.Main', [
      'Title', 'ParamedicsTitle', 'ParamedicsDescription', 'PilotsTitle', 'PilotsDescription', 'CrewmanTitle', 'CrewmanDescription',
      'OperationsTitle', 'OperationsDescription', 'BoardsTitle', 'BoardsDescription', 'DisplayTitle'
    ]);

    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title')
        ->setDescription('This title is used for filtering/organisation.  Please do not change this one'),
      TextField::create('DisplayTitle', 'Display Title')
        ->setDescription('The title displayed on the page, you can change this one.  If left empty, it will default to CREW'),

      HeaderField::create('hf1', 'Pilots Section'),
      TextField::create('PilotsTitle', 'Pilots Section Title'),
      TextareaField::create('PilotsDescription', 'Pilots Section Description'),

      HeaderField::create('hf2', 'Crewman Section'),
      TextField::create('CrewmanTitle', 'Crewman Section Title'),
      TextareaField::create('CrewmanDescription', 'Crewman Section Description'),

      HeaderField::create('hf3', 'Paramedics Section'),
      TextField::create('ParamedicsTitle', 'Paramedics Section Title'),
      TextareaField::create('ParamedicsDescription', 'Paramedics Section Description'),

      HeaderField::create('hf4', 'Operations Team Section'),
      TextField::create('OperationsTitle', 'Operations Team Section Title'),
      TextareaField::create('OperationsDescription', 'Operations Section Description'),

      HeaderField::create('hf5', 'Board Members Section'),
      TextField::create('BoardsTitle', 'Board Members Section Title'),
      TextareaField::create('BoardsDescription', 'Board Members Section Description'),

      GridField::create('Crew', 'Crew', $this->Crew(), GridFieldConfig_RecordEditor::create()
        ->addComponent(new GridFieldOrderableRows("SortOrder"))
      )
    ]);

    return $fields;
  }

  public function GetPilots() {
    return $this->Crew()->filter('CrewType','Pilot')->sort('SortOrder', 'ASC');
  }

  public function GetCrewmans() {
    return $this->Crew()->filter('CrewType','Crewman')->sort('SortOrder', 'ASC');
  }

  public function GetParamedics() {
    return $this->Crew()->filter('CrewType','Paramedic')->sort('SortOrder', 'ASC');
  }

  public function GetBoards() {
    return $this->Crew()->filter('CrewType','Board')->sort('SortOrder', 'ASC');
  }

  public function GetOperations() {
    return $this->Crew()->filter('CrewType','Operations')->sort('SortOrder', 'ASC');
  }


  public function getType()
  {
    return self::$singular_name;
  }
}
