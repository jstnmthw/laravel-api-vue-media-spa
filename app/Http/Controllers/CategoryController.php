<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Category;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        app('debugbar')->disable();
        $data = Category::query()
            ->select(['name'])
            ->get()
            ->toArray();
        return response()->json($data);
    }

}
