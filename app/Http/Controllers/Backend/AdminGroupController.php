<?php

namespace App\Http\Controllers\Backend;

use App\AdminGroup;
use Illuminate\Http\Request;
use Auth;

class AdminGroupController extends MY_BackendController
{
    public $sidebar_id = 3; // 3A(查看) 3B(新增) 3C(刪除) 3D(修改) 皆為後台會員群組管理的權限
    public $permission = "";
    public $title = "後台會員群組管理";
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $this->permission = $permission = Auth::user()->adminGroup->permission;
            return $next($request);
        });
    }

    //列表
    public function lists(Request $request)
    {
        if (strrpos($this->permission, "[" . $this->sidebar_id . "A],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $searchData = $request->all();
        $data = array();
        $data["result"] = AdminGroup::getList($searchData)->paginate($this->perPage);
        $data["searchData"] = $searchData;
        $data["sidebar_id"] = $this->sidebar_id;
        $data["perPage"] = $this->perPage;
        $data["permission"] = $this->permission;
        $data["title"] = $this->title;
        return view("backend.admin_group.lists", $data);
    }

    public function add(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();
        $data["sidebarList"] = $this->getSidebarList();
        $data["title"] = $this->title;
        return view("backend.admin_group.forms", $data);
    }

    public function addPost(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $this->validate(
            $request,
            [
                'name' => 'required',
            ]
        );

        $data = $request->all();

        // TODO: implode(',', new array) -> 用逗號區隔陣列內的值
        $data["permission"] = implode(",", $data["permission"]) . ",";
        $data["created_id"] = Auth::user()->id;
        $data["updated_id"] = Auth::user()->id;
        $newAdminGroup = AdminGroup::create($data);

        if (isset($newAdminGroup->id)) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '新增成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '新增失敗');
        }

        return redirect("/backend/admingroup");
    }

    public function upd(Request $request, $id)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "C],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();
        $data["data"] = AdminGroup::find($id);
        $data["sidebarList"] = $this->getSidebarList();
        $data["title"] = $this->title;
        return view("backend.admin_group.forms", $data);
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
            ]
        );
        $data = $request->all();
        $data["permission"] = implode(",", $data["permission"]) . ",";
        $data["updated_id"] = Auth::user()->id;
        $updAdminGroup = AdminGroup::find($id)->update($data);

        if ($updAdminGroup) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '修改成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '修改失敗');
        }

        return redirect("/backend/admingroup");
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
            $deldata["deleted_id"] = Auth::user()->id;
            AdminGroup::find($data["id"])->update($deldata);
            AdminGroup::destroy($data["id"]);
        }

        $request->session()->flash('statusCode', '0');
        $request->session()->flash('statusMsg', '刪除成功');
        return redirect()->back();
    }
}
