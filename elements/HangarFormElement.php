<?php

namespace App\Elements;

use App\Models\SubmissionHangar;
use App\Pages\HomePage;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Email\Email;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;


class HangarFormElement extends BaseElement
{
  private static $table_name = "HangarFormElement";
  private static $singular_name = 'Hangar Form Element';
  private static $plural_name = 'Hangar Form Elements';
  private static $description = 'Contains the form and content skeleton for a hangar visit';
  private static $icon = 'font-icon-block-content';

  private static $db = [
    'HangarContent' => "HTMLText",
  ];

  private static $inline_editable = false;

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $fields->removeFieldsFromTab('Root.Main', [
      'HangarContent', 'Title'
    ]);
    $fields->addFieldsToTab("Root.Main", [

    ]);

    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title'),
      HTMLEditorField::create('HangarContent', 'Hangar Text Content')
        ->setDescription('Text content for the hangar section goes here')
    ]);

    return $fields;
  }

  public function getType()
  {
    return self::$singular_name;
  }

  public function HangarForm()
  {

    $req = Controller::curr()->getRequest();

    if ($req->isPOST()) {
      $this->submitHangarForm($req);
    }

    $fields = new FieldList(
      TextField::create("Name")
        ->addExtraClass('hangarvisitform-name')
        ->setAttribute('placeholder', 'Name'),
      EmailField::create("Email")
        ->addExtraClass('hangarvisitform-email')
        ->setAttribute('placeholder', 'Email'),
      TextField::create("Organisation")
        ->addExtraClass('hangarvisitform-organisation')
        ->setAttribute('placeholder', 'Organisation'),
      DateField::create('Date')
        ->addExtraClass('hangarvisitform-date')
    );

    $actions = new FieldList(
      FormAction::create("submitHangar", "Book Now")
        ->addExtraClass('hangarvisitform-submit')
    );

    $requiredFields = new RequiredFields([
      'Name',
      'Email',
      'Organisation',
      'Date'
    ]);

    $form = new Form(Controller::curr(), 'HangarForm', $fields, $actions, $requiredFields);
    $form->setTemplate('themes/app/templates/App/Forms/FormHangarVisit.ss');

    $form->enableSpamProtection();

    return $form;
  }

  public function submitHangarForm($req)
  {
    $data = $req->PostVars();
    $submission = new SubmissionHangar();
    $submission->Name = $data['Name'];
    $submission->Email = $data['Email'];
    $submission->Organisation = $data['Organisation'];
    $submission->Date = $data['Date'];
    $submission->Subsite = $this->controller::curr()->init();

    $submission->write();

    $to = HomePage::get()->filter('Theme', $this->controller::curr()->init())->first()->HelicopterFormEmail;
    $bcc = "forms@baa.co.nz";

    $email = Email::create();
    $email->setTo($to)
      ->setBCC($bcc)
      ->setSubject('New Hangar Visit Enquiry')
      ->setData($submission)
      ->setHTMLTemplate('EmailHangar');

    $email->send();

    $request = Injector::inst()->get(HTTPRequest::class);
    $session = $request->getSession();
  }
}
