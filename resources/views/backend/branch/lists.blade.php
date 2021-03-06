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
                                    "placeholder"=>"可輸入店名、電話、地址、統編"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">經銷商</label>
                                <div class="col-md-4">
                                    {{ Form::select('dealer_id', $dealers, null, ['id'=>'dealer_id', 'class'=>'form-control']) }}
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
                                onclick="location.href = '/backend/branch/add';">新增</button>
                        </div>
                        @endif

                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <thead>
                                <tr>
                                    <th> 編號 </th>
                                    <th> 店名 </th>
                                    <th> 電話 </th>
                                    <th> 地址 </th>
                                    <th> 經銷商 </th>
                                    <th> 統編 </th>
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
                                    <td> {{ $rs->branch_name }} </td>
                                    <td> {{ $rs->phone_number }} </td>
                                    <td> {{ $rs->address }} </td>
                                    <td> {{ $rs->dealer_name }} </td>
                                    <td> {{ $rs->gui_number }} </td>
                                    <td>
                                        {{-- TODO: 判斷是否有編輯和刪除權限 --}}
                                        @if(strrpos($permission, "[".$sidebar_id."C],") !== false)
                                        <a class="btn green btn-xs" href="/backend/branch/upd/{{ $rs->id }}">編輯</a>
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
            <form action="/backend/branch/delData" method="post" id="DeleteActions">
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
