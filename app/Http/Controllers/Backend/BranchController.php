<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Dealer;
use Illuminate\Http\Request;
use Auth;

class BranchController extends MY_BackendController
{

    public $sidebar_id = 6;
    public $permission = "";
    public $title = "分店管理";

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $this->permission = $permission = Auth::user()->adminGroup->permission;

            return $next($request);
        });
    }

    public function lists(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "A],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $searchData = $request->all();
        // echo '<pre>';
        // print_r($searchData);
        // exit;
        $data = array();
        $data["result"] = Branch::getList($searchData)->paginate($this->perPage);
        $data["dealers"] = array_merge([0 => "請選擇"], Dealer::pluck('dealer_name', 'id')->toArray());
        $data["searchData"] = $searchData;
        $data["sidebar_id"] = $this->sidebar_id;
        $data["perPage"] = $this->perPage;
        $data["permission"] = $this->permission;
        $data["title"] = $this->title;

        return view("backend.branch.lists", $data);
    }

    public function add(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();

        $data["dealers"] = Dealer::pluck('dealer_name', 'id')->toArray();

        $data['title'] = $this->title;

        return view('backend.branch.forms', $data);
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
                'branch_name' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
                // FIXME: 如何限制統編為八個數字
                // FIXME: 刪除後的經銷商統編和要新增的經銷商統編一樣的話會有bug
                'gui_number' => 'required|integer|unique:branchs,gui_number,null,null,deleted_at,null',
                'dealer_releated' => 'required|integer',
            ]
        );

        $data = $request->all();
        // TODO: 下方為批量賦值 -> 和 Eloquent model(Admin.php)的$fillable有關係
        $data["created_id"] = Auth::user()->id;
        $data["updated_id"] = Auth::user()->id;
        // TODO: 新增新的管理員
        $newAdmin = Branch::create($data);

        if (isset($newAdmin->id)) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '新增成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '新增失敗');
        }

        return redirect("/backend/branch/lists");
    }

    public function upd(Request $request, $id)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "C],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();
        // TODO: 找到該名使用者的資料
        $data["data"] = Branch::find($id);
        $data["dealers"] = Dealer::pluck('dealer_name', 'id');
        $data["title"] = $this->title;
        return view("backend.branch.forms", $data);
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
                'branch_name' => 'required',
                // FIXME: 刪除後的經銷商統編和要編輯的經銷商統編一樣的話會有bug
                'gui_number' => 'required|integer|unique:branchs,gui_number,null,' . $id . ',deleted_at,null',
                'phone_number' => 'required',
                'dealer_releated' => 'required|integer',
                'address' => 'required',
            ]
        );
        $data = $request->all();

        $data["updated_id"] = Auth::user()->id;
        $updBranch = Branch::find($id)->update($data);

        if ($updBranch) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '修改成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '修改失敗');
        }

        return redirect("/backend/dealer/branch");
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
            ]
        );

        $data = $request->all();

        if (isset($data["op"]) && $data["op"] == "del") {
            $deldata = array();
            // TODO: 取得誰刪除此筆資料
            $deldata["deleted_id"] = Auth::user()->id;
            Branch::find($data["id"])->update($deldata);
            Branch::destroy($data["id"]);
        }

        $request->session()->flash('statusCode', '0');
        $request->session()->flash('statusMsg', '刪除成功');
        return redirect()->back();
    }
}
