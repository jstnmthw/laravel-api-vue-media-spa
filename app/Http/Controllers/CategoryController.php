<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Category;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return string
     */
    public function index(): string
    {
        return response()->json(Category::query()->select('name')->get());
    }

}
