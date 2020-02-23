@extends('backend.shared.master')
@section('content')
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>{{$title}}
                    <small></small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
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
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        {{-- TODO: Form:model 預設方法為POST --}}
                        {!! Form::model(isset($data) ? $data : array(), ['files' => true, "class" => "form-horizontal",
                        "id"=>"form_sample_1"]) !!}
                        <div class="form-body">
                            <div class="alert alert-danger display-hide basic-error">
                                <button class="close" data-close="alert"></button> 表單驗證失敗，請再次檢查. </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button> 表單驗證成功! </div>
                            {{-- FIXME: 下方$errors從哪邊得到的？ --}}
                            {{-- 從控制器驗證後得到的錯誤訊息會自動快閃到session --}}
                            @if($errors->has(null))
                            {{-- TODO: 取出所有欄位的所有錯誤訊息 --}}
                            @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button> {{ $error }} </div>
                            @endforeach
                            @endif

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="branch_name">店名：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    {!! Form::text('branch_name', null, ['class' => 'form-control', "id"=> 'branch_name']) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">請輸入店名</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="phone_number"">電話：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">

                                        {!! Form::text('phone_number', null, ['class' => 'form-control', "id"=> 'phone_number']) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">請輸入電話</span>

                                </div>
                            </div>

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="address">地址：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    {!! Form::text('address', null, ['class' => 'form-control', "id"=> 'address']) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">請輸入地址</span>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="gui_number">統編：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    {!! Form::text('gui_number', null, ['class' => 'form-control', "id"=> 'gui_number']) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">請輸入統一編號</span>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="dealer_releated">選擇經銷商：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    {{-- @php
                                        echo '<pre>';
                                        print_r($dealers);
                                        exit;
                                    @endphp --}}
                                    {{ Form::select('dealer_releated', $dealers, null, ['id'=>'dealer_releated', 'class'=>'form-control']) }}
                                    <div class="form-control-focus"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">送出</button>
                                    <button type="button" class="btn default" onclick="location.href = '/backend/dealer/branch/lists';">取消</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
                <!-- END VALIDATION STATES-->
            </div>
        </div>
    </div>
</div>

<!-- END CONTENT -->
@endsection
@section('script')
<script>
    var FormValidationMd = function() {

        var handleValidation1 = function() {
            // for more info visit the official plugin documentation:
            // h/docs.jquery.com/Plugins/Validationttp:/
            var form1 = $('#form_sample_1');
            // TODO: var allError = $('#form_sample_1 .alert-danger') 和下方寫法用法一樣
            var allError = $('.alert-danger', form1); //FIXME: 為什麼要加入第二個欄位 -> 為了要響應表單嗎？
            var error1 = $('.basic-error', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                // TODO: help-block會有錯誤的顏色 help-block-error會有錯誤訊息跑出來
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                messages: {
                    branch_name: "請輸入店名",
                    phone_number: "請輸入電話",
                    gui_number: {
                        required: "請輸入信箱",
                        rangelength: "請輸入正確的統編格式"
                    },
                    address: "請輸入地址",
                    dealer_releated: "請選擇經銷商",
                },
                rules: {
                    branch_name: {
                        required: true
                    },
                    phone_number: {
                        required: true
                    },
                    gui_number: {
                        required: true,
                        rangelength:[8,8], // TODO: 必须输入正确格式的电子邮件。
                    },
                    address: {
                        required: true
                    },
                    dealer_releated: {
                        required: true
                    }
                },
                invalidHandler: function(event, validator) { //display error alert on form submit
                    success1.hide();
                    allError.hide();
                    // TODO: 顯示表單驗證失敗的錯誤訊息
                    error1.show();
                    App.scrollTo(error1, -200);
                },

                errorPlacement: function(error, element) {
                    if (element.is(':checkbox')) {
                        error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                    } else if (element.is(':radio')) {
                        error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                highlight: function(element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function(element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function(label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                /*submitHandler: function(form) {

                    success1.show();
                    error1.hide();

                }*/
            });
        }
        return {
            //main function to initiate the module
            init: function() {
                handleValidation1();
            }
        };
    }();

    jQuery(document).ready(function() {
        FormValidationMd.init();
    });
</script>
@endsection
