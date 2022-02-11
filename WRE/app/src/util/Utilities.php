<?php

namespace App\Utils;

use App\Models\OpenHome;
use App\Models\Person;
use App\Models\Property;
use App\Models\Suburb;
use App\Models\VaultImage;
use DateTime;
use SilverStripe\Assets\Folder;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;

class Utilities
{
    private const RESIDENTIALSALES = "residentialSales";
    private const PROPERTYMANAGER = "propertyManagement";
    private const FRONTOFFICE = "frontOffice";
    private const MANAGEMENT = "management";


    //NOTE: Doesn't include sold as sometimes we want to search for those.
    public const INACTIVE_PROPERTIES = [
        "deleted",
        "inactive",
        "offmarket",
        "withdrawn",
    ];

    public const PROPERTY_TYPES = [
        "House",
    ];

    public const PROPERTY_CLASSES = [
        "residential",
        "commercial",
        "rural",
        "business",
        "land",
        "holidayRental"
    ];

    public static function resizeImage($image)
    {
        $backend = $image->getImageBackend();

        if ($backend && ($backend->getWidth() > 2400 || $backend->getHeight() > 2400)) {
            // temporary location for image manipulation
            $tmp_image = TEMP_FOLDER . '/resampled-' . random_int(100000, 999999) . '.' . $image->getExtension();
            $tmp_contents = $image->getString();
            // write to tmp file
            file_put_contents($tmp_image, $tmp_contents);

            $backend->loadFrom($tmp_image);
            $transformedBE = $backend->resizeRatio(2400, 2400);

            // write to tmp file and then overwrite original
            $transformedBE->writeTo($tmp_image);
            $image->File->deleteFile();
            $image->setFromLocalFile($tmp_image, $image->FileName); // set new image
            $image->setImageBackend($transformedBE);

            $image->write();
            $image->publishSingle();

            // Clear backend image data from memory
            // (have to do it this way as unlink/unset/setting to null doesn't work on interfaces)
            $transformedBE->setImageResource(null);
            $backend->setImageResource(null);

            unlink($tmp_image);
            $tmp_image = null;
            $tmp_contents = null;
        }

        return $image;
    }

    /**
     *
     * @param $properties
     * @param $sortBy user-friendly string (probably  the 'value' from a dropdown)
     * @return ArrayList
     */
    public static function sortProperties($properties, $sortBy): ArrayList
    {
        switch ($sortBy) {
            case "lowestPrice":
                $propertiesArray = $properties->toArray();
                usort($propertiesArray, function ($a, $b) {
                    $aPrice = $a->PriceFilter;
                    $bPrice = $b->PriceFilter;

                    if ($aPrice && $bPrice) {
                        return $aPrice - $bPrice;
                    } else {
                        if ($a->priceView === $b->priceView) {
                            return 0;
                        } else {
                            return $a->priceView > $b->priceView ? 1 : -1;
                        }
                    }
                });
                return ArrayList::create($propertiesArray);
            case "highestPrice":
                $propertiesArray = $properties->toArray();
                usort($propertiesArray, function ($a, $b) {
                    $aPrice = $a->PriceFilter;
                    $bPrice = $b->PriceFilter;

                    if ($aPrice && $bPrice) {
                        return $bPrice - $aPrice;
                    } else {
                        if ($a->PriceSearch === $b->PriceSearch) {
                            return 0;
                        } else {
                            return $a->PriceSearch > $b->PriceSearch ? 1 : -1;
                        }
                    }
                });
                return ArrayList::create($propertiesArray);
            case "oldest":
                return ArrayList::create($properties->sort("Created ASC")->toArray());
            case "latest":
            case "":
            case null:
            default:
                return ArrayList::create($properties->sort("Created DESC")->toArray());
        }
    }

