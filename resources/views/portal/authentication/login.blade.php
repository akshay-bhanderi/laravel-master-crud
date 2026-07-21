@extends('portal.template.blank') 
@section('content')
<div class="container">
    <div class="card">
        <div class="row g-0">
            <div class="col d-none d-lg-flex border-start align-items-center justify-content-center text-center ">
                <div class="logo">
                   <?php
                        try {
                            $logo = \Cache::remember('setting_logo', 3600000, function () {
                                $logo_setting = \InsertSettings::get_settings_value('logo');
                                if (!empty($logo_setting)) {
                                    return \App\Http\Controllers\Controller::get_image_from_id($logo_setting);
                                }
                                return '';
                            });
                        } catch (\Exception $e) {
                            $logo = '';
                        }
                    ?>
                    <img width="200" onerror="this.remove();" src="{{$logo}}" alt="logo">
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-md-10 offset-md-1">

                        <div class="my-5 text-center text-lg-start">
                            <h1 class="display-8">Sign In</h1>
                            <p class="text-muted">into admin panel</p>
                        </div>


                        @php
                         $default_data=[];
                        if(Session::has('data')){
                            $default_data = Session::get('data');
                        } 
                        @endphp   
                        <form method="post" class="mb-5 my-4" action="{{url('portal-do-login')}}" id="portal-login-form">
                            @csrf
                            <div class="mb-3">
                                <input type="tel" name="email" class="form-control" placeholder="Enter email or phone number" autofocus required value="{{$default_data['user_phone_no'] ?? '' }}" inputmode="numeric">
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>
                            <div class="text-center text-lg-start">
                                <!-- <p class="small">Can't access your account? <a href="#">Reset your password now</a>.</p> -->
                                <button type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection