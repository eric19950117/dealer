{{-- TODO: 子模板繼承母模板 --}}
@extends('backend.shared.master')
@section('content')
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        {{-- TODO: 引入開頭頁面 --}}
        @include('backend.shared.page-head')
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i> 搜尋
                        </div>
                        <div class="tools"></div>
                    </div>
                    <div class="portlet-body">
                        {!! Form::model(isset($searchData) ? $searchData : array(), ['files' => true, "class" =>
                        "form-horizontal", "id"=>"searchForm", "method"=>"get"]) !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="keyword">關鍵字</label>
                                <div class="col-md-4">
                                    {!! Form::text('keyword', null, ['class' => 'form-control', "id"=> 'keyword',
                                    "placeholder"=>"可輸入姓名、電話、分店名稱"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">經銷商</label>
                                <div class="col-md-4">
                                    {{ Form::select('dealer_id', $dealers, null, ['id'=>'dealer_id', 'class'=>'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">分店</label>
                                <div class="col-md-4">
                                    {{-- {{ Form::select('branch_id', $branchs, null, ['id'=>'branch_id', 'class'=>'form-control']) }} --}}
                                    <select id="branch_id" class="form-control" disabled="disabled" name="branch_id">
                                        <option value="0">請選擇</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-1">
                                    <button type="submit" class="btn green" onclick="searchFormBtnClick(0)">搜尋</button>
                                </div>
                                @if (strrpos($permission, "[". $sidebar_id ."E],") !== false)
                                <div class="col-md-8">
                                    <button type="button" class="btn green" onclick="searchFormBtnClick(1)">匯出</button>
                                </div>
                                @endif
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>

                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-list font-dark"></i>
                            <span class="caption-subject bold uppercase">列表</span>
                        </div>
                        {{-- TODO: 判斷是否有新增權限 --}}
                        @if(strrpos($permission, "[".$sidebar_id."B],") !== false)
                        <div class="tools">
                            <button type="button" class="btn btn-info"
                                onclick="location.href = '/backend/client/add';">新增</button>
                        </div>
                        @endif

                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <thead>
                                <tr>
                                    <th> 編號 </th>
                                    <th> 經銷商 </th>
                                    <th> 分店名稱 </th>
                                    <th> 客戶名稱 </th>
                                    <th> 客戶電話 </th>
                                    <th> 功能 </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $key => $rs)
                                <tr>
                                    <td>
                                        {{ (((isset($searchData["page"])) ? $searchData["page"]: 1) - 1) * $perPage + $key + 1 }}
                                    </td>
                                    <td> {{ $rs->dealer_name }} </td>
                                    <td> {{ $rs->branch_name }} </td>
                                    <td> {{ $rs->client_name }} </td>
                                    <td> {{ $rs->phone_number }} </td>
                                    <td>
                                        {{-- TODO: 判斷是否有編輯和刪除權限 --}}
                                        @if(strrpos($permission, "[".$sidebar_id."C],") !== false)
                                        <a class="btn green btn-xs" href="/backend/client/upd/{{ $rs->id }}">編輯</a>
                                        @endif
                                        @if(strrpos($permission, "[".$sidebar_id."D],") !== false)
                                        <a class="btn btn-danger btn-xs" data-toggle="modal" href="#draggable"
                                            onclick="$('#id').val({{ $rs->id }})">刪除</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 text-center">
                            {!! $result->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
    </div>
</div>
<div class="modal fade draggable-modal" id="draggable" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">警告！</h4>
            </div>
            <div class="modal-body">請問確定刪除此筆資料？</div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-danger" onclick="$('#DeleteActions').submit();">刪除</button>
            </div>
            <form action="/backend/client/delData" method="post" id="DeleteActions">
                @csrf
                <input type="hidden" name="op" id="op" value="del" />
                <input type="hidden" name="id" id="id" />
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- END CONTENT -->
@endsection

@section('script')
    <script>
        function searchFormBtnClick(type){
            if(type){
                // alert(1);
                $("#searchForm").attr("action","/backend/client/exportExcel");
            }else{
                // alert(2);
                $("#searchForm").attr("action","/backend/client");
            }
            $("#searchForm").submit();
        }

        // TODO: laravel blade get url parameter -> blade頁面抓取網址參數的方法
        // TODO: 頁面上已經有取得 branch_id ， 可以使用controller內的 $data傳入的$searchData取值($request->all())
        // if(isset($searchData['branch_id']))

        @if ((app('request')->input('branch_id') ))
            var branch_id = '{{ app('request')->input('branch_id') }}';
        @else
            // TODO: branch_id 不能設為 null -> 在進入頁面網址沒有值得時候，沒有分店的經銷商會為空值
            //                              -> 原因jquery .val() 裡面要帶數字
            var branch_id = 0;
        @endif

        function api(dealer){
            $.ajax({
            url: '/backend/client/searchBranch',
            dataType: "json",
            type: "GET",
            data: {
                api: dealer
            },
            success: function (data) {

                $('#branch_id').html("<option value='0'>請選擇</option>");

                for (var i = 0; i < data.length; i++) {
                    var id = data[i].id;
                    var branch_name = data[i].branch_name;
                    // TODO: 判斷從網址取得的參數和迴圈取得的id是否一樣
                    // if(id == branch_id){
                    //     // console.log('branch_id', branch_id);
                    //     // console.log('id', id);
                    //     // console.log('//');
                    //     $('#branch_id').append("<option value='" + id + "' selected>" + branch_name + "</option>");
                    // }
                    // else{
                    //     // console.log('///');
                    //     // console.log('branch_id', branch_id);
                    //     // console.log('id', id);
                    //     $('#branch_id').append("<option value='" + id + "'>" + branch_name + "</option>");
                    // }
                    $('#branch_id').append("<option value='" + id + "'>" + branch_name + "</option>");
                }
                $('#branch_id').removeAttr("disabled");
                // TODO: jquery select set value ->  $("#sfs").val(3);  //設定值為3的選項被選取
                $('#branch_id').val(branch_id);
                // TODO: 讓 branch_id 歸零 -> 讓原本搜尋的分店資料歸零，在搜尋其他經銷商時才不會有bug
                branch_id = 0;
            }
        })
    }

    // function api(dealer){
    //         $.get("/backend/client/searchBranch?api=" + dealer,function(data){
    //             console.log(data);
    //             $('#branch_id').html("<option value='0'>請選擇</option>");
    //             for(var i=0;i< data.length;i++) {
    //                 var id = data[i].id;
    //                 var branch_name = data[i].branch_name;
    //                 $('#branch_id').append("<option value='" + id + "'>" + branch_name + "</option>")
    //             }
    //             $('#branch_id').removeAttr("disabled");
    //         });
    //     }

        $(function(){
            // TODO: 假如有選取經銷商，則會跑ajax
            if ($('#dealer_id').val() !== '0') {
                api($('#dealer_id').val())
            }
            // TODO: 判斷是否選擇經銷商
            $('#dealer_id').change(function(){
                if($('#dealer_id').val() === '0'){
                    $('#branch_id').attr("disabled",true);
                }else{
                    api($('#dealer_id').val())
                }
            });
        });

    </script>
@endsection


