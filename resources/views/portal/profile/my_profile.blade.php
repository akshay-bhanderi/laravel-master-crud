@extends('portal.template.app')
 

 

@section('content')
  
    <div class="main-content card">
        <section class="section card-body">
            <div class="section-body">
                <div class="row">
                    <div class="col-md-1 col-12">
                        <img style="height: 70px;width: 70px;border-radius: 50%" src="{{$profile_data['profile_image_url']}}"
                        onerror="this.onerror=null; this.src='{{asset('assets/inlancer_portal/img/placeholder.jpg')}}'"
                        >
                    </div>
                    <div class="col-md-8 col-12">
                        <h2 style="font-size: 18px;color: #191d21;font-weight: 600;position: relative;margin: 10px 0 5px 0;">Hi, {{ $profile_data['user_name']}} !</h2>
                        <p class=" ">
                             Change information about your account on this page
                        </p>        
                    </div>
                    <div class="col-md-3 col-12">
                        @php
                            $role='';
                            if(isset($profile_data['role_title']) && $profile_data['role_title']!=''){
                                $role = $profile_data['role_title'];
                            }else{ 
                                if(session()->get('admin')['user_role']=='admin'){
                                    $role = 'Super Admin';
                                }
                                if(session()->get('admin')['user_role']=='staff'){
                                    $role = 'Staff Member';
                                }
                            }
                        @endphp
                            <h6 style="border: 1px solid #ccc; padding: 10px; text-align: center;">You are {{ucfirst($role)}}!</h6>
                    </div>
                </div>
                
                <div class="row mt-sm-4"> 
                    <div class="col-12 col-md-12 col-lg-7">
                        <div class="card"> 
                            <form id="user_profile" method="post" action="{{url('portal-update-profile')}}" class="needs-validation" novalidate="">
                                @csrf
                                <div class="card-header">
                                    <h4>Profile Info</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="form-group col-md-6  col-12">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control" name="user_name" value="{{$profile_data['user_name']}}" >
                                            <div class="invalid-feedback">
                                                Please fill in the first name
                                            </div>
                                        </div> 
                                        <div class="form-group col-md-6  col-12">
                                            <label> Email</label>
                                            <input type="email" class="form-control" name="user_email" value="{{$profile_data['user_email']}}" disabled>
                                            <div class="invalid-feedback">
                                                Please fill in the email
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2"> 
                                        <div class="form-group col-md-6  col-12">
                                            <label> Profile Picture </label>
                                            <input type="file" name="user_profile_image" class="form-control" value="">
                                        </div>
                                        <div class="form-group col-md-6  col-12">
                                            <label> Phone No </label>
                                            <input type="tel" name="user_phone_no" class="form-control" value="{{$profile_data['user_phone_no']}}">
                                        </div>
                                    </div>  
                                </div> 
                                <br>
                                <div class="card-footer text-right">
                                    <button style="font-size: 15px !important;" type="submit" class="btn btn-success waves-effect waves-light"><i class="fa-pulse fa fa-spinner d-none"></i>Update Profile </button>&nbsp;  
                                </div>
                            </form> 
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-5">
                        <div class="card">
                            <form id="password" method="post"  action="{{url('portal-update-password')}}" class="needs-validation" novalidate="">
                                <div class="card-header">
                                    <h4> Change Password </h4>
                                </div>
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-12 mb-1 col-12">
                                            <label> Current  Password </label>
                                            <input type="Password" class="form-control" name="current_password"   required="">
                                            <div class="invalid-feedback">
                                                Please fill in your Current password
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 mb-1 col-12">
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12 mb-1 col-12">
                                            <label> New Password </label>
                                            <input type="Password" class="form-control" name="new_password"   required="">
                                            <div class="invalid-feedback">
                                                Please fill in your new password
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 mb-1 col-12">
                                            <label> Confirm Password</label>
                                            <input type="Password" class="form-control" name="confirm_password"  required="">
                                            <div class="invalid-feedback">
                                                Please fill in your Confirm password
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="card-footer text-right"> 
                                    <button style="font-size: 15px !important;" type="submit" class="btn btn-danger waves-effect waves-light"><i class="fa-pulse fa fa-spinner d-none"></i>  Change Password </button>&nbsp;  
                                </div>
                            </form>
                        </div>
                    </div> 
                </div>
            </div>
        </section>
    </div>

<script type="text/javascript">
    jQuery(document).ready(function() {  
    /*var dd = {
        beforeSend: function() { 
            $('.fa-spinner').removeClass('d-none');
        },
        uploadProgress: function(event, position, total, percentComplete) { 
        },
        success: function() {},
        complete: function(response) {
            // console.log(response.responseText);
            var result = jQuery.parseJSON(response.responseText);
            $('.fa-spinner').removeClass('d-none');
            $('.fa-spinner').addClass('d-none');
            if (result.status == 200) {
                successToast(result.message);
                setTimeout(function(){ location.reload(); }, 1500);
            } else {
                errorToast(result.message);
            }
        },
        error: function() { 
        }
    }; 
    jQuery("#user_profile").ajaxForm(dd);*/ 


    var pp = {
        beforeSend: function() { 
            $('.fa-spinner').removeClass('d-none');
        },
        uploadProgress: function(event, position, total, percentComplete) { 
        },
        success: function() {},
        complete: function(response) {
            // console.log(response.responseText);
            var result = jQuery.parseJSON(response.responseText);
            $('.fa-spinner').removeClass('d-none');
            $('.fa-spinner').addClass('d-none');
            if (result.status == 200) {
                successToast(result.message);

                /*Swal.fire({
                    type: 'success',
                    title: result.message,
                    showConfirmButton: false,
                    timer: 1500
                });*/  
            } else {
                /*Swal.fire({
                    type: 'warning',
                    title: 'Oops',
                    text: result.message,
                    showConfirmButton: false,
                    timer: 2000
                });*/
                errorToast(result.message);
            }
        },
        error: function() { 
        }
    }; 
    jQuery("#password").ajaxForm(pp); 
 
});
</script>

@endsection