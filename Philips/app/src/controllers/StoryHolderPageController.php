<?php

namespace App\Controllers;

use App\Models\Story;
use PageController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\PaginatedList;

class StoryHolderPageController extends PageController
{
  private static $allowed_actions = [
    'story'
  ];

  public function story(HTTPRequest $request)
  {
    $story = Story::get()->filter([
      'ID' => $request->allParams()["ID"]
    ])->first();

    if (!$story) {
      // no property was found redirect to home;
      return Controller::curr()->redirect("/");
    }
    return [
      "Story" => $story,
    ];
  }

  public function StoriesList($subsite)
  {
    $req = Controller::curr()->getRequest();
    $stories = Story::get()->filter('Subsite', $subsite)->sort('Date', 'DESC');

    $list = new PaginatedList($stories, Controller::curr()->getRequest());
    $list->setPageLength(6);

    return $list;
  }
}