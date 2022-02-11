<?php

namespace App\Admins;

use App\Models\FooterAward;
use App\Models\OfficeLocation;
use App\Models\Property;
use App\Models\ServiceType;
use SilverStripe\Admin\ModelAdmin;

class PropertyAdmin extends ModelAdmin
{
    public $showImportForm = false;

    private static $url_segment = 'property-admin';
    private static $menu_title = 'Admin - Property';

    private static $managed_models = [
        Property::class
    ];
}