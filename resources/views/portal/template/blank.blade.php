<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />  

<head>
    <!--meta tags -->
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport"> 
    <meta name="csrf-token" content="{{ csrf_token() }}" />
     
    <!-- favicon start -->
    
    <!-- end favicon -->
    <title>@if(isset($data['title']) && $data['title']!='') {{$data['title']}} @endif | {{ config('app.name') }}</title>
<!-- General CSS Files -->    
   
    <link rel="stylesheet" href="{{asset('assets/inlancer_portal/extra/css/iziToast.min.css')}}">
   
 <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('assets/inlancer_portal/icons/themify-icons/themify-icons.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/inlancer_portal/css/app.min.css')}}" type="text/css">

    <script src="{{asset('assets/inlancer_portal/extra/js/jquery.min.js')}}"></script>

</head>
<body class="auth"> 
    @include('messages.alert')
<!-- begin::preloader-->
    <div class="preloader">
        <div class="preloader-icon"></div>
    </div>
<!-- end::preloader -->
    <div class="form-wrapper">
            @yield('content')
    </div>
    <script language="javascript">APPLICATION_URL="{{asset('/')}}"</script>   
    <!-- General JS Scripts -->
    <script src="{{asset('assets/inlancer_portal/libs/bundle.js')}}"></script>
    <script src="{{asset('assets/inlancer_portal/js/app.min.js')}}"></script>
    <script src="{{asset('assets/inlancer_portal/extra/js/iziToast.min.js')}}"></script>
    <script src="{{asset('assets/inlancer_portal/extra/js/tost_msg.js')}}"></script>


    <!-- <script src="{{asset('restaurant_inlancer/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('restaurant_inlancer/js/slick.min.js')}}"></script>
    <script src="{{asset('restaurant_inlancer/js/main.js')}}"></script> -->
</body>

</html>