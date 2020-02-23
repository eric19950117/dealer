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
                        <!-- BEGIN FORM-->
                        {{-- TODO: 當使用 Form::model 方法時，必須確保有使用 Form::close 方法來關閉表單！--}}
                        {{-- TODO: 'files' => true -> 表單允許上傳檔案 --}}
                        {{-- FIXME: 下方程式寫法不能理解 --}}
                        {{-- TODO: 下方為if else縮寫，假如$searchData有值，則印出，沒有的話則會顯示陣列--}}
                        {!! Form::model(isset($searchData) ? $searchData : array(), ['files' => true, "class" =>
                        "form-horizontal", "id"=>"searchForm", "method"=>"get"]) !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="keyword">關鍵字</label>
                                <div class="col-md-4">
                                    {{-- FIXME: 為什麼中間參數要加入null --}}
                                    {{-- TODO: null->為預設值(即不會有值在輸入框內) --}}
                                    {{-- TODO: hidden 和 textarea 方法和 text 方法使用屬性參數是相同的 --}}
                                    {!! Form::text('keyword', null, ['class' => 'form-control', "id"=> 'keyword',
                                    "placeholder"=>"可輸入帳號、姓名"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">群組</label>
                                <div class="col-md-4">
                                    {{ Form::select('admin_group_id', $adminGroup, null, ['id'=>'admin_group_id', 'class'=>'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">搜尋</button>
                                </div>
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
                                onclick="location.href = '/backend/admin/add';">新增</button>
                        </div>
                        @endif

                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <thead>
                                <tr>
                                    <th> 編號 </th>
                                    <th> 帳號 </th>
                                    <th> 姓名 </th>
                                    <th> 群組 </th>
                                    <th> 狀態 </th>
                                    <th> 功能 </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $key => $rs)
                                <tr>
                                    <td>
                                        {{-- FIXME: $searchData['page']怎麼得到的 --}}
                                        {{ (((isset($searchData["page"])) ? $searchData["page"]: 1) - 1) * $perPage + $key + 1 }}
                                    </td>
                                    <td> {{ $rs->email }} </td>
                                    <td> {{ $rs->name }} </td>
                                    <td> {{ $rs->admin_groups_name }} </td>
                                    <td> {{ ($rs->is_active) ? "啟用" : "停用" }} </td>
                                    <td>
                                        {{-- TODO: 判斷是否有編輯和刪除權限 --}}
                                        @if(strrpos($permission, "[".$sidebar_id."C],") !== false)
                                        <a class="btn green btn-xs" href="/backend/admin/upd/{{ $rs->id }}">編輯</a>
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
                        {{-- http://127.0.0.1:8000/backend/admin?admin_group_id=1&page=2 --}}
                        {{-- http://127.0.0.1:8000/backend/admin?page=2 --}}
                        <div class="col-md-12 col-sm-12 text-center">
                            {{-- FIXME: 不懂用法 -> appends -> 加入參數到分頁連結中 --}}
                            {{-- TODO: links() -> 將分頁結果顯示在畫面上 --}}
                            {!! $result->appends(request()->input())->links() !!}
                            {{-- {{ $result->links() }} --}}
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
            <form action="/backend/admin/delData" method="post" id="DeleteActions">
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
