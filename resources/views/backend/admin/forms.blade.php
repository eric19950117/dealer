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
                                <label class="col-md-3 control-label" for="name">姓名：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    {{-- TODO: 下方 'name' 為從$data取得的資料 --}}
                                    {!! Form::text('name', null, ['class' => 'form-control', "id"=> 'name']) !!}
                                    <div class="form-control-focus"> </div>
                                    {{-- TODO: 下方為輸入時會給的提示字 --}}
                                    <span class="help-block">請輸入姓名</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="email">Email：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                        {{-- TODO: $data有值則會顯示email('readonly' -> 不能更改)--}}
                                        {!! Form::text('email', null, (isset($data)) ? ['class' => 'form-control',
                                        "id"=> 'email', "readonly"=>""] : ['class' => 'form-control', "id"=> 'email'])
                                        !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">請輸入信箱</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="password">密碼：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" placeholder="" name="password" id="password">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">請輸入密碼</span>
                                </div>
                            </div>

                            <div class="form-group form-md-radios">
                                <label class="col-md-3 control-label" for="">狀態：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    <div class="md-radio-inline">
                                        <div class="md-radio">
                                            {{-- TODO: 下方假如不寫true/false -> 會有不能選取的狀況發生 --}}
                                            {{ Form::radio('is_active', '1', true, ['id'=>'is_active_1', 'class'=>'md-radiobtn']) }}
                                            <label for="is_active_1">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 啟用 </label>
                                        </div>
                                        <div class="md-radio">
                                            {{ Form::radio('is_active', '0', false, ['id'=>'is_active_0', 'class'=>'md-radiobtn']) }}
                                            <label for="is_active_0">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 停用 </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="admin_group_id">群組：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    {{ Form::select('admin_group_id', $adminGroup, null, ['id'=>'admin_group_id', 'class'=>'form-control']) }}
                                    <div class="form-control-focus"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">送出</button>
                                    <button type="button" class="btn default" onclick="location.href = '/backend/admin';">取消</button>
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
                    name: "請輸入姓名",
                    password: "請輸入密碼",
                    email: {
                        required: "請輸入信箱",
                        email: "請輸入正確的信箱"
                    },
                    is_active: "請選擇狀態",
                    admin_group_id: "請選擇群組",
                },
                rules: {
                    name: {
                        required: true // TODO: 必须输入的字段
                    },
                    @if(!isset($data)) // FIXME: 有問題 -> 在編輯的時候不需要驗證有沒有輸入密碼(因為該名使用者原本就有密碼了)
                    password: {
                        required: true
                    },
                    @endif
                    email: {
                        required: true,
                        email: true // TODO: 必须输入正确格式的电子邮件。
                    },
                    admin_group_id: {
                        required: true
                    },
                    is_active: {
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
