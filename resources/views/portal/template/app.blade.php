<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta http-equiv="content-type" content="text/html;charset=utf-8" /> 

<head>
<!--meta tags -->
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="author" content="Inlancer.in"> 
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <!-- For Site Install -->
    <meta name="theme-color" content="#990d16"/>
    <link rel="apple-touch-icon" href="{{asset('assets/inlancer_portal/img/logo.png')}}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/inlancer_portal/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/inlancer_portal/img/logo.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/inlancer_portal/img/logo.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">

    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/inlancer_portal/img/logo.png') }}">

    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/inlancer_portal/img/logo.png')}}">

    <title>@if(isset($title) && $title!='') {{ucfirst($title)}} @endif  Portal | {{ config('app.name') }}</title>


    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        href="{{asset('assets/inlancer_portal/icons/bootstrap-icons-1.4.0/bootstrap-icons.min.css')}}" type="text/css">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        href="{{asset('assets/inlancer_portal/css/bootstrap-docs.css')}}" type="text/css">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        href="{{asset('assets/inlancer_portal/libs/slick/slick.css')}}" type="text/css">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        href="{{asset('assets/inlancer_portal/css/app.min.css')}}" type="text/css">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        href="{{asset('assets/inlancer_portal/css/shadcn-theme.css')}}?v=2" type="text/css">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        type="text/css" href="{{asset('assets/inlancer_portal/modules/datatables/datatables.min.css')}}">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        type="text/css" href="{{asset('assets/inlancer_portal/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        type="text/css" href="{{asset('assets/inlancer_portal/libs/select2/css/select2.min.css')}}">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        type="text/css" href="{{asset('assets/inlancer_portal/indrop/inlancer_drop.css')}}">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        type="text/css" href="{{asset('assets/inlancer_portal/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css')}}">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        type="text/css" href="{{asset('assets/inlancer_portal/vendors/dataTable/datatables.min.css')}}">    
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        href="{{asset('assets/inlancer_portal/extra/css/iziToast.min.css')}}">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        href="{{asset('assets/inlancer_portal/vendors/summernote/summernote-lite.min.css')}}">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen" 
        type="text/css" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css"> 
    
    <script src="{{asset('assets/inlancer_portal/extra/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        var loaded_script = [];
            
        function load_scripts( urls, final_callback, index=0 )
        {
            if( typeof urls[index+1] === "undefined" )
            {
                load_script( urls[index], final_callback );
            }
            else
            {
                load_script( urls[index], function() {
                    load_scripts( urls, final_callback, index+1 );
                } );
            }
        }
    
        // LOAD SCRIPT
        function load_script( url, callback )
        {
            var script = document.createElement( "script" );
            script.type = "text/javascript";
            if(script.readyState) // IE
            {
                script.onreadystatechange = function()
                {
                    if ( script.readyState === "loaded" || script.readyState === "complete" )
                    {
                        script.onreadystatechange = null;
                        callback(url);
                    }
                };
            }else{  
                script.onload = function() { callback(url); };
            }
            script.src = url;
            document.getElementsByTagName( "head" )[0].appendChild( script );
            loaded_script.push(url);
            // console.log(loaded_script);
        }
    
        // after all script loaded
        function script_load(){
            console.log(loaded_script);
        }
    </script>    
    <script language="javascript">csrf_token="{{ csrf_token() }}"</script>    
    <script language="javascript">APPLICATION_URL="{{url('/')}}"</script>    
