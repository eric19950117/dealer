@extends('backend.shared.master')
{{-- TODO: 下方@section和@endsection將中間的資料傳入母模板--}}
{{-- TODO: content -> 指定傳入母模板變數名稱 --}}
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
                        {!! Form::model(array(), ['files' => true, "class" => "form-horizontal", "id"=>"form_sample_1"])
                        !!}
                        <div class="form-body">
                            <div class="alert alert-danger display-hide basic-error">
                                <button class="close" data-close="alert"></button> 表單驗證失敗，請再次檢查. </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button> 表單驗證成功! </div>

                            @if($errors->has(null))
                            @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button> {{ $error }} </div>
                            @endforeach
                            @endif

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="name">名稱：
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    {!! Form::text('name', isset($data) ? $data["name"] : null, ['class' =>
                                    'form-control', "id"=> 'name']) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">請輸入名稱</span>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="name">權限：
                                    <span class="required">*</span>
                                </label>
                            </div>
                            @foreach($sidebarList as $id => $va)
                            <div class="form-group form-md-checkboxes">
                                <label class="col-md-3 control-label" for="form_control_1">{{ $va["name"] }}：</label>
                                <div class="col-md-9">
                                    <div class="md-checkbox-inline">
                                        <div class="md-checkbox">
                                            <input type="checkbox" id="Z-{{ $va["id"] }}" class="md-check">
                                            <label for="Z-{{ $va["id"] }}">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 全選 </label>
                                        </div>
                                        <div class="md-checkbox">
                                            {{Form::checkbox('permission[]', "[".$va["id"]."A]", isset($data) ? strrpos($data["permission"], "[".$va["id"]."A],") !== false : null, ['class' => 'form-control', "id"=> $va["id"]."A"])}}

                                            <label for="{{ $va["id"] }}A">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 查看 </label>
                                        </div>
                                        <div class="md-checkbox">
                                            {{Form::checkbox('permission[]', "[".$va["id"]."B]", isset($data) ? strrpos($data["permission"], "[".$va["id"]."B],") !== false : null, ['class' => 'form-control', "id"=> $va["id"]."B"])}}
                                            <label for="{{ $va["id"] }}B">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 新增 </label>
                                        </div>
                                        <div class="md-checkbox">
                                            {{Form::checkbox('permission[]', "[".$va["id"]."C]", isset($data) ? strrpos($data["permission"], "[".$va["id"]."C],") !== false : null, ['class' => 'form-control', "id"=> $va["id"]."C"])}}
                                            <label for="{{ $va["id"] }}C">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 修改 </label>
                                        </div>
                                        <div class="md-checkbox">
                                            {{Form::checkbox('permission[]', "[".$va["id"]."D]", isset($data) ? strrpos($data["permission"], "[".$va["id"]."D],") !== false : null, ['class' => 'form-control', "id"=> $va["id"]."D"])}}
                                            <label for="{{ $va["id"] }}D">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 刪除 </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(isset($va["sub"]))
                            @foreach($va["sub"] as $sub_id => $sub_va)
                            <div class="form-group form-md-checkboxes">
                                <label class="col-md-3 control-label"
                                    for="form_control_1">{{ $sub_va["name"] }}：</label>
                                <div class="col-md-9">
                                    <div class="md-checkbox-inline">
                                        <div class="md-checkbox">
                                            <input type="checkbox" id="Z-{{ $sub_va["id"] }}" class="md-check">
                                            <label for="Z-{{ $sub_va["id"] }}">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 全選 </label>
                                        </div>
                                        <div class="md-checkbox">
                                            {{Form::checkbox('permission[]', "[".$sub_va["id"]."A]", isset($data) ? strrpos($data["permission"], "[".$sub_va["id"]."A],") !== false : null, ['class' => 'form-control', "id"=> $sub_va["id"]."A"])}}
                                            <label for="{{ $sub_va["id"] }}A">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 查看 </label>
                                        </div>
                                        <div class="md-checkbox">
                                            {{Form::checkbox('permission[]', "[".$sub_va["id"]."B]", isset($data) ? strrpos($data["permission"], "[".$sub_va["id"]."B],") !== false : null, ['class' => 'form-control', "id"=> $sub_va["id"]."B"])}}
                                            <label for="{{ $sub_va["id"] }}B">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 新增 </label>
                                        </div>
                                        <div class="md-checkbox">
                                            {{Form::checkbox('permission[]', "[".$sub_va["id"]."C]", isset($data) ? strrpos($data["permission"], "[".$sub_va["id"]."C],") !== false : null, ['class' => 'form-control', "id"=> $sub_va["id"]."C"])}}
                                            <label for="{{ $sub_va["id"] }}C">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 修改 </label>
                                        </div>
                                        <div class="md-checkbox">
                                            {{Form::checkbox('permission[]', "[".$sub_va["id"]."D]", isset($data) ? strrpos($data["permission"], "[".$sub_va["id"]."D],") !== false : null, ['class' => 'form-control', "id"=> $sub_va["id"]."D"])}}
                                            <label for="{{ $sub_va["id"] }}D">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 刪除 </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            @endforeach
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">送出</button>
                                    <button type="button" class="btn default"
                                        onclick="location.href = '/backend/admingroup';">取消</button>
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
                    name:"請輸入名稱",
                    'permission[]': {
                        required: '請選擇權限',
                        minlength: jQuery.validator.format("最少要選擇 {0} 個"),
                    }
                },
                rules: {
                    name: {
                        required: true
                    },
                    'permission[]': {
                        required: true,
                        minlength: 1,
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
        $("input[type='checkbox']").change(function () {
            //判斷是否是全選
            if (this.id.indexOf("Z") > -1) {
                if (this.checked) {
                    $(this).parent().parent().parent().find("input[name='permission[]']").prop("checked", true);
                } else {
                    $(this).parent().parent().parent().find("input[name='permission[]']").prop("checked", false);
                }
            } else {
                if ($(this).parent().parent().parent().find("input[name='permission[]']:checked").length == 4) {
                    $($(this).parent().parent().parent().find("input[type='checkbox']")[0]).prop("checked", true);
                } else {
                    $($(this).parent().parent().parent().find("input[type='checkbox']")[0]).prop("checked", false);
                }
            }
        });
    });
</script>
@endsection
