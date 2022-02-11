<?php

namespace {
    use App\Models\City;
    use App\Models\HeaderContact;
    use App\Models\Property;
    use App\Models\SubmissionAppraisal;
    use App\Models\FooterAward;
    use App\Models\OfficeLocation;
    use App\Models\Suburb;
    use App\Utils\Utilities;
    use Psr\SimpleCache\CacheInterface;
    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\Control\Controller;
    use SilverStripe\Control\Email\Email;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\Core\Environment;
    use SilverStripe\Core\Injector\Injector;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\DateField;
    use SilverStripe\Forms\EmailField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\Form;
    use SilverStripe\Forms\FormAction;
    use SilverStripe\Forms\LiteralField;
    use SilverStripe\Forms\OptionsetField;
    use SilverStripe\Forms\RequiredFields;
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\ValidationException;
    use SilverStripe\Security\PermissionProvider;
    use SilverStripe\SiteConfig\SiteConfig;

    class PageController extends ContentController implements PermissionProvider
    {
        /**
         * @var array
         */
        private static $allowed_actions = [
            "AppraisalForm",
            "Suburbs",
            "Cities",
            "PropertyCounts",
            "ClearCache",
            "PriceOptions"
        ];

        private static $url_handlers = [
            'locations/cities' => 'Cities',
            'locations/suburbs' => 'Suburbs',
            'locations/propertyCounts' => 'PropertyCounts',
            'locations/clearCache' => 'ClearCache'
        ];

        protected function init()
        {
            parent::init();
        }

        public function providePermissions()
        {
            return [
                "EDIT_DATAOBJECTS" => "Create, edit and delete DataObjects such as Accordion Items, Office Locations, etc"
            ];
        }

        public function footerLocations()
        {
            return OfficeLocation::get();
        }

        public function headerContacts()
        {
            return HeaderContact::get();
        }

        public function footerAwards()
        {
            return FooterAward::get();
        }

        public function listing(HTTPRequest $request)
        {
            $property = Utilities::DisplayableProperties($request->allParams()["ID"])->first();

            if (!$property) {
                // no property was found redirect to home;
                return Controller::curr()->redirect("/");
            }
            return [
                "Property" => $property,
            ];
        }

        // Sets up the appraisal form and submits the contents
        public function AppraisalForm()
        {
            /* Creates all the fields on needed for the Appraisal Form */
            $form = Form::create(
                $this,
                __FUNCTION__,
                FieldList::create(
                    OptionsetField::create('AppraisalType', 'Sales', array('1' => 'Sales', '2' => 'Rental'))
                        ->setTemplate('Includes/Modals/RadioTemplate'),
                    TextField::create('AName', "Owner's Name")->addExtraClass('apform__fields-large'),


                    EmailField::create('Email', 'Email')->addExtraClass('apform__fields-medium'),
                    TextField::create("PhoneNumber", "Phone Number")->addExtraClass('apform__fields-small'),

                    TextField::create('Address', 'Property Address')->addExtraClass("apform__fields-medium"),
                    TextField::create('City', 'City')->addExtraClass('apform__fields-small'),

                    TextareaField::create('Comments', 'Comments')->addExtraClass('apform__fields-large'),

                    LiteralField::create("literalField", '<div class="apform__checkbox-title"><p class="legend text-default">Preferred Contact Method</p></div>'),

                    CheckboxField::create('ContactEmail', 'Email')->addExtraClass('apform__checkbox'),
                    CheckboxField::create('ContactPhone', 'Phone')->addExtraClass('apform__checkbox'),



                    TextField::create("Name")
                        ->setAttribute('tabindex', -1)
                        ->setAttribute('autocomplete', 'no-google-autofill')
                        ->addExtraClass('hidden-field hidden-label-field')
                ),
                FieldList::create(
                    FormAction::create('SubmitAppraisal', 'Send')
                        ->addExtraClass('c-button apform-actionsbutton')
                ),
                RequiredFields::create([
                    'AppraisalType',
                    'Address',
                    'AName',
                    'Email',
                    'PhoneNumber'
                ])
            );

            $form->addExtraClass('legend');

            $form->enableSpamProtection()
                ->fields()->fieldByName('Captcha');
            return $form;
        }
        public function SubmitAppraisal($data, Form $form)
        {
            if ($this->emptySpamField($data)) {
                $submission = SubmissionAppraisal::create();
                $form->saveInto($submission);
                $submission->write();

                //Send email with data.
                $toRecord = SiteConfig::current_site_config()->AppraisalFormEmail;
                $toOutgoing = $submission->Email;
                $from = Environment::getEnv('APP_SMTP_USERNAME');
                $bcc = "forms@baa.co.nz";

                // This email goes out to the user who filled in the form
                $email = new Email();
                $email->setTo($toOutgoing)
                    ->setFrom($from)
                    ->setReplyTo($toRecord)
                    ->setSubject('Appraisal Request Received – Waikato Real Estate')
                    ->setBCC($bcc)
                    ->setHTMLTemplate('EmailAppraisalOutgoing')
                    ->setData($submission);
                $email->send();

//                 This email goes back to the admin
                $email = new Email();
                $email->setTo($toRecord)
                    ->setFrom($from)
                    ->setReplyTo($toRecord)
                    ->setSubject('Website Appraisal Request')
                    ->setBCC($bcc)
                    ->setHTMLTemplate('EmailAppraisalRecord')
                    ->setData($submission);
                $email->send();

                $controller = Controller::curr();
                return $controller->redirect($controller->Link() . "?success=2");
            } else {
                throw new ValidationException('Sorry, something went wrong. Please try again.');
            }
        }