</head>
<body style="opacity:0;" >
    <script> setTimeout(() => { document.body.style.opacity = '1'; }, 500); </script>
    <style type="text/css">
        .modal.left .modal-dialog,
        .modal.right .modal-dialog {
            position: fixed;
            margin: auto;
            width: 100%;
            height: 100%;
            -webkit-transform: translate3d(0%, 0, 0);
            -ms-transform: translate3d(0%, 0, 0);
            -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
        }

        .modal.left .modal-content,
        .modal.right .modal-content {
            height: 100%;
        }
        
        .modal.left .modal-body,
        .modal.right .modal-body {
            padding: 15px 15px 80px;
        }
        .modal.left.fade .modal-dialog{
            left: -320px;
            -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
            -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
                -o-transition: opacity 0.3s linear, left 0.3s ease-out;
                    transition: opacity 0.3s linear, left 0.3s ease-out;
        }
        .modal.left.fade.in .modal-dialog{
            left: 0;
        }
        .modal.right.fade .modal-dialog {
            right: 0;
            -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
            -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
                -o-transition: opacity 0.3s linear, right 0.3s ease-out;
                    transition: opacity 0.3s linear, right 0.3s ease-out;
        }
        .modal.right.fade.in .modal-dialog {
            right: 0;
        }
        .modal-content {
            border-radius: 0;
            border: none;
        }
        .modal-xl{
            max-width: 90%;
        }
        .modal-header {
            border-bottom-color: #EEEEEE;
            background-color: #FAFAFA;
        }
        label{
            text-transform: capitalize;
        }
        a.maja_drop {
            padding: 10px 20px;
            font-weight: 500;
            line-height: 1.2;
            color: white;
        }
        .img_remove{
            position: absolute;/*inheri*/
            /*height: 26px; 
            margin-right: -25px;*/
            z-index: 10;
        }
        .dt-buttons.btn-group.flex-wrap:before{
        content: "Export data ";
        width: 10%;
        margin: auto 10px;
        }
        .dt-buttons.btn-group.flex-wrap{
            width: 90%;
            display: block;
        }
        .dataTable td{
            /*white-space: break-spaces;*/
            word-break: break-all;
        }
        .menu-header-logo{
            height: 15px;
            width: 60px;
            visibility: collapse;
        }
        .save-btns{
            justify-content: space-between;
            display: flex;
            padding: 15px;
        }
        .save-btns .btn{
            min-width:90px;
        }
        .badge{
            padding: 0.6em 0.85em;
        }
        :focus,
        .modal .close:focus,
        .btn-check:focus+.btn, .btn:focus {
            outline: none;
            box-shadow:unset;
        }
        .dataTable .dropdown-item .bi {
            display: none;
        }
        .dataTable [data-bs-toggle="dropdown"] {
            /*border: 1px solid var(--bs-orange);
            line-height: 10px;*/
            border: 1px solid #ccc;
            padding: 3px 9px 2px 9px;
            color: var(--bs-orange);
        }
        .modal .close{
            border: 1px solid var(--bs-orange);
            line-height: 10px;
            color: var(--bs-orange);
        }
        .modal .close {
            border: 1px solid var(--bs-orange);
            color: var(--bs-orange);
            background: transparent;
            border-radius: 4px;
            font-size: 23px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            padding: 0;
            margin: 0;
        }
        .table.table-custom tbody tr:last-child td{
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        .preloader{
            flex-direction: column;
        }
        .note-editor .dropdown-toggle:after{
            border: 0;
        }
        .note-btn-group.note-view,
        .note-btn-group.note-insert {
            display: none;
        }
        .note-dropdown-menu{
            height: 300px;
            overflow-y: scroll;
        }
        .note-color.open .note-dropdown-menu{
            display: flex;
        }
        .menu-body ul li>a>span{
            text-transform: capitalize;
        }
        .note-btn-group.note-color.note-color-all.open .note-dropdown-menu,
        .note-para .note-btn-group.open .note-dropdown-menu{
            display: flex;
            overflow: hidden;
        }
        .note-para .note-btn-group.open .note-dropdown-menu .note-align{
            margin: 0;
        }
        .note-btn-group.open .note-dropdown-menu{
            height: auto;
            overflow: hidden;
        }
        [data-href]{cursor: pointer;}
        [data-href],
        .grow{
            /* transform: scale(1); */
            transition: .25s all ease; 
        }
        [data-href]:hover{
            transform: scale(1.01) translateX(-1px);
        }
        .grow:hover{
            transform: scale(1.025);
        }
        tr.border-warning{
            background: #faae423d!important;
        }
        tr.border-warning > td{
            border-color: var(--bs-warning)!important;
        }
        tr.border-danger{
            background: #ffedec !important
        }
        tr.border-danger > td{
            border-color: #ff5722!important;
        }
        tr.border-black{
            background: #0000000a!important;
        }
        tr.border-black > td{
            border-color: var(--bs-black)!important;
        }
        tr.border-success{
            background: #05b17138!important;
        }
        tr.border-success > td{
            border-color: var(--bs-success)!important;
        }
        tr.border-info{
            background: #25c2e34d!important;
        }
        tr.border-info > td{
            border-color: var(--bs-info)!important;
        }
        .fw-bold{
            text-transform: uppercase;
        }
        .bd-content>ul li, .bd-content>ol li{
            margin-bottom: .10rem;
        }
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
            font-weight: 700;
        }

        /*  Tom Select Css  */
        #select-customer-ts-dropdown{
            background-color: #fff;  
            border-color : #990d16;
        }
        .focus .ts-control  {
            border-color : #990d16;
            box-shadow: none;
        }
        .ts-dropdown [data-selectable].option {
            padding: 7px;    
        }
        .ts-dropdown .active {
            background-color: #f0f0f0; 
            color: #333; 
        }
        .ts-dropdown-content {
            background-color: #fff; 
        }
        .ts-wrapper:not(.form-control,.form-select).single .ts-control {
            border-radius: 5px;
            border: 1px solid #990d16;
        }
        [add_more]:focus {
            -webkit-box-shadow: 0 0 0 2px var(--bs-primary);
            box-shadow: 0 0 0 2px var(--bs-primary);
        }
        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child:before{
            top: 45%;
            line-height: 20px;
            font-size: 20px;
            border: 0px solid white;
            height: 20px;
            width: 20px;
        }
        .break-all{
            display: inline-block;
            width: 100%;
            word-break: break-word;
            text-wrap: pretty;
        }
        /* @media (max-width: 768px) {
            .break-all {
                display: inline-block;
                width: 100%;
                word-wrap: break-word;
                text-wrap: pretty;
            }
        } */
        .ts-wrapper:not(.form-control,.form-select).single .ts-control,
        .ts-wrapper.form-control:not(.disabled) .ts-control, .ts-wrapper.form-control:not(.disabled).single.input-active .ts-control, .ts-wrapper.form-select:not(.disabled) .ts-control, .ts-wrapper.form-select:not(.disabled).single.input-active .ts-control{
            padding: .675rem 1.25rem;
            min-height: 42px;
        }
        .ts-dropdown, .ts-dropdown.form-control, .ts-dropdown.form-select{
            background-color: #fff;
        }
        .plugin-dropdown_input.focus .ts-dropdown .dropdown-input{
            box-shadow: unset;
            border: 1px solid;
        }
        .ts-wrapper:not(.form-control,.form-select).single .ts-control{
            background-image: none;
        }
        .not-allowed{
            background-color: #f0f0f0 !important;
            opacity: 0.8;
            cursor: not-allowed;
            pointer-events: none;
        }
        @media (max-width:540px) {
            .menu {
                width: 70%!important;
                right: 0
            }
            .menu-body ul li>a{
                padding: 5px 5px;
            }
            .menu-body ul li.menu-divider{
                padding: 10px 30px;   
                margin-top: 0px;
            }
            .menu-header, .menu-header-logo{
                padding:0;
            }
            .menu-body{
                padding:0 20px;
            }
            .menu-body .dropdown{
                margin-bottom: 0;
            }
            .menu-body ul li>a+ul li a{
                padding-left: 55px;
            }
        }
    </style>






    @include('messages.alert')
    <?php

    $session_role_permission_id =  session()->get('role_permission_id');  
    if(!empty($session_role_permission_id)) {
        $permissions =  $session_role_permission_id;  
    }else{
        $permissions =  [];  
    } 
    if(!empty(session()->get('role_module_id'))){
        $module_id = session()->get('role_module_id');
    }else{
        $module_id = [];
    }
    $role = session()->get('admin')['user_role']; 
    ?>
    
    <script>
        base_url = "{{asset('/')}}";
    </script>

