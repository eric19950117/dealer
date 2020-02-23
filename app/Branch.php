<?php

namespace App;

use App\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends MyModel
{
    use SoftDeletes;

    // TODO: Branch也是關鍵字，所以下方需要
    protected $table = 'branchs';

    protected $fillable = [
        'branch_name', 'address', 'phone_number', 'gui_number', 'created_id', 'updated_id', 'deleted_id', 'dealer_releated',
    ];

    public static function getList($searchData)
    {
        $instance = new static;

        $list = $instance::Join('dealers', function ($join) use ($searchData) {
            $join->on('dealers.id', '=', 'branchs.dealer_releated');
            $join->whereNull('dealers.deleted_at');

            if (isset($searchData['dealer_id']) && $searchData["dealer_id"]) {
                $join->where('dealers.id', $searchData["dealer_id"]);

                unset($searchData["admin_group_id"]);
            }
        })
            ->select('branchs.*', 'dealers.dealer_name');

        if (isset($searchData["keyword"]) && $searchData["keyword"]) {
            $list->where(function ($query) use ($searchData) {
                $query->where('branchs.branch_name', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('branchs.address', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('branchs.phone_number', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('branchs.gui_number', 'like', '%' . $searchData["keyword"] . '%');
            });
            unset($searchData["keyword"]);
        }

        // echo $list->toSql();
        // exit;

        return $list;
    }
}