        // HONEYPOT CODE
        public function emptySpamField($data)
        {
            if (array_key_exists('Name', $data) && $data['Name'] !== "") {
                //hidden Name field filled out, which means it's probably a bot or spam
                return false;
            } else {
                return true;
            }
        }

        // FEATURED PROPERTIES CODE - gets only two properties at maximum so as to not crowd the page
        public function FeaturedProperties($type = "", $number = 2)
        {
            return Utilities::FeaturedProperties($type)->limit($number);
        }
        // STAFF MEMBERS CODE - gets all the staff members, filtered by their roles given in the parameter

        // SEARCH
        // Gets all suburbs based on type and what's currently in the database
        public function Suburbs()
        {
            $CityIDs = $this->getRequest()->param('ID');

            $filters = [];
            if ($CityIDs) {
                $filters["CityID"] = explode(",", $CityIDs);
            }

            $subs = Suburb::get()->filter($filters);
            $suburbs = [];

            foreach ($subs as $suburb) {
                $name = trim($suburb->Title);
                if (!empty($name)) {
                    $suburbs[] = [
                        'ID' => $suburb->ID,
                        'Title' => $name
                    ];
                }
            }

            return Utilities::jsonifyResponse($suburbs);
        }

        // Gets all cities based on what's currently in the database.
        public function Cities()
        {
            $cits = City::get();
            $cities = [];

            foreach ($cits as $city) {
                $name = trim($city->Title);
                if (!empty($name)) {
                    $cities[] = [
                        'ID' => $city->ID,
                        'Title' => $name
                    ];
                }
            }

            return Utilities::jsonifyResponse($cities);
        }

        public function ClearCache()
        {
            $cache = Injector::inst()->get(CacheInterface::class . '.wreCache');
            $cache->clear();
        }

        /*
         * Returns a matrix of all active properties containing relevant values for type, category, town, and suburb
         * Utilises caching to minimise times this needs to be built
         */
        public function PropertyCounts()
        {
            $cache = Injector::inst()->get(CacheInterface::class . '.wreCache');
            $propertyCounts = null;
            $theMatrixStarringKeanuReeves = [];

            if (!$cache->has('wrePropertyCounts')) {
                // create a new item by trying to get it from the cache
                $cache->get('wrePropertyCounts');

                $properties = Utilities::DisplayableProperties();

                foreach ($properties as $property) {
                    $type = $property->Status == "sold" ? "sold" : $property->Type;
                    $theMatrixStarringKeanuReeves[] = [
                        "type" => $type,
                        "category" => $property->Category,
                        "towncity" => $property->Suburb->City->Title ?? "",
                        "suburb" => $property->Suburb->Title,
                    ];
                }

                // set a value and save it via the adapter
                $cache->set('wrePropertyCounts', $theMatrixStarringKeanuReeves);
            }

            return Utilities::jsonifyResponse($cache->get('wrePropertyCounts'));
        }
    }
}