<!-- <div class="preloader" onclick="this.style.display='none';">
    <img  src="{{asset('assets/inlancer_portal/img/logo.png')}}" alt="logo">
    <div class="preloader-icon"></div>
</div>
 -->


    
<div class="sidebar" id="notifications">
    <div class="sidebar-header d-block align-items-end">
        <div class="align-items-center d-flex justify-content-between py-4">
            Notifications
            <button data-sidebar-close>
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active nav-link-notify" data-bs-toggle="tab" href="#activities">Activities</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#notes">Notes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#alerts">Alerts</a>
            </li>
        </ul>
    </div>
    <div class="sidebar-content">

    </div>
</div>
    





<div class="menu">
    <div class="menu-header">
        <a href="{{url('portal')}}" class="menu-header-logo">
            <img src="{{asset('assets/inlancer_portal/img/logo.png')}}" alt="logo">
        </a>
        <a href="{{url('portal')}}" class="btn btn-sm menu-close-btn">
            <i class="bi bi-x"></i>
        </a>
    </div>
    <div class="menu-body">

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown">
                <div class="avatar me-3">
                    <img src="{{session()->get('admin')['user_profile_image']}}"
                         class="rounded-circle" alt="image">
                </div>
                <div>
                    <div class="fw-bold"> Hi, {{session()->get('admin')['user_name']}}</div>
                        <small class="text-muted">{{ucfirst($role)}} Account</small>
                </div>
                <div style=" margin-right: 10px; margin-left: auto; ">
                    <i class="bi bi-three-dots-vertical"></i>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{url('my-profile')}}" class="dropdown-item d-flex align-items-center @if(isset($active) && $active=='profile') {{'active'}} @endif">
                    Profile
                </a>
                <a href="{{ url('portal-logout') }}" class="dropdown-item d-flex align-items-center text-danger">
                    Logout
                </a>
            </div>
        </div>

        <ul>            
            @include('portal.template.menu')
        </ul>
    </div>
