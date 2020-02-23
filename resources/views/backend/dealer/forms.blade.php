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
                        {!! Form::model(isset($data) ? $data : array(), ['files' => true, "class" => "form-horizontal",
                        "id"=>"form_sample_1"]) !!}
                        <div class="form-body">
                            <div class="alert alert-danger display-hide basic-error">
                                <button class="close" data-close="alert"></button> 表單驗證失敗，請再次檢查. </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button> 表單驗證成功! </div>
                            {{-- FIXME: 下方$errors從哪邊得到的？ --}}
                            @if($errors->has(null))
                            @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button> {{ $error }} </div>
                            @endforeach
                            @endif

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="dealer_name">經銷商名稱：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    {{-- TODO: 下方 'name' 為從$data取得的資料 --}}
                                    {!! Form::text('dealer_name', null, ['class' => 'form-control', "id"=> 'dealer_name']) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">請輸入經銷商名稱</span>
                                </div>
                            </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">送出</button>
                                    <button type="button" class="btn default" onclick="location.href = '/backend/dealer/lists';">取消</button>
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
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#form_sample_1');
            var allError = $('.alert-danger', form1);
            var error1 = $('.basic-error', form1);

            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                messages: {
                    dealer_name: "請輸入經銷商名稱",
                },
                rules: {
                    dealer_name: {
                        required: true
                    },
                },
                invalidHandler: function(event, validator) { //display error alert on form submit
                    success1.hide();
                    allError.hide();
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
