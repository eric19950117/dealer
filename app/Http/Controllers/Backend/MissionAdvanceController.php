<?php

namespace App\Http\Controllers\Backend;

use App\Dealer;
use App\Branch;
use App\Client;
use App\Mission;
use App\Admin;
use Illuminate\Http\Request;
use Auth;

class MissionAdvanceController extends MY_BackendController
{
    public $sidebar_id = 9;
    public $permission = '';
    public $title = '進階任務分配';
    public $admin = '';

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
        $data["result"] = Mission::getList($searchData)->paginate($this->perPage);
        $data["dealers"] = array_merge([0 => "請選擇"], Dealer::pluck('dealer_name', 'id')->toArray());
        $data["branchs"] = array_merge([0 => "請選擇"], Branch::pluck('branch_name', 'id')->toArray());
        $data["clients"] = array_merge([0 => "請選擇"], Client::pluck('client_name', 'id')->toArray());

        $data["searchData"] = $searchData;
        $data["sidebar_id"] = $this->sidebar_id;
        $data["perPage"] = $this->perPage;
        $data["permission"] = $this->permission;
        $data["title"] = $this->title;

        return view("backend.mission_advance.lists", $data);
    }

    public function add(Request $request)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();
        $searchData = $request->all();
        $data["searchData"] = $searchData;

        $mission_advance = array();
        $dealer_info = Dealer::get()->toArray();
        $branch_info = Branch::get()->toArray();
        $client_info = Client::get()->toArray();

        foreach ($dealer_info as $key => $va) {
            $mission_advance[$va['id']] = $va;
        }

        // dd($dealer_info);
        // dd($branch_info);
        // dd($client_info);
        // echo '<pre>';
        // print_r($mission_advance);
        // exit;
        foreach ($branch_info as $key => $va) {
            if (isset($mission_advance[$va['dealer_releated']])) {
                $mission_advance[$va["dealer_releated"]]["branchs"][$va["id"]] = $va;
            }
        }

        foreach ($client_info as $key => $va) {
            if (isset($mission_advance[$va['dealer_releated']]["branchs"][$va['branch_releated']])) {
                $mission_advance[$va['dealer_releated']]["branchs"][$va['branch_releated']]['clients'][$va['id']] = $va;
            }
        }

        // echo '<pre>';
        // print_r($mission_advance);
        // exit;
        $data['mission_advance'] = $mission_advance;
        $data['admins'] = Admin::pluck('name', 'id');
        $data['title'] = $this->title;

        return view('backend.mission_advance.forms', $data);
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
                'mission_name' => 'required',
                'mission_content' => 'required',
                'dealer_releated' => 'required|integer',
                'branch_releated' => 'required|integer',
                'client_releated' => 'required|integer',
                'admin_releated' => 'required|integer',
            ]
        );

        $data = $request->all();
        $data["created_id"] = Auth::user()->id;
        $data["updated_id"] = Auth::user()->id;
        $newMission = Mission::create($data);

        if (isset($newMission->id)) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '新增成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '新增失敗');
        }

        return redirect("/backend/mission/lists");
    }

    public function upd(Request $request, $id)
    {

        if (strrpos($this->permission, "[" . $this->sidebar_id . "B],") === false) {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '權限不足');
            return redirect("/backend");
        }

        $data = array();
        $data['data'] = Mission::find($id);

        $data["dealers"] = Dealer::join('branchs', function ($join) {
            $join->on('branchs.dealer_releated', '=', 'dealers.id');
            $join->whereNull('branchs.deleted_at');
        })->pluck('dealer_name', 'dealers.id');

        // FIXME: 這邊的寫法和上面新增不一樣  $data['data']['dealer_releated'] 抓到客戶所對應的經銷商
        $data['branchs'] = Branch::where('dealer_releated', $data['data']['dealer_releated'])->pluck('branch_name', 'id');
        $data['clients'] = Client::where('branch_releated', $data['data']['branch_releated'])->pluck('client_name', 'id');
        $data['admins'] = Admin::pluck('name', 'id');

        $data['title'] = $this->title;

        return view("backend.mission_ajax.forms", $data);
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
                'mission_name' => 'required',
                'mission_content' => 'required',
                'dealer_releated' => 'required|integer',
                'branch_releated' => 'required|integer',
                'client_releated' => 'required|integer',
                'admin_releated' => 'required|integer',
            ]
        );

        $data = $request->all();

        $data['updated_id'] = Auth::user()->id;
        $updClient = Mission::find($id)->update($data);


        if ($updClient) {
            $request->session()->flash('statusCode', '0');
            $request->session()->flash('statusMsg', '修改成功');
        } else {
            $request->session()->flash('statusCode', '3');
            $request->session()->flash('statusMsg', '修改失敗');
        }

        return redirect("/backend/mission/lists");
    }

    public function searchBranch(Request $request)
    {
        $branch = Branch::select('id', 'branch_name')->where('dealer_releated', '=', $request->api_branch)->get()->toArray();

        return json_encode($branch);
    }

    public function searchClient(Request $request)
    {
        $client = Client::select('id', 'client_name')->where('branch_releated', '=', $request->api_client)->get()->toArray();
        // dd($branch);
        return json_encode($client);
    }
}
