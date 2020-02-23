<?php

namespace App\Http\Controllers\Backend;

use App\Admin; // Eloquent ORM
use App\AdminGroup; // Eloquent ORM
use Illuminate\Http\Request; // Http請求 -> 繼承了 Symfony\Component\HttpFoundation\Request 類別
use Auth; // 此檔案在config/auth.php -> 認證用
use Illuminate\Support\Facades\Hash;

class AdminController extends MY_BackendController
{
    public $sidebar_id = 2; // 2A(查看) 2B(新增) 2C(刪除) 2D(修改) 皆為後台會員管理的權限
    public $permission = "";
    public $title = "後台會員管理";

    public function __construct(Request $request)
    {
        // FIXME: 思考為什麼下面這一行程式碼放在這個位置也可以取得permission欄位，但為什麼要放在中介層內？
        // $this->permission = $permission = Auth::user()->adminGroup->permission; -> 會噴錯
        // return $request;
        $this->middleware(function ($request, $next) {
            // TODO: Auth::user() -> 為admin的物件(取得目前的已認證使用者)
            // TODO: Auth::user()->adminGroup -> 呼叫adminGroup()這個function
            // TODO: permission為admin_groups表內的欄位
            // TODO: 將取得的permission資料存入$permission，再呼叫將permission結果印出
            // $test = Auth::user()->adminGroup;
            // echo $test;
            // exit;
            $this->permission = $permission = Auth::user()->adminGroup->permission;
            // TODO: 呼叫帶有$request的$next方法，即可將請求傳遞到更深層的應用程式(允許「通過」中介層)
            return $next($request);
        });
    }

    //列表
    public function lists(Request $request)
    {
        // TODO: strrpos()->計算指定字符串在目標字符串中最後一次出現的位置(case-sensitive)
        // FIXME: 要搞懂$this用法? -> $this指的是AdminController這個物件主體
        // TODO: 要找到2A(查看權限)
        if (strrpos($this->permission, "[" . $this->sidebar_id . "A],") === false) {
            // TODO: 假如字符串裡沒有出現2A則會執行以下訊息 -> 權限不足
            // TODO: 下方為快閃資料(想存入資料並只在下一次請求有效)
            // FIXME: statusCode要去哪裡找？ -> 在views/backend/shared/page-head.blade.php
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            // TODO: 權限不足 -> 導回後台首頁
            return redirect("/backend");
        }
        // TODO: 以陣列的形式取得所有GET和POST輸入的資料
        // FIXME: 為什麼$searchData使用print_r()在網頁上只顯示Array() -> 在input欄位輸入值後送出即會有值
        $searchData = $request->all();
        // echo '<pre>';
        // print_r($searchData);
        // exit;
        $data = array();
        // TODO: 從admin.php呼叫getList()這個function
        // FIXME: 不太理解這邊的邏輯
        // TODO: $this->perPage為呼叫MY_BackendController內的變數(做出分頁)
        $data["result"] = Admin::getList($searchData)->paginate($this->perPage);
        // echo '<pre>';
        // print_r($data['result']);
        // exit;
        // TODO: array_merge() -> 合併多個陣列
        // TODO: pluck() -> 取得name、id欄位作為鍵值
        // TODO: toArray() -> 將集合(collection)轉換成純 PHP 陣列。假如集合的數值是 Eloquent 模型，也會被轉換成陣列
        // FIXME: 思考什麼時候需要使用toArray() -> AdminGroup::pluck('name', 'id')為集合要轉換成陣列
        $data["adminGroup"] = array_merge([0 => "請選擇"], AdminGroup::pluck('name', 'id')->toArray());
        // print_r(AdminGroup::pluck('name', 'id'));
        // exit;
        // echo '<pre>';
        // print_r($data['adminGroup']);
        // exit;
        // TODO: $data["title"]可以在其他頁面以$title的方式取用該變數值(二維陣列)->可以參考手冊視圖說明
        // TODO: 需要用在admin.lists.blade.php的資料存在$data這個陣列內
        $data["searchData"] = $searchData;
        $data["sidebar_id"] = $this->sidebar_id;
        $data["perPage"] = $this->perPage;
        $data["permission"] = $this->permission;
        $data["title"] = $this->title;

        // TODO: 表示此模板檔案是放在resources/views/backend/admin/lists.blade.php，$data為指定要傳入模板的資料
        return view("backend.admin.lists", $data);
    }