    /**
     * This function sorts properties based on the difference in price to the reference/current one
     * @param $properties DataList list of properties to sort (will be converted to array)
     * @param $currentPrice number price of the current/reference property, can be price or rentalAmount
     * @param $type string type of current property ("rental" or other)
     * @return array array of properties
     */
    public static function sortPropertiesByClosestPrice($properties, $currentPrice, $type)
    {
        $propertyArray = $properties->toArray();

        if ($type == "lease") {
            usort($propertyArray, function ($a, $b) use ($currentPrice) {
                $diffA = abs($a->rentalAmount - $currentPrice);
                $diffB = abs($b->rentalAmount - $currentPrice);

                if ($diffA == $diffB) {
                    return 0;
                } else {
                    return $diffA < $diffB ? -1 : 1;
                }
            });
        } else {
            usort($propertyArray, function ($a, $b) use ($currentPrice) {
                $diffA = abs($a->price - $currentPrice);
                $diffB = abs($b->price - $currentPrice);

                if ($diffA === $diffB) {
                    return 0;
                } else {
                    return $diffA < $diffB ? -1 : 1;
                }
            });
        }

        return $propertyArray;
    }

    public static function setValues($model, $data, $keys)
    {
        foreach ($keys as $key => $value) {

            if (!empty($data[$value])) {
                $model->$key = $data[$value];
            }
        }
        return $model;
    }

    public static function formatDate($dateString)
    {
        $date = DateTime::createFromFormat("Y-m-d\TH:i:sP", $dateString);
        if (!$date) {
            $date = DateTime::createFromFormat("Y-m-d\TH:i:s", $dateString);
        }
        if (!$date) {
            $date = DateTime::createFromFormat("Y-m-d", $dateString);
        }

        if ($date) {
//            we have to set the timezone, because Vault sends UTC
            $date->setTimezone(new \DateTimeZone('Pacific/Auckland'));
        }

        return $date ? $date->format("Y-m-d H:i:s") : "";
    }

    public static function jsonifyResponse($data): HTTPResponse
    {
        return (new HTTPResponse(json_encode($data)))
            ->addHeader('Access-Control-Allow-Origin', '*')
            ->addHeader('Content-Type', 'application/json');
    }

    public static function clearRelation($model, $relation): void
    {
        foreach ($model->$relation() as $item) {
            $item->delete();
        }

        $model->$relation()->removeAll();
    }

    /**
     * @param $staff
     * @param $property
     * @return string
     * @throws \SilverStripe\ORM\ValidationException
     */
    public static function processAgent($staff, $property): string
    {
        if (!empty($staff)) {
            $membersProcessed = 0;
            foreach ($staff as $staffMember) {
                $membersProcessed++;
                $person = Person::get()->filter("VaultID", $staffMember->id)->first();

                if (!$person) {
                    $person = new Person();
                    $person->VaultID = $staffMember->id;
                }

                $person->FirstName = $staffMember->firstName;
                $person->LastName = $staffMember->lastName;
                $person->Username = $staffMember->username;
                $person->Role = $staffMember->role ?? $person->Role;
                $person->Email = $staffMember->email ?? $person->Email;
                $person->Position = $staffMember->position ?? $person->Position;

                foreach ($staffMember->phoneNumbers as $number) {
                    if ($number->type === "Mobile") {
                        $person->PhoneMobile = $number->number ?? $person->PhoneMobile;
                    }
                }

                $person->Properties()->add($property);
                $person->write();

                $staffFolder = Folder::find_or_make('staff-photos/' . $person->VaultID);

                if ($staffMember->photo) {
                    if ($person->PhotoID && $person->Photo->ID) {
                        self::deleteItemAndVersions($person->Photo);
                    }
                    $url = $staffMember->photo->original;

                    $image = VaultImage::create();
                    $image->setFromString(file_get_contents($url), ($staffFolder->getFilename() . basename($url)));
                    $image->ParentID = $staffFolder->ID;
                    $image->write();

                    $image = self::resizeImage($image);

                    $person->Photo = $image;
                    $person->write();
                    $image->publishSingle();

                    $image = null;
                    $img = null;
                }

            }
            return "Processed " . $membersProcessed . " agents for this property";
        }
        else {
            return "   No agent processed";
        }

    }

    /**
     * @param $address
     * @param $property
     * @throws \SilverStripe\ORM\ValidationException
     */
    public static function processAddress($address, $property): void
    {
        if (isset($address->suburb)) {
            $suburb = Suburb::get()->filter("Title", $address->suburb->name)->first();

            if (!$suburb) {
                $suburb = new Suburb();
                $suburb->Title = $address->suburb->name;
            }

            $suburb->VaultID = $address->suburb->id;
            $suburb->Postcode = $address->suburb->postcode;

            $suburb->write();

            $property->Suburb = $suburb;
            $property->SearchableSuburb = $suburb->Title;
        }

        /*
         * Cities are not coming through the Vault API as at 4/5/21
         * VaultRE has given no timeline for adding Cities (a real estate API with no City names... yikes)
         * We currently use a manual workaround
         */
        /*if (isset($address->city)) {
            $city = City::get()->filter("Title", $address->city->name)->first();

            if (!$city) {
                $city = new City();
                $city->Title = $address->city->name;
            }

            $city->VaultID = $address->city->id;
            $city->Abbreviation = $address->city->abbreviation;

            $city->write();

            $property->City = $city;
            $property->SearchableCity = $address->city->name;
        }*/
    }

