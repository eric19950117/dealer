<?php

namespace App;

use App\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mission extends MyModel
{
    use SoftDeletes;

    protected $table = 'missions';


    protected $fillable = [
        'mission_name', 'mission_content', 'dealer_releated', 'branch_releated', 'client_releated', 'admin_releated', 'created_id', 'updated_id', 'deleted_id',
    ];

    public static function getList($searchData)
    {
        $instance = new static;

        $list = $instance::Join('dealers', function ($join) use ($searchData) {
            $join->on('dealers.id', '=', 'missions.dealer_releated');
            $join->whereNull('dealers.deleted_at');

            if (isset($searchData['dealer_id']) && $searchData['dealer_id']) {
                $join->where('dealers.id', $searchData['dealer_id']);

                unset($searchData["dealer_id"]);
            }
        })
            ->Join('branchs', function ($join) use ($searchData) {
                $join->on('branchs.id', '=', 'missions.branch_releated');
                $join->whereNull('branchs.deleted_at');

                if (isset($searchData['branch_id']) && $searchData['branch_id']) {
                    $join->where('branchs.id', $searchData['branch_id']);

                    unset($searchData["branch_id"]);
                }
            })
            ->Join('clients', function ($join) use ($searchData) {
                $join->on('clients.id', '=', 'missions.client_releated');
                $join->whereNull('clients.deleted_at');

                if (isset($searchData['client_id']) && $searchData['client_id']) {
                    $join->where('clients.id', $searchData['client_id']);

                    unset($searchData["branch_id"]);
                }
            })
            ->Join('admins', 'admins.id', '=', 'missions.admin_releated')
            ->select('missions.*', 'dealers.dealer_name', 'branchs.branch_name', 'clients.client_name', 'admins.name as admin_name');

        if (isset($searchData['keyword']) && $searchData['keyword']) {
            $list->where(function ($query) use ($searchData) {
                $query->where('mission_name', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('dealer_name', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('branch_name', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('client_name', 'like', '%' . $searchData["keyword"] . '%');
            });
            unset($searchData["keyword"]);
        }
        return $list;
    }
}
