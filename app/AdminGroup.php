<?php

namespace App;

use App\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Database\Eloquent\Model;

// TODO: 對應為admin_groups資料表(第一個字一定為大寫，遇到底線後的第一個字也要為大寫)
class AdminGroup extends MyModel
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'permission', 'created_id', 'updated_id', 'deleted_id',
    ];

    public static function getList($searchData)
    {
        $instance = new static;

        $list = $instance::select("admin_groups.*");

        if (isset($searchData["keyword"]) && $searchData["keyword"]) {

            $list->where(function ($query) use ($searchData) {
                $query->where('admin_groups.name', 'like', '%' . $searchData["keyword"] . '%');
            });
            unset($searchData["keyword"]);
        }

        return $list;
    }
}
