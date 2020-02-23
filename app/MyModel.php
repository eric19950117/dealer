<?php

namespace App;

use Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MyModel extends Model
{
    //儲存log用
    //model=資料
    // public static function boot()
    // {
    //     self::creating(function($model){

    //     });

    //     self::created(function($model){
    //         $modelName = get_class($model);
    //         if($modelName != "App\NoteLogs"){
    //             $data = array();
    //             $noteLogsModel = new NoteLogs;
    //             $data["model"] = $modelName;
    //             $data["data_after"] = $model->toJson();
    //             $data["uri"] = Request::path();
    //             $data["ip"] = Request::getClientIp();
    //             $data["user_agent"] = Request::server('HTTP_USER_AGENT');
    //             $data["action"] = "create";
    //             $modelData = $model->toArray();
    //             $data["id"] = $modelData[$model->primaryKey];
    //             //$data["id"] = DB::getPdo()->lastInsertId();
    //             $noteLogsModel->createData($data);

    //         }

    //     });

    //     self::updating(function($model){

    //     });

    //     self::updated(function($model){
    //         $modelName = get_class($model);
    //         if($modelName != "App\NoteLogs"){
    //             $data = array();
    //             $noteLogsModel = new NoteLogs;
    //             $data["model"] = $modelName;
    //             $data["data_after"] = $model->toJson();
    //             $data["uri"] = Request::path();
    //             $data["ip"] = Request::getClientIp();
    //             $data["user_agent"] = Request::server('HTTP_USER_AGENT');
    //             $data["action"] = "update";
    //             $modelData = $model->toArray();
    //             $data["id"] = $modelData[$model->primaryKey];
    //             $noteLogsModel->createData($data);
    //         }

    //     });

    //     self::deleting(function($model){

    //     });

    //     self::deleted(function($model){
    //         $modelName = get_class($model);
    //         if($modelName != "App\NoteLogs"){
    //             $data = array();
    //             $noteLogsModel = new NoteLogs;
    //             $data["model"] = $modelName;
    //             $data["data_after"] = $model->toJson();
    //             $data["uri"] = Request::path();
    //             $data["ip"] = Request::getClientIp();
    //             $data["user_agent"] = Request::server('HTTP_USER_AGENT');
    //             $data["action"] = "delete";
    //             $modelData = $model->toArray();
    //             $data["id"] = $modelData[$model->primaryKey];
    //             $noteLogsModel->createData($data);
    //         }

    //     });
    // }

    //共用的新增資料
    public function createData($data, $is_admin = true){

        $userId = 0;

        $user = \Auth::user();
        //跑排程時會有null狀況
        if(!is_null($user)){
            $userId = $user->id;
        }

        $this->created_id = $userId;
        $this->updated_id = $userId;
        $this->fill($data)->save();
        $modelData = $this->toArray();
        return $modelData["id"];
    }

    //共用的修改資料
    public function updateData($data, $id, $is_admin = true){

        $userId = 0;

        $user = \Auth::user();

        //跑排程時會有null狀況
        if(!is_null($user)){
            $userId = $user->id;
        }

        $data = $this::find($id);
        $data->updated_id = $userId;
        $data->fill($data)->save();
    }
}
