<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request): SiteSettingResource
    {
        return new SiteSettingResource(SiteSetting::current($this->site($request)));
    }
}
