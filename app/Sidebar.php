<?php

namespace App;
use App\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Database\Eloquent\Model;

class Sidebar extends MyModel
{
    //
    use SoftDeletes;
}
