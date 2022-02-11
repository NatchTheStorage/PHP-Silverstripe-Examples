<?php

namespace App\Pages;

use App\Controllers\ArticleHolderPageController;
use App\Models\Article;
use gorriecoe\Link\Models\Link;
use Page;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;

class ArticleHolderPage extends Page
{
  private static $table_name = "ArticleHolderPages";
  private static $singular_name = "Article Holder Page";
  private static $plural_name = "Article Holder Pages";
  private static $controller_name = ArticleHolderPageController::class;

  private static $db = [
    'LinksTitle' => 'Text',
    'SortOrder' => 'Int'
  ];

  private static $many_many = [
    'Links' => Link::class
  ];

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $fields->removeFieldsFromTab('Root.Main', [
      'Content', 'LinksID'
    ]);
    $fields->addFieldsToTab("Root.Main", [

      HeaderField::create('hftitle', 'Links Section')
      ->setDescription('This is the section just above the newsletter form,
      containing a title and some links to the other subsites'),
      TextField::create('LinksTitle', 'Title'),
      GridField::create(
        "Links",
        "Links",
        $this->Links(),
        GridFieldConfig_RecordEditor::create())
    ]);

    return $fields;
  }

  public function getArticlesJSON($subsite)
  {
    $articles = [
      'articles' => array_map(function ($article) {
        return $article->getModelData();
      }, Article::get()->filterAny('Subsite:PartialMatch', $subsite)->toArray())
    ];

    return json_encode($articles);
  }
}