</div>



<div class="layout-wrapper">

    <div class="header">
        <div class="menu-toggle-btn"> 
            <a href="#">
                <i class="bi bi-list"></i>
            </a>
        </div>
        
        <a href="{{url('portal')}}" class="logo">
            <img width="100" src="{{asset('assets/inlancer_portal/img/logo.png')}}" alt="logo">
        </a>
        
        <div class="page-title">{{\Str::title($title) ?? ''}}</div>
        <!-- <form class="search-form">
            <div class="input-group">
            </div>
        </form>
        <div class="header-bar ms-auto">
            <div class="justify-content-end">
            </div>
        </div> 
         -->
        <!-- <div class="header-mobile-buttons">
            <a href="#" class="search-bar-btn">
                <i class="bi bi-search"></i>
            </a>
            <a href="#" class="actions-btn">
                <i class="bi bi-three-dots"></i>
            </a>
        </div> -->
        
    </div>

    
    <div class="content p-2">
        @yield('content')  
    </div>
    
</div> 

<script defer src="{{asset('assets/inlancer_portal/libs/bundle.js')}}"></script>
<script defer src="{{asset('assets/inlancer_portal/libs/charts/apex/apexcharts.min.js')}}"></script>
<script defer src="{{asset('assets/inlancer_portal/libs/slick/slick.min.js')}}"></script>
<script defer src="{{asset('assets/inlancer_portal/js/app.min.js')}}?ver=1"></script>      
<script defer src="{{asset('assets/inlancer_portal/extra/js/iziToast.min.js')}}"></script>
<script defer src="{{asset('assets/inlancer_portal/extra/js/tost_msg.js')}}"></script>
<script defer src="{{asset('assets/inlancer_portal/extra/js/jquery.form.js')}}"></script>
<script defer src="{{ asset('assets/inlancer_portal/vendors/dataTable/datatables.min.js')}}"></script>
<script defer src="{{asset('assets/inlancer_portal/vendors/summernote/summernote-lite.min.js')}}"></script>
<script defer src="{{ asset('assets/inlancer_portal/libs/select2/js/select2.full.min.js')}}"></script>
<script defer src="{{ asset('assets/inlancer_portal/indrop/inlancer_drop.js')}}"></script>


<script type="text/javascript">
    window.scroll_enable = true;
    $(document).ready(function() {
        // $('.select2').select2();
    });
    $(document).on('show.bs.modal','[add-modal]', function () {
        // $(this).find('form').trigger('reset');
        // $(this).find('select').val('').trigger('change');
    });

    $(document).on('show.bs.modal','.modal', function () {
        setTimeout(function() {
            $('.previewImage').each(function(index, el) {
                console.log(el);
                if($(el).attr("src").includes('.pdf') ){
                    $(el).attr("src", "{{asset('assets/pdf_icon.png')}}");
                }   
            });
        }, 1000);
        // load_editor();
    });

    function html_customer_list(append_here,passed_data,call_fn=''){
        var selected_data = {};
        if(typeof $(append_here).data('selected') !== 'undefined'){
            selected_data = { 'customer_id' : $(append_here).data('selected') };
        }
        html_ajax('POST','{{url("html_customer_list")}}',append_here,passed_data,selected_data,call_fn);
    }

    function html_process_list(append_here,passed_data,call_fn=''){
        var selected_data = {};
        if(typeof $(append_here).data('selected') !== 'undefined'){
            selected_data = { 'process_id' : $(append_here).data('selected') };
        }
        html_ajax('POST','{{url("html_process_list")}}',append_here,passed_data,selected_data,call_fn);
    }

    function html_product_list(append_here,passed_data,call_fn=''){
        var selected_data = {};
        if(typeof $(append_here).data('selected') !== 'undefined'){
            selected_data = { 'item_id' : $(append_here).data('selected') };
        }
        html_ajax('POST','{{url("html_product_list")}}',append_here,passed_data,selected_data,call_fn);
    }

    function html_user_list(append_here,passed_data,call_fn=''){
        var selected_data = {};
        if(typeof $(append_here).data('selected') !== 'undefined'){
            selected_data = { 'user_id' : $(append_here).data('selected') };
        }
        html_ajax('POST','{{url("html_user_list")}}',append_here,passed_data,selected_data,call_fn);
    }

    function html_ajax(method='POST',url,append_here,passed_data,selected_data={},call_fn='') {
        if(passed_data == undefined ){
            var fianl_passed_data = selected_data;
        }else{
            var fianl_passed_data = {...passed_data, ...selected_data};
        }
        $(append_here).parent().css({
            'opacity': '0.5',
            'pointer-events': 'none'
        });
        jQuery.ajax({
            url: url,
            method: method,
            data: fianl_passed_data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            success: function(result){
                $(append_here).empty();
                $(append_here).append(result).trigger('change');
                $(append_here).parent().css({
                    'opacity': '1',
                    'pointer-events': 'all'
                });
                if(call_fn !== '' && typeof window[call_fn] === 'function'){
                    window[call_fn]();
                }
            }
        });
    }
