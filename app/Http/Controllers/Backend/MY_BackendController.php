<?php

namespace App\Http\Controllers\Backend;

use App\Sidebar;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
// FIXME: 為什麼不是用 -> user APP\Http\Controllers\Controller
use Illuminate\Routing\Controller as BaseController;

class MY_BackendController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $perPage = 10;

    public static function getSidebarList()
    {
        // TODO: 先定義$sidebar為陣列
        $sidebar = array();
        $mainSidebarList = Sidebar::where("sidebar_id", 0)->orderBy('sort', 'asc')->get()->toArray();
        // TODO: 下方為sidebar的小選單
        $subSidebarList = Sidebar::where("sidebar_id", "!=", 0)->orderBy('sort', 'asc')->get()->toArray();
        // echo '<pre>';
        // print_r($subSidebarList);
        // exit;
        foreach ($mainSidebarList as $key => $va) {
            // FIXME: 思考下方變數寫的邏輯 ->
            // echo '<pre>';
            // print_r($va);
            // exit;
            $sidebar[$va["id"]] = $va;
            // echo '<pre>';
            // print_r($sidebar);
            // exit;
        }

        // echo '<pre>';
        // print_r($sidebar[$va['id']]);
        // exit;

        foreach ($subSidebarList as $key => $va) {
            // echo '<pre>';
            // print_r($sidebar[$va['sidebar_id']]);
            // exit;
            // TODO: 判斷小選單對應的側變欄是否存在
            if (isset($sidebar[$va["sidebar_id"]])) {

                // TODO: ['sidebar_id'] 為該小選單對應的sidebar id, ['sub'] 為該小選單對應的sidebar id
                $sidebar[$va["sidebar_id"]]["sub"][$va["id"]] = $va;

                // echo '<pre>';
                // print_r($sidebar[$va['sidebar_id']]['sub'][$va['id']]);
                // exit;
            }
        }

        return $sidebar;
    }
    //file 上傳的檔案
    //type images file
    //unit 單元
    //is_thumbnail 是否要縮圖
    //thumbnail_size 縮圖大小 ['Xlarge'=> 1280,'large'=>800, 'medium'=>400, 'smail'=>200]
    public static function uploadFile($file, $type, $unit, $is_thumbnail = true, $thumbnail_size = [])
    {

        $fileData = array();

        try {
            $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
            $file_path = 'uploads/' . $type . '/' . $unit . '/' . date('Y') . '/' . date('m');
            if (!is_dir($file_path)) {
                mkdir($file_path, 0777, true);
            }

            if ($type == "images") {
                $file_original_path = public_path($file_path) . "/original";
                if (!is_dir($file_original_path)) {
                    mkdir($file_original_path, 0777, true);
                }

                if ($is_thumbnail) {
                    if (!$thumbnail_size) {
                        $thumbnail_size = ['Xlarge' => 1280, 'large' => 800, 'medium' => 400, 'smail' => 200];
                    }

                    foreach ($thumbnail_size as $sizeName => $width) {

                        $file_thumbnail_path = public_path($file_path) . "/" . $sizeName;
                        if (!is_dir($file_thumbnail_path)) {
                            mkdir($file_thumbnail_path, 0777, true);
                        }

                        Image::make($file)->resize($width, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($file_thumbnail_path . '/' . $file_name);
                    }
                }

                Image::make($file)->save($file_original_path . '/' . $file_name);
            } else {
                $file->move($file_path, $file_name);
            }

            $fileData["file_name"] = $file_name;
            $fileData["status"] = true;
        } catch (Exception $e) {

            $fileData["file_name"] = "";
            $fileData["status"] = false;
        }

        return $fileData;
    }
}
