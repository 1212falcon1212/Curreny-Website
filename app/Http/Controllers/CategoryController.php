<?php

namespace App\Http\Controllers;



use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{



    public function test_methodu()
    {

        $haber = News::with('category','user')->get();
        dd($haber);
    }
}
