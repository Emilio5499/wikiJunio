<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function index()
    {
        return Category::withCount('user')->get();
    }



}
