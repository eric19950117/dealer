<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Client;
use Illuminate\Http\Request;
use Auth;

class ClientController extends MY_BackendController
{

    public $sidebar_id = 7;
    public $permission = '';
    public $title = '客戶管理';

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

        $data = array();
        $data["result"] = Client::getList($searchData)->paginate($this->perPage);
        $data["branchs"] = array_merge([0 => "請選擇"], Branch::pluck('branch_name', 'id')->toArray());
        $data["searchData"] = $searchData;
        $data["sidebar_id"] = $this->sidebar_id;
        $data["perPage"] = $this->perPage;
        $data["permission"] = $this->permission;
        $data["title"] = $this->title;

        return view("backend.client.lists", $data);
    }

    public function add(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();

        

        $data["branchs"] = Branch::pluck('branch_name', 'id');

        $data['title'] = $this->title;

        return view('backend.client.forms', $data);
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
                'client_name' => 'required',
                'phone_number' => 'required',
                'branch_releated' => 'required',
            ]
        );

        $data = $request->all();
        $data["created_id"] = Auth::user()->id;
        $data["updated_id"] = Auth::user()->id;
        $newClient = Client::create($data);

        if (isset($newClient->id)) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '新增成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '新增失敗');
        }

        return redirect("/backend/dealer/client/lists");
    }

    public function upd(Request $request, $id)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();
        $data['data'] = Client::find($id);
        $data['branchs'] = Branch::pluck('branch_name', 'id');
        $data['title'] = $this->title;

        return view("backend.client.forms", $data);
    }

    public function updPost(Request $request, $id)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $this->validate(
            $request,
            [
                'client_name' => 'required',
                'phone_number' => 'required',
                'branch_releated' => 'required',
            ]
        );

        $data = $request->all();

        $data['updated_id'] = Auth::user()->id;
        $updClient = Client::find($id)->update($data);


        if ($updClient) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '修改成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '修改失敗');
        }

        return redirect("/backend/dealer/client/lists");
    }

    public function delData(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
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
            Client::find($data["id"])->update($deldata);
            Client::destroy($data["id"]);
        }

        $request->session()->flash('statusCode', '0');
        $request->session()->flash('statusMsg', '刪除成功');
        return redirect()->back();
    }
}
