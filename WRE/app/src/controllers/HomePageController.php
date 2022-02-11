<?php

namespace App\Controllers;

use App\Models\City;
use App\Models\Property;
use App\Models\Suburb;
use App\Pages\PropertySearchPage;
use PageController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;

class HomePageController extends PageController
{
    private static $allowed_actions = [
        "Search"
    ];

    public function Search()
    {
        $request = $this->getRequest();

        // Get the searched type, or default to All
        $type = "buy";
        if ($reqType = $request->getVar("type")) {
            $type = $reqType === "sold" ? "buy" : $reqType;
        }

        $page = PropertySearchPage::get()
            ->filter('type', strtolower($type))
            ->first();
        $vars = $request->getVars();

        if (!$page) {
            return $this->redirectBack();
        }

        return $this->redirect($page->Link() . "?" . http_build_query($vars));
    }
}
