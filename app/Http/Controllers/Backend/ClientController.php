<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Client;
use App\Dealer;
use Illuminate\Http\Request;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $data["dealers"] = array_merge([0 => "請選擇"], Dealer::pluck('dealer_name', 'id')->toArray());
        $data["branchs"] = array_merge([0 => "請選擇"], Branch::pluck('branch_name', 'id')->toArray());

        // FIXME: 把selector的disabled拿掉會發現分店會抓到全部的資料(bug) -> 所以要用下面方法將經銷商對應的分店資料取出來
        // TODO: 下方為使用From model送出資料後，填入原先的查詢資訊的方法
        // $data["branchs"] = [0 => "請選擇"]; // 此為預設值 -> 沒有的話進入頁面的時候會噴錯 (在view那邊會得不到$branchs的值)

        // TODO: 判斷經銷商的值是否存在和是否有選擇經銷商
        // if (isset($searchData["dealer_id"]) && $searchData["dealer_id"] != 0) {
        //     $data["branchs"] = array_merge([0 => "請選擇"], Branch::where("dealer_releated", $searchData["dealer_id"])->pluck('branch_name', 'id')->toArray());
        // }


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
        $searchData = $request->all();
        $data["searchData"] = $searchData;

        // TODO: 篩選有分店的經銷商
        $data['dealers'] = Dealer::join('branchs', function ($join) {
            $join->on('branchs.dealer_releated', 'dealers.id');
            $join->whereNull('branchs.deleted_at');
        })->pluck('dealer_name', 'dealers.id');
        // TODO: laravel collection get key of first item
        // dd($data['dealers']);
        $data["branchs"] = Branch::where('dealer_releated', $data['dealers']->keys()->first())->pluck('branch_name', 'id');


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
                'branch_releated' => 'required|integer',
                'dealer_releated' => 'required|integer',
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

        return redirect("/backend/client/lists");
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

        $data["dealers"] = Dealer::join('branchs', function ($join) {
            $join->on('branchs.dealer_releated', '=', 'dealers.id');
            $join->whereNull('branchs.deleted_at');
        })->pluck('dealer_name', 'dealers.id');

        // echo $data['data']['dealer_releated'];
        // exit;

        // dd($data['dealers']);

        // FIXME: 這邊的寫法和上面新增不一樣  $data['data']['dealer_releated'] 抓到客戶所對應的經銷商
        $data['branchs'] = Branch::where('dealer_releated', $data['data']['dealer_releated'])->pluck('branch_name', 'id');

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
                'branch_releated' => 'required|integer',
                'dealer_releated' => 'required|integer',
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

        return redirect("/backend/client/lists");
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

    public function searchBranch(Request $request)
    {
        $branch = Branch::select('id', 'branch_name')->where('dealer_releated', '=', $request->api)->get()->toArray();
        // dd($branch);
        return json_encode($branch);
    }

    public function exportExcel(Request $request)
    {
        $searchData = $request->all();
        $result = Client::getList($searchData)->get()->toArray();
        $dt = Carbon::now();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $intTitle = array(
            'A' => '編號',
            'B' => '經銷商',
            'C' => '分店名稱',
            'D' => '客戶名稱',
            'E' => '客戶電話',
        );

        $intValue = array(
            'A' => 'id',
            'B' => 'dealer_name',
            'C' => 'branch_name',
            'D' => 'client_name',
            'E' => 'phone_number',
        );

        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', '客戶管理');

        foreach ($intTitle as $column => $va) {
            $sheet->setCellValue($column . '2', $va);
        }

        $i = 2;
        foreach ($result as $key => $rs) {
            $i++;
            foreach ($intValue as $column => $va) {
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
