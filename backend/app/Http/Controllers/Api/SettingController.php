<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;

class SettingController extends Controller
{
    public function index(): SiteSettingResource
    {
        return new SiteSettingResource(SiteSetting::current());
    }
}