    /**
     * @param $props
     * @param $property
     * @return void
     */
    public static function processExternalLinks($props, $property): void
    {
        $property->VirtualTourLink = null;
        $property->VideoLink = null;

        foreach ($props as $prop) {
            if ($prop->type->name === 'Virtual Tour') {
                $property->VirtualTourLink = $prop->url;
            } else if ($prop->type->name === 'Video') {
                $property->VideoLink = $prop->url;
            }
        }
    }

    /**
     * @param $auction
     * @param $property
     * @return void
     */
    public static function processAuctionDetails($auction, $property): void
    {
        if ($auction && $auction->dateTime) {
            $property->AuctionDate = $auction->dateTime;
            $property->AuctionLocation = $auction->venue;
            $property->Auctioneer = $auction->auctioneer;

            $property->write();
        }
    }

    public static function cleanupOpenHomes()
    {
        $openHomes = OpenHome::get();
        foreach ($openHomes as $openHome) {
            $openHome->delete();
        }
    }

    public static function rentalFeatureNames()
    {
        return [
            "PropertyBathroomsNo" => "bathrooms",
            "PropertyBedroomsNo" => "bedrooms",
            "PropertyEnsuitesNo" => "ensuites",
            "PropertyCarsNo" => "carports",
            //"" => "garages",
            //"" => "openSpaces",
        ];
    }

    public static function propertyStatus($status = "")
    {
        $statusMap = [
            "settled" => "sold",
            "unconditional" => "sold",
            "conditional" => "conditional",
            "listing" => "listing",
        ];

        if ($status) {
            if (array_key_exists($status, $statusMap)) {
                return $statusMap[$status];
            } else {
                return $status;
            }
        }

        return $statusMap;
    }

    public static function personOptions()
    {
        return [
            self::RESIDENTIALSALES => "Residential Sales",
            self::PROPERTYMANAGER => "Property Manager",
            self::FRONTOFFICE => "Front Office",
            self::MANAGEMENT => "Management",
        ];
    }

    //  Gets filtered properties based upon the TYPE given
    public static function FeaturedProperties($type = "")
    {
        $properties = Utilities::DisplayableProperties();
        $filters = [
            "FeaturedProperty" => 1,
        ];
        switch (strtolower($type)) {
            case 'rent':
                $filters['Type'] = 'rent';
                break;
            case 'buy':
                $filters['Type'] = 'buy';
                break;
            default:
                break;
        }
        return $properties->filter($filters);
    }

    // Gets properties that are not force hidden and are active
    public static function DisplayableProperties($propertyID = null)
    {
        $filters = [
            'ForceHide' => 0,
            "Active" => 1,
        ];

        if ($propertyID) {
            $filters["ID"] = $propertyID;
        }

        $result = Property::get()->filter($filters);

        return $propertyID ? $result->first() : $result;
    }

    //  Gets staff members based on their roles, defaults to giving all
    public static function StaffMembers($role = "")
    {
        $filters = [
            "Inactive" => 0
        ];
        switch (strtolower($role)) {
            case 'sales':
                $filters['Role'] = 'residentialSales';
                break;
            case 'propertyManagement':
                $filters['Role'] = 'propertyManagement';
                break;
            case 'frontoffice':
                $filters['Role'] = 'frontoffice';
                break;
            case 'management':
                $filters['Role'] = 'management';
                break;
            default:
                break;
        }
        return Person::get()->filter($filters);
    }

