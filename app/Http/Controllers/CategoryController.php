<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Category;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = Category::query()
            ->select(['name'])
            ->get()
            ->toArray();
        return response()->json($data);
    }

}
