<?php

namespace App\Controllers;

use App\Models\Article;
use PageController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;

class ArticleHolderPageController extends PageController
{
  private static $allowed_actions = [
    'article'
  ];

  public function article(HTTPRequest $request)
  {
    $article = Article::get()->filter([
      'ID' => $request->allParams()["ID"]
    ])->first();

    if (!$article) {
      // no property was found redirect to home;
      return Controller::curr()->redirect("/");
    }
    return [
      "Article" => $article,
    ];
  }


  public function MonthYears()
  {
    $articles = Article::get()->filter('Subsite', parent::init());
    $dates = [];

    $dates[] = [
      'Title' => 'All',
      'Value' => '',
      'Sortable' => ''
    ];
    foreach ($articles as $article) {
      $date = date('m-Y', strtotime($article->Date));
      $dates[$date] = [
        'Title' => date('F Y', strtotime($article->Date)),
        'Value' => $date,
        'Sortable' => date('Y-m-d', strtotime($article->Date))
      ];
    }

    usort($dates, function ($a, $b) {
      if ($a['Value'] == $b['Value']) {
        return 0;
      } else {
        return $a['Value'] > $b['Value'] ? 1 : -1;

      }
    });

    return json_encode($dates);
  }

  public function ArticleTypes() {
    $types = [
      'all',
      'missions',
      'news'
    ];

    $types['0'] = [
      'Title' => 'All',
      'Value' => ''
    ];

    $types['1'] = [
      'Title' => 'Missions',
      'Value' => 'Mission'
    ];

    $types['2'] = [
      'Title' => 'News',
      'Value' => 'News Article'
    ];

    return json_encode($types);
  }
}
