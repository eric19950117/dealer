<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Auth;

class IndexController extends MY_BackendController
{
    public $title = "首頁";
    public function __construct(Request $request)
    {

    }

    //首頁
    public function index()
    {
        $data = array();
        $data["title"] = $this->title;
        return view("backend.index", $data);
    }


}
