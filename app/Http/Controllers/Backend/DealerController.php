<?php

namespace App\Http\Controllers\Backend;

use App\Admin; // Eloquent ORM
use App\Dealer;
use App\AdminGroup; // Eloquent ORM
use Illuminate\Http\Request; // Http請求 -> 繼承了 Symfony\Component\HttpFoundation\Request 類別
use Auth; // 此檔案在config/auth.php -> 認證用

class DealerController extends MY_BackendController
{

    public $sidebar_id = 5;
    public $permission = "";
    public $title = "經銷商管理";

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $this->permission = $permission = Auth::user()->adminGroup->permission;
            // TODO: 呼叫帶有$request的$next方法，即可將請求傳遞到更深層的應用程式(允許「通過」中介層)
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
        $data["result"] = Dealer::getList($searchData)->paginate($this->perPage);
        $data["searchData"] = $searchData;
        $data["sidebar_id"] = $this->sidebar_id;
        $data["perPage"] = $this->perPage;
        $data["permission"] = $this->permission;
        $data["title"] = $this->title;

        return view("backend.dealer.lists", $data);
    }

    public function add(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();

        $data['title'] = $this->title;

        return view('backend.dealer.forms', $data);
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
                'dealer_name' => 'required',
            ]
        );

        $data = $request->all();

        $data["created_id"] = Auth::user()->id;
        $data["updated_id"] = Auth::user()->id;

        $newDealer = Dealer::create($data);

        if (isset($newDealer->id)) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '新增成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '新增失敗');
        }

        return redirect("/backend/dealer");
    }

    public function upd(Request $request, $id)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();

        $data['title'] = $this->title;
        $data['data'] = Dealer::find($id);

        return view("backend.dealer.forms", $data);
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
                'dealer_name' => 'required|unique:dealers,dealer_name,' . $id,
            ]
        );

        $data = $request->all();

        $data["updated_id"] = Auth::user()->id;
        $updCompany = Dealer::find($id)->update($data);

        if ($updCompany) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '修改成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '修改失敗');
        }

        return redirect("/backend/dealer");
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
            $deldata["deleted_id"] = Auth::user()->id;
            Dealer::find($data["id"])->update($deldata);
            Dealer::destroy($data["id"]);
        }

        $request->session()->flash('statusCode', '0');
        $request->session()->flash('statusMsg', '刪除成功');
        return redirect()->back();
    }
}
