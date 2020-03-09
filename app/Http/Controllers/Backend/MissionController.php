<?php

namespace App\Http\Controllers\Backend;

use App\Dealer;
use App\Branch;
use App\Client;
use App\Mission;
use App\Admin;
use Illuminate\Http\Request;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MissionController extends MY_BackendController
{
    public $sidebar_id = 8;
    public $permission = '';
    public $title = '任務分配';
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

        return view("backend.mission_ajax.lists", $data);
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

        // TODO: 篩選有分店的經銷商
        $data['dealers'] = Dealer::join('branchs', function ($join) {
            $join->on('branchs.dealer_releated', 'dealers.id');
            $join->whereNull('branchs.deleted_at');
        })->pluck('dealer_name', 'dealers.id');

        $data["branchs"] = Branch::where('dealer_releated', $data['dealers']->keys()->first())->pluck('branch_name', 'id');
        // dd($data['branchs']);
        $data["clients"] = Client::where('branch_releated', $data['branchs']->keys()->first())->pluck('client_name', 'id');

        $data['admins'] = Admin::pluck('name', 'id');
        $data['title'] = $this->title;

        return view('backend.mission_ajax.forms', $data);
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

    public function exportExcel(Request $request)
    {
        $searchData = $request->all();
        $data = array();
        $result = Mission::getList($searchData)->get()->toArray();
        $dt = Carbon::now();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $initTitle = array(
            'A' => '編號',
            'B' => '經銷商',
            'C' => '分店名稱',
            'D' => '客戶名稱',
            'E' => '指派人員',
            'F' => '任務名稱',
            'G' => '任務內容',
        );
        // echo '<pre>';
        // print_r($result);
        // exit;

        $initValue = array(
            'A' => 'id',
            'B' => 'dealer_name',
            'C' => 'branch_name',
            'D' => 'client_name',
            'E' => 'admin_name',
            'F' => 'mission_name',
            'G' => 'mission_content',
        );

        foreach ($initTitle as $column => $va) {
            $sheet->setCellValue($column . '1', $va);
        }

        $i = 1;
        foreach ($result as $key => $rs) {
            $i++;
            foreach ($initValue as $column => $va) {
                $sheet->setCellValue($column . $i, $rs[$va]);
            }
        }

        $fileName = "匯出" . $dt->year . $dt->month . $dt->day . $dt->hour . $dt->minute . $dt->second . rand(0, 32767);
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($spreadsheet) {
            // $spreadsheet = //create you spreadsheet here;
            $writer =  new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $streamedResponse->setStatusCode(200);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '.xlsx"');
        return $streamedResponse->send();
    }
}
