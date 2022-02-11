<?php

namespace App\Controllers;

use App\Models\City;
use App\Models\Person;
use App\Models\Property;
use App\Utils\Utilities;
use PageController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\PaginatedList;

class PropertySearchPageController extends PageController
{
    private static $allowed_actions = [
        "Search",
        'listing',
        "PriceOptions"
    ];

    private static $url_handlers = [
        'prices' => 'PriceOptions'
    ];

    public function index()
    {
        return [
            'PaginatedProperties' => $this->PaginatedProperties()
        ];
    }

    public function PaginatedProperties()
    {
        $request = Controller::curr()->getRequest();
        $listings = Utilities::DisplayableProperties();

        $searchType = $request->getVar("type") ? strtolower($request->getVar("type")) : $this->Type;

        // Searching for a specific 'type' redirects to the right Page type,
        // so we only care about checking for Sold stuff. (see PageController.search())
        // There won't be a search type if the user is just browsing the default pages.
        // In that case we want to make sure we're filtering by the right thing
        $typeFilters = [];
        $typeExcl = [];
        if ($searchType === "sold" || strtolower($this->Type) === "sold") {
            $typeFilters['Status'] = ['sold'];
        } else if ($searchType === "rent" || strtolower($this->Type) === "rent") {
            $typeFilters['Type'] = ['rent'];
            $typeExcl["Status"] = "sold";
        } else if ($searchType === "buy" || strtolower($this->Type) === "buy") {
            $typeFilters['Type'] = ['buy'];
            $typeExcl["Status"] = "sold";
        } else {
            $typeExcl["status"] = "sold"; //always exclude sold unless specifically searched for
        }

        $listings = $listings
            ->filter($typeFilters)
            ->exclude($typeExcl);

        $filters = [];

        switch ($searchType) {
            case "rent":
            case "buy":
            case "sold":
                $filters[] = ["type", "Type", ""];
                $filters[] = ["category", "Category", ""];
                $filters[] = ["suburb", "SuburbID", ""];
                $filters[] =  ["staff_id", "StaffContact.ID", ""];
                $filters[] = ["bedrooms", "FeatureBeds", "GreaterThanOrEqual"];
                $filters[] = ["bathrooms", "FeatureBathrooms", "GreaterThanOrEqual"];
                $filters[] = ["price_min", "PriceFilter", "GreaterThanOrEqual"];
                $filters[] = ["price_max", "PriceFilter", "LessThanOrEqual"];
                $filters[] = ["available", "AvailableFlag", ""];
                break;
            case "":
            case null:
            default:
                break;
        }

//        if they've selected a city and no suburb, grab all suburbs under that city and search by suburbs still
        $suburbIDs = [];
        if (($cityIDs = $request->getVar('towncity')) && !$request->getVar('suburb')) {
            $filters[] = ["towncity", "SuburbID", ""];
            foreach ($cityIDs as $cityID) {
                $city = City::get_by_id($cityID);
                if ($city) {
                    foreach ($city->Suburbs() as $suburb) {
                        $suburbIDs[] = $suburb->ID;
                    }
                }
            }
        }

        foreach ($filters as $filterKeys) {
            list($getVar, $field, $filter) = $filterKeys;
            if ($value = $request->getVar($getVar)) {
                if ($getVar === "towncity" && !empty($suburbIDs)) {
                    $value = $suburbIDs;
                }
                if ($getVar === "type" && $value == "sold") {
                    $value = "buy";
                }

                $listings = empty($filter)
                    ? $listings->filter(["{$field}" => $value])
                    : $listings->filter(["{$field}:{$filter}" => $value]);
            }
        }
        if ($listings->count() === 0) {
            return [];
        }

        $listings = Utilities::sortProperties($listings, $request->getVar("sortBy"));

        return (new PaginatedList($listings, $this->getRequest()))->setPageLength(12);
    }

    public function listing(HTTPRequest $request)
    {
        $property = Property::get()->filter([
            'VaultID' => $request->allParams()["ID"],
            'Active' => 1
        ])->first();

        if (!$property) {
            // no property was found redirect to home;
            return Controller::curr()->redirect("/");
        }
        return [
            "Property" => $property,
        ];
    }

    public function TitleText()
    {
        $category = Controller::curr()->getRequest()->getVar('category');
        $type = Controller::curr()->getRequest()->getVar('type');
        $staff = Controller::curr()->getRequest()->getVar('staff_id');
        $title = $category ? ucwords($category) . " " : "";

        if ($staff) {
            $staffMember = Person::get_by_id($staff);
            if ($staffMember) {
                $sold = $type === "sold" ? " Sold" : "";
                return "{$staffMember->getFullName()}'s{$sold} Properties";
            }
        }

        switch ($this->Type) {
            case "buy":
                return $type === "sold" ? "Sold Properties" : "{$title}Property for Sale";
            case "rent":
                return "{$title}Property for Rent";
            case "All":
            default:
                return "{$title}Properties";
        }
    }

    public function PriceOptions()
    {
        $type = $this->getRequest()->getVar('ID');

        if ($type === "rent") {
            $prices = [
                "" => "Any",
                100 => "$100",
                200 => "$200",
                300 => "$300",
                400 => "$400",
                500 => "$500",
                600 => "$600",
                700 => "$700",
                1000 => "$1,000",
                2000 => "$2,000"
            ];
        } else {
            $prices = [
                "" => "Any",
                200000 => "$200k",
                300000 => "$300k",
                400000 => "$400k",
                500000 => "$500k",
                600000 => "$600k",
                700000 => "$700k",
                1000000 => "$1m",
                2000000 => "$2m",
                5000000 => "$5m"
            ];
        }
        $pricesBuilt = [];

        foreach ($prices as $key => $price) {
            $pricesBuilt[] = [
                'ID' => $key,
                'Title' => $price
            ];
        }

        return Utilities::jsonifyResponse($pricesBuilt);
    }

    public function SearchType()
    {
        $type = $this->getRequest()->getVar('type');
        if (!$type) {
            $type = $this->Type;
        }
        return $type;
    }

}
