<?php

namespace App\Pages;

use App\Controllers\NoticePageController;
use Page;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;

class NoticePage extends Page
{
    private static $table_name = "NoticePages";
    private static $singular_name = "Notice Page";
    private static $plural_name = "Notice Pages";
    private static $controller_name = NoticePageController::class;

    private static $db = [
        'FormTitle' => 'Text',
        'FormDescription' => 'Text',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Content');
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('FormTitle', 'Form Title')
            ->setDescription('This is the title displayed above the form description and form itself'),
            TextareaField::create('FormDescription', 'Form Description')
        ]);

        return $fields;
    }
}