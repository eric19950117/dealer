<?php

namespace App;

use App\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends MyModel
{
    use SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'client_name', 'phone_number', 'branch_releated', 'dealer_releated', 'created_id', 'updated_id', 'deleted_id',
    ];

    public static function getList($searchData)
    {
        $instance = new static;

        $list = $instance::Join('branchs', function ($join) use ($searchData) {
            $join->on('branchs.id', '=', 'clients.branch_releated');
            $join->whereNull('branchs.deleted_at');

            if (isset($searchData['branch_id']) && $searchData['branch_id']) {
                $join->where('branchs.id', $searchData['branch_id']);

                unset($searchData["branch_id"]);
            }
        })
            ->Join('dealers', function ($join) use ($searchData) {
                $join->on('dealers.id', '=', 'clients.dealer_releated');
                $join->whereNull('dealers.deleted_at');

                if (isset($searchData['dealer_id']) && $searchData['dealer_id']) {
                    $join->where('dealers.id', $searchData['dealer_id']);

                    unset($searchData["dealer_id"]);
                }
            })
            ->select('clients.*', 'branchs.branch_name', 'dealers.dealer_name');

        if (isset($searchData['keyword']) && $searchData['keyword']) {
            $list->where(function ($query) use ($searchData) {
                $query->where('clients.client_name', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('clients.phone_number', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('branch_name', 'like', '%' . $searchData["keyword"] . '%');
            });
            unset($searchData["keyword"]);
        }
        // echo $list->toSql();
        // exit;

        return $list;
    }
}