jQuery(document).ready(function($) {
    setTimeout(function() {
        $('.previewImage').each(function(index, el) {
            console.log(el);
            if($(el).attr("src").includes('.pdf') ){
                $(el).attr("src", "{{asset('assets/pdf_icon.png')}}");
            }
        });
    }, 1000);
    $('.tom-select').each(function() {
        simple_tom_select(this);
    });
});

function simple_tom_select(el){
    new TomSelect(el, {
        plugins: ['dropdown_input'],
        persist: false,
        highlight: false,
        sortField: {
                field: 'text',
                direction: 'asc'
            }
    });
}

function load_editor() {
    $('.summernote').summernote({
        // fontNames: ['Arial', 'Arial Black', 'Calibri', 'Times New Roman', 'Comic Sans MS', 'Courier New'],
        fontNamesIgnoreCheck: ['Calibri', 'Times New Roman'],
        fontSizes: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60', '61', '62', '63', '64', '65', '66', '67', '68', '69', '70', '71', '72', '73', '74', '75', '76', '77', '78', '79', '80', '81', '82', '83', '84', '85', '86', '87', '88', '89', '90', '91', '92', '93', '94', '95', '96', '97', '98', '99', '100', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '111', '112', '113', '114', '115', '116', '117', '118', '119', '120', '121', '122', '123', '124', '125', '126', '127', '128', '129', '130', '131', '132', '133', '134', '135', '136', '137', '138', '139', '140', '141', '142', '143', '144', '145', '146', '147', '148', '149', '150', '151', '152', '153', '154', '155', '156', '157', '158', '159', '160', '161', '162', '163', '164', '165', '166', '167', '168', '169', '170', '171', '172', '173', '174', '175', '176', '177', '178', '179', '180', '181', '182', '183', '184', '185', '186', '187', '188', '189', '190', '191', '192', '193', '194', '195', '196', '197', '198', '199', '200'],
        // height: ['1.0','1.1','1.2','1.3','1.4','1.5','1.6','1.7','1.8','1.9','2.0','2.1','2.2','2.3','2.4','2.5','2.6','2.7','2.8','2.9','3.0'],
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['font', ['fontname', 'fontsize', 'color', 'superscript', 'subscript']],
            ['table', ['table']],
            ['para', ['style', 'ol', 'ul', 'paragraph','height']],
            ['misc', ['undo', 'redo']],
        ],
        dialogsInBody: true,
        height: 500,
    });
}
$(document).on('click', '[data-href]', function(event) {
    event.preventDefault();
    let href = $(this).data('href');
    if(href && href.trim() == ''){
        return false;
    }
    let target = $(this).data('target');
    if(!target){
        target = '_self';
    }
    window.open(href,target);
});

var currentTime = new Date().getTime();

function updateTime() {
    var moveTime = new Date().getTime();
    if ((moveTime - currentTime) > (1 * 60 * 60 * 1000)) {
        location.reload();
    }
}

window.onload = function() {
    document.addEventListener("mousemove", updateTime);
    document.addEventListener("touchstart", updateTime);
};
</script>

@include('portal.template.imagePress')  
<!-- @-include('portal.template.firebase-notification')   -->


<!-- For Site Install -->
<script src="{{ asset('/sw.js') }}"></script>
<script>
   if ("serviceWorker" in navigator) { 
      navigator.serviceWorker.register("/sw.js").then(
      (registration) => {
         console.log("Service worker registration succeeded:", registration);
      },
      (error) => {
         console.error(`Service worker registration failed: ${error}`);
      },
    );
  } else {
     console.error("Service workers are not supported.");
  }
</script>

<!-- For Cache -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('{{ asset("service-worker.js") }}').then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }).catch(function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }

    if (window.matchMedia('(display-mode: standalone)').matches) {
        console.log('Running in standalone mode');
    } else {
        console.log('Running in browser mode');
    }
</script>


</body>
</html>