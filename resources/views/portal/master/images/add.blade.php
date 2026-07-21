@extends('portal.template.app') 
@section('content')

<?php 
$page_title = 'Add Images';
$route_name = 'image'; 
$mode = 'add';
$save_url = url($route_name.'-save');
$model_size = 'modal-lg';
?> 
<style type="text/css"> 
    textarea{
        height: unset;
    } 
</style>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?php echo $page_title; ?></h1>
           
            <div class="col-md-6 col-12"> 
              @include('portal.template.breadcrumb')
            </div> 
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12"> 
                    <div class="card">
                      <form class="form add_form " method="post" id="add_form" action="javascript:void(0);" enctype="multipart/form-data" >
                            <input type="hidden" name="mode" value="{{$mode}}">
                            @csrf
                            <div id="message"></div> 
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12 pb-5">
                                        <div class="card-body">
                                            <div class="accordion" id="accordionFlushAdd">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#add_teacher_detail" aria-expanded="false" aria-controls="add_teacher_detail">
                                                        Step-1 : Images Basic Info
                                                        </button>
                                                    </h2>
                                                    <div id="add_teacher_detail" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushAdd">
                                                        <div class="accordion-body">
                                                            <div class="row pb-2">
                                                                <div class="col">
                                                                    <label class="form-label" for="withdrawinput2">Image (Multiple)</label>
                                                                    <!-- main_image -->
                                                                    <input class="d-none" type="file"  multiple="multiple" accept="image/jpeg,png,webp" class="">
                                                                    <div class="drop-area"> 
                                                                        <div class="text-center row">
                                                                          <i class="bi bi-cloud-arrow-up mb-2 upload-icon"></i>
                                                                          <h3 class="img-upload-title">Click "Here" or drop your Images here</h3>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" id="other_images" name="main_image" data-image>
                                                                    <!-- main_image -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col-12 text-start">
                                                    <button type="submit" class="btn btn-sm btn-success waves-effect waves-light float-right"><i class="fa fa-spinner fa-spin d-none" tabindex="20"></i> Save</button>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div> 
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
    $("#add_form").submit(function (event) {
      var image_id = $('input[name="main_image"]').val();
      if(image_id != ''){
        successToast("Data Saved Successfully");
        setTimeout(function(){
            window.location.href= '{{url($route_name."-master")}}';
        },1000);
      }else{
        errorToast("Image upload required");
      }
    });
});

</script>

@endsection