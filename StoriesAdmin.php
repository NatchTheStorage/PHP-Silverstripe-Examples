<?php

namespace App\Admins;

use App\Models\Event;
use App\Models\Article;
use SilverStripe\Admin\ModelAdmin;

class StoriesAdmin extends ModelAdmin
{
  private static $menu_title = "Admin - Stories & Videos";
  private static $url_segment = "stories-videos-admin";

  private static $managed_models = [
    Event::class, Article::class
  ];
}