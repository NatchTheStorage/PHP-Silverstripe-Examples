<?php

namespace {

  use App\Models\SubmissionNewsletter;
  use App\Pages\HomePage;
  use SilverStripe\CMS\Controllers\ContentController;
  use SilverStripe\Control\Email\Email;
  use SilverStripe\Control\HTTPRequest;
  use SilverStripe\Core\Injector\Injector;
  use SilverStripe\Forms\EmailField;
  use SilverStripe\Forms\FieldList;
  use SilverStripe\Forms\Form;
  use SilverStripe\Forms\FormAction;
  use SilverStripe\Forms\RequiredFields;
  use SilverStripe\Forms\TextField;
  use SilverStripe\SiteConfig\SiteConfig;
  use SilverStripe\View\Requirements;

  class PageController extends ContentController
  {
    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * [
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * ];
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = [
      "NewsletterForm", "HangarForm"
    ];

    private $successful = False;
    private $currentSubsite = '';
    private $newsletterSubsite = '';
    const CSS_PATH = 'themes/app/css/dist/';

    protected function init()
    {
      parent::init();

      $request = Injector::inst()->get(HTTPRequest::class);
      $session = $request->getSession();
      if ($session->get('Newsletter') === 'Success') {
        $this->successful = True;
        $session->clear('Newsletter');
      }

      // this code checks which theme the home page uses, then give it its colours
      $homePage = $this;

      while ($homePage->ClassName != HomePage::class && $homePage->ParentID) {
        $homePage = $homePage->Parent();
      }

      if ($homePage->Theme === "Waikato") {
        return $this->CheckSubsite($homePage->Theme, 'Waikato Westpac');
      }
      else if ($homePage->Theme === "TECT" ||
        $homePage->Theme === "Greenlea" ||
        $homePage->Theme === "Palmerston" ||
        $homePage->Theme === "Westpac") {
        return $this->CheckSubsite($homePage->Theme, $homePage->Theme);
      }
      else
        return null;
    }

    private function CheckSubsite($theme, $newsletterSubsite) {
      Requirements::css(self::CSS_PATH . strtolower($theme) . '.css');
      $this->currentSubsite = $theme;
      $this->newsletterSubsite = $newsletterSubsite;
      return $theme;
    }

    public function currentSubsite() {
      return $this->currentSubsite;
    }

    public function newsletterSubsite()
    {
      return $this->newsletterSubsite;
    }

    public function formSuccess()
    {
      return $this->successful;
    }

    public function NewsletterForm()
    {
      $fields = new FieldList(
        TextField::create('Name')
          ->addExtraClass('newsletter-name')
          ->setAttribute('placeholder', 'Name'),
        TextField::create('Organisation')
          ->addExtraClass('newsletter-organisation')
          ->setAttribute('placeholder', 'Organisation'),
        EmailField::create('Email')
          ->addExtraClass('newsletter-email')
          ->setAttribute('placeholder', 'Email'),
      );

      $actions = new FieldList(
        FormAction::create('submitNewsletter', 'SEND')
          ->addExtraClass('newsletter-send')
      );

      $requiredFields = new RequiredFields([
        'Name',
        'Email'
      ]);

      $form = new Form($this, 'NewsletterForm', $fields, $actions, $requiredFields);
      $form->setTemplate('themes/app/templates/App/Forms/FormNewsletter.ss');

      $form->enableSpamProtection();

      $form->currentSubsite = $this->newsletterSubsite();

      return $form;
    }

    public function submitNewsletter($data, Form $form)
    {
      $submission = new SubmissionNewsletter();
      $form->saveInto($submission);
      $submission->write();

      $to = SiteConfig::current_site_config()->ContactFormEmail;
      $bcc = "forms@baa.co.nz";

      $email = Email::create();
      $email->setTo($to)
        ->setBCC($bcc)
        ->setSubject('New Newsletter Sign-Up')
        ->setData($submission)
        ->setHTMLTemplate('EmailNewsletter');

      $email->send();

      $form->sessionMessage("You've been signed up for our newsletter!", 'good');

      return $this->redirectBack();
    }
  }
}
