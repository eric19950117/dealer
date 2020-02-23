<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <!-- BEGIN PAGE TITLE -->
    <div class="page-title">
        <h1>{{$title}}
            <small></small>
        </h1>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE BREADCRUMB -->
<ul class="page-breadcrumb breadcrumb">
    <li>
        <a href="/backend/">Home</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span class="active">{{$title}}</span>
    </li>
</ul>
<!-- END PAGE BREADCRUMB -->
<!-- BEGIN PAGE BASE CONTENT -->
@php
    $isError = true;
    // TODO: 取回項目值
    if(Session::has('statusCode')){
        $statusCode = Session::get('statusCode');
        $statusMsg = Session::get('statusMsg');
        if($statusCode == 0){
            $statusHtmlText = "alert-success";
        }elseif($statusCode == 1){
            $statusHtmlText = "alert-info";
        }elseif($statusCode == 2){
            $statusHtmlText = "alert-warning";
        }elseif($statusCode == 3){
            $statusHtmlText = "alert-danger";
        }
    }else{
        $isError = false;
    }
@endphp

@if($isError)
<div class="m-heading-1 border-green m-bordered">
    {{-- <h3>DataTables Rowreorder Extension</h3>
    <p> RowReorder adds the ability for rows in a DataTable to be reordered through user interaction with the table (click and drag / touch and drag). </p>
    <p> For more info please check out
        <a class="btn red btn-outline" href="http://datatables.net/extensions/rowreorder" target="_blank">the official documentation</a>
    </p> --}}

    <div class="alert {{$statusHtmlText}}">{{$statusMsg}}</div>
</div>
@endif