    public static function manipulateYoutubeLink($originalLink)
    {
        // Working from the following formats
        // https://youtu.be/W8M1CNOu5ww
        //"https://www.youtube.com/embed/3wLLgJ_a7Rs";
        //"https://www.youtube.com/watch?v=_6Q53ceiesU";
        //"https://www.youtube.com/watch?v=WYA1qapa5Us&feature=youtu.be";

        //We need to extract the video ID from these so we can put it into the embed link format
        // https://www.youtube.com/embed/of0wSOfO46Q
        $id = "";

        //Most common will contain a 'watch?v='
        if (strpos($originalLink, "watch?v=")) {
            // remove any extra parameters to make our life easy
            $link = explode("&", $originalLink);
            $link = $link[0];

            //lets get that id
            $link = explode("https://www.youtube.com/watch?v=", $link);
            if (count($link) < 2) {
                return false;
            }

            $id = $link[1];
        } else if (strpos($originalLink, "youtu.be") || strpos($originalLink, "embed")) {
            $link = explode("/", $originalLink);
            $id = $link[count($link) - 1];
        } else {
            return false;
        }

        return $id;
    }

    /**
     * Dynamically allocate memory based on image dimensions, bit-depth and channels
     * Shamelessly stolen from somewhere online.
     * Probably from https://alvarotrigo.com/blog/allocate-memory-on-the-fly-PHP-image-resizing/
     *
     * @param string $filename Full path to a file supported by getimagesize() function
     * @param float $tweak_factor Multiplier for tweaking required memory. 1.8 seems fine. More info: http://php.net/imagecreatefromjpeg#76968
     * @param string $original_name Used purely for reporting actual file name instead of uploaded temp file (e.g. /tmp/RaNd0m.tmp)
     *
     * @return bool true on success or if no memory increase required, false if required memory amount is too large
     */
    public static function setMemoryLimit(string $filename, $tweak_factor = 1.8, $original_name = null): bool
    {

        $maxMemoryUsage = 512 * 1024 * 1024; // 512MB
        $width = 0;
        $height = 0;
        $memory_limit = self::return_bytes(ini_get('memory_limit'));

        $memory_baseline_usage = memory_get_usage(true);

        // Getting the image info
        $info = @getimagesize($filename);
        if (empty($info)) {
            return false;
        }

        !empty($original_name) ? $filename = $original_name : $original_name;

        $channels = $info['channels'] ?? 3;
        [$width, $height] = $info;

        if ($info['mime'] == 'image/png') {
            $channels = 4;
        }

        if (!isset($info['bits'])) {
            $info['bits'] = 16;
        }
        $bytes_per_channel = (($info['bits'] / 8) * $channels);

        // Calculating the needed memory
        $new_limit = $memory_baseline_usage + ($width * $height * $bytes_per_channel * $tweak_factor + 1048576);

        if ($new_limit <= $memory_limit) {
            return true;
        }

        /* We don't want to allocate an extremely large amount of memory
        so it's a good practice to define a limit and bail out if new limit is more than that */
        if ($new_limit > $maxMemoryUsage) {
            return false;
        }

        $new_limit = ceil($new_limit / 1048576);

        // Updating the default value
        ini_set('memory_limit', $new_limit . 'M');
        return true;
    }

    private static function return_bytes($val): int
    {
        $val = (int)trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    /**
     * Delete all versions of a VaultImage.
     * The File_Versions table in the DB is a chonky boi - need to start deleting the file history as it's not needed.
     *  (we delete all the images and re-download them all each time the property is updated, so we lose any references to versions anyway)
     * @param $item
     * @param bool $isImage
     */
    public static function deleteItemAndVersions($item, $isImage = true)
    {
        $recordID = $item->ID;  //Grab the ID before $item is gone-ski

        $item->doUnpublish();
        $item->deleteFile();
        $item->delete();

        // I hate hard-coding SQL queries, but my (admittedly brief) testing didn't work any other way.
        // (Examples online also do it this way, though with a bit more finesse)
        // Have to do this after deleting $item as the action of deleting creates new version history rows with 'WasDeleted' = true
        // 3 tables to remove file history from:
        if ($isImage) {
            DB::query('DELETE FROM "VaultImages_Versions" WHERE "RecordID" = ' . $recordID);
            DB::query('DELETE FROM "Image_Versions" WHERE "RecordID" = ' . $recordID);
        } else {
            DB::query('DELETE FROM "VaultFiles_Versions" WHERE "RecordID" = ' . $recordID);
        }

        DB::query('DELETE FROM "File_Versions" WHERE "RecordID" = ' . $recordID);
    }
}
