<?php

namespace App\Admins;

use App\Models\SubmissionContact;
use App\Models\SubmissionDetails;
use App\Models\SubmissionHangar;
use App\Models\SubmissionNewsletter;
use App\Models\SubmissionRequestAccess;
use SilverStripe\Admin\ModelAdmin;

class SubmissionsAdmin extends ModelAdmin
{
  private static $menu_title = "Form Submissions";
  private static $url_segment = "submissions-admin";

  private static $managed_models = [
    SubmissionContact::class,
    SubmissionNewsletter::class,
    SubmissionHangar::class,
    SubmissionDetails::class,
    SubmissionRequestAccess::class,
  ];
}
