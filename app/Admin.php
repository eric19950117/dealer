<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// TODO: 對應為admins資料表
class Admin extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // snake case -> 單字與單字間使用下劃線分隔
    protected $table = 'admins'; // TODO: 假如沒特別指定，系統會自動對應snake case且複數的資料表名稱
    // TODO: $fillable -> 在建立一個新的模型時，把屬性資料陣列傳入建構子，這些屬性值會經由批量賦值存成模型資料。
    protected $fillable = [
        'name', 'email', 'password', 'admin_group_id', 'is_active', 'created_id', 'updated_id', 'deleted_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // TODO: 隱藏欄位
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // TODO: $casts屬性為陣列，'email_verified_at'為需要被轉換的屬性名稱， 'datetime'為想要把欄位轉換成什麼類型
    // FIXME: 沒有看到email_verified_at這個欄位
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminGroup()
    {
        // hasOne() -> 一對一關聯 -> 用法和left join & inner join相似
        // TODO: 'id'為副表欲關聯的欄位，'admin_group_id'為主表的欄位
        return $this->hasOne('App\AdminGroup', 'id', 'admin_group_id');
    }

    public static function getList($searchData)
    {
        $instance = new static; // FIXME: 不懂為什麼要設定一個靜態的變數 -> 呼叫自己

        // TODO: 61行可以寫成 -> $list = Admin::leftJoin() -> 怕會噴錯
        // FIXME: 使用leftJoin在搜尋管理員群組時，管理員和一般會員都會一起顯示(bug)->改成join即可修正(inner join 將兩個表相同的欄位取出)
        $list = $instance::Join('admin_groups', function ($join) use ($searchData) {
            // TODO: 使用主表admin的admin_group_id欄位 -> 關聯副表admin_groups的id
            $join->on('admin_groups.id', '=', 'admins.admin_group_id');
            // TODO: 使用 Where Null 判斷 deleted_at 欄位是否為null
            $join->whereNull('admin_groups.deleted_at');
            // FIXME: 這邊能像一般的php一樣把mysql的query印出嗎？
            //        -> 在最後方加入 (1)->toSql() (2)把query印出 dd($list)
            if (isset($searchData["admin_group_id"]) && $searchData["admin_group_id"]) {
                $join->where('admin_groups.id', $searchData["admin_group_id"]);
                // TODO: unset() -> 用來移除變數的值，清除後並不會回傳任何結果
                // FIXME: 為什麼要清除變數值？
                unset($searchData["admin_group_id"]);
            }
        })
            ->select("admins.*", "admin_groups.name as admin_groups_name");

        // ->toSql();
        // dd($list);

        if (isset($searchData["keyword"]) && $searchData["keyword"]) {
            $list->where(function ($query) use ($searchData) {
                $query->where('admins.name', 'like', '%' . $searchData["keyword"] . '%');
                $query->orWhere('admins.email', 'like', '%' . $searchData["keyword"] . '%');
            });
            unset($searchData["keyword"]);
        }
        // echo $list->toSql();
        // exit;
        return $list;
    }
}
