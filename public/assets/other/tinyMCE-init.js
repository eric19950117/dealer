tinymce.init({
    selector:'textarea',
    language: 'zh_TW',
    height: 500,
    plugins: 'link image code textcolor colorpicker lists table lineheight',
    // toolbar: ["forecolor backcolor underline bold italic  |  alignleft aligncenter alignright |  numlist bullist | insertfile link image | code ","fontsizeselect fontselect  |  lineheightselect table"],
    toolbar: ["fontselect fontsizeselect | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | numlist bullist | insertfile link image | code | lineheightselect table"],
    fontsize_formats: "9px 10px 12px 14px 15px 16px 18px 20px 22px 24px 26px 28px 36px",
    lineheight_formats: "6px 8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px",
    font_formats:
    '微軟正黑體=Microsoft JhengHei;'+
    '新細明體=PMingLiU;'+
    '細明體=MingLiU;'+
    "Andale Mono=andale mono,times;"+
    "Arial=arial,helvetica,sans-serif;"+
    "Arial Black=arial black,avant garde;"+
    "Book Antiqua=book antiqua,palatino;"+
    "Comic Sans MS=comic sans ms,sans-serif;"+
    "Courier New=courier new,courier;"+
    "Georgia=georgia,palatino;"+
    "Helvetica=helvetica;"+
    "Impact=impact,chicago;"+
    "Symbol=symbol;"+
    "Tahoma=tahoma,arial,helvetica,sans-serif;"+
    "Terminal=terminal,monaco;"+
    "Times New Roman=times new roman,times;"+
    "Trebuchet MS=trebuchet ms,geneva;"+
    "Verdana=verdana,geneva;"+
    "Webdings=webdings;"+
    "Wingdings=wingdings,zapf dingbats",
    // image_advtab: true,
    image_dimensions: false,
    valid_elements : '+*',
    fix_list_elements: false,
    valid_children: '+a[h5|p]',
    invalid_elements: '',
    cleanup_on_startup: false,
    trim_span_elements: false,
    verify_html: false,
    cleanup: false,
    // image_class_list: [
    //     {title: '自適應', value: 'img-responsive'}
    // ],
    relative_urls: false,
    remove_script_host : true,
    // content_css: [
    //     '/css/bootstrap.min.css',
    //     '/css/font-awesome.min.css',
    //     '/css/main.css',
    //     '/css/colors/default.css'
    // ],
    // file_browser_callback : function(field_name, url, type, win) {
    //     var w = window,
    //         d = document,
    //         e = d.documentElement,
    //         g = d.getElementsByTagName('body')[0],
    //         x = w.innerWidth || e.clientWidth || g.clientWidth,
    //         y = w.innerHeight|| e.clientHeight|| g.clientHeight;

    //     var cmsURL = '/filemanager/index.html?&field_name='+field_name+'&langCode='+tinymce.settings.language;

    //     if(type == 'image') {
    //         cmsURL = cmsURL + "&type=images";
    //     }

    //     tinyMCE.activeEditor.windowManager.open({
    //         file : cmsURL,
    //         title : 'Filemanager',
    //         width : x * 0.8,
    //         height : y * 0.8,
    //         resizable : "yes",
    //         close_previous : "no"
    //     });

    // }
});