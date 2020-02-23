<?php

namespace App;

use App\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends MyModel
{
    use SoftDeletes;


    protected $fillable = [
        'dealer_name', 'created_id', 'updated_id', 'deleted_id',
    ];

    public static function getList($searchData)
    {
        $instance = new static;

        $list = $instance::select('dealers.*');

        if (isset($searchData["keyword"]) && $searchData["keyword"]) {

            $list->where(function ($query) use ($searchData) {
                $query->where('dealers.dealer_name', 'like', '%' . $searchData['keyword'] . '%');
            });

            unset($searchData["keyword"]);
        }
        // echo $list->toSql();
        // exit;

        return $list;
    }
}