    public function add(Request $request)
    {
        // TODO: 判斷是否有2B(新增權限)
        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();
        $data["adminGroup"] = AdminGroup::pluck('name', 'id');
        $data["title"] = $this->title;
        return view("backend.admin.forms", $data);
    }

    public function addPost(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        // TODO: 控制器驗證
        $this->validate(
            $request,
            [
                'name' => 'required',
                // TODO: 驗證從左到右
                // TODO: unique:table,column,except,idColumn
                //       -> 沒有column欄位則會使用欄位本身的名稱
                'email' => 'required|email|unique:admins', // TODO: admins資料表內，信箱欄位不能重複
                'password' => 'required',
                'is_active' => 'required|integer|max:1',
                'admin_group_id' => 'required|integer',
                // 'captcha' => 'required|captcha'
            ]
        );

        $data = $request->all();
        // TODO: 下方為批量賦值 -> 和 Eloquent model(Admin.php)的$fillable有關係
        $data["password"] = Hash::make($data["password"]);
        $data["created_id"] = Auth::user()->id;
        $data["updated_id"] = Auth::user()->id;
        // TODO: 新增新的管理員
        $newAdmin = Admin::create($data);

        if (isset($newAdmin->id)) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '新增成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '新增失敗');
        }

        return redirect("/backend/admin");
    }

    // TODO: 使用 Illuminate\Http\Request 型別提示，同時取得你的路由參數 id
    public function upd(Request $request, $id)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "C],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();
        // TODO: 找到該名使用者的資料
        $data["data"] = Admin::find($id);
        // echo '<pre>';
        // print_r($data['data']);
        // exit;
        $data["adminGroup"] = AdminGroup::pluck('name', 'id');
        $data["title"] = $this->title;
        return view("backend.admin.forms", $data);
    }

    public function updPost(Request $request, $id)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "C],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $this->validate(
            $request,
            [
                'name' => 'required',
                // FIXME: 為什麼？後方沒加入該名使用者id不能更新 -> 畫面顯示已註冊的信箱
                //        -> 為了要更新並且不會和前面的unique認證衝突
                //        -> unique:table,column,except,idColumn
                'email' => 'required|email|unique:admins,email,' . $id,
                //'password' => 'required',
                'is_active' => 'required|integer|max:1',
                'admin_group_id' => 'required|integer',
                // 'captcha' => 'required|captcha'
            ]
        );
        $data = $request->all();
        // echo '<pre>';
        // print_r($data);
        // exit;
        if (isset($data["password"]) && $data["password"]) {
            $data["password"] = Hash::make($data["password"]);
        } else {
            unset($data["password"]);
        }

        $data["updated_id"] = Auth::user()->id;
        $updAdmin = Admin::find($id)->update($data);

        if ($updAdmin) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '修改成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '修改失敗');
        }

        return redirect("/backend/admin");
    }

    public function delData(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "D],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $this->validate(
            $request,
            [
                'op' => 'required',
                'id' => 'required|integer',
                // 'captcha' => 'required|captcha'
            ]
        );

        $data = $request->all();

        if (isset($data["op"]) && $data["op"] == "del") {
            $deldata = array();
            // TODO: 取得誰刪除此筆資料
            $deldata["deleted_id"] = Auth::user()->id;
            Admin::find($data["id"])->update($deldata);
            Admin::destroy($data["id"]);
        }

        $request->session()->flash('statusCode', '0');
        $request->session()->flash('statusMsg', '刪除成功');
        return redirect()->back();
    }
}
