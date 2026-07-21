<?php 
  if(empty($value) && !empty( $data[$name] )){
    $value = $data[$name];
  }
  if(!empty($value)){
    $arr = \App\Http\Controllers\Controller::get_image_from_id_multiple($value);
    $uploaded = implode(',', $arr);
  }
  if(empty($label)){
      $label = str_replace('_', ' ', $name).' : ';
      $label = str_replace('id', '', $label);
  }

  $userdata = session('admin');
  if(empty($userdata) || empty($userdata['user_role_id'])){
      return false;
  }

  if(!empty($upload) ){
    $image_upload_type = $upload;
  }else{
    if($userdata['user_role_id'] == 1){
      $image_upload_type = 'select';
    }else{
      $image_upload_type = 'direct';
    }
  }
?>
@if(empty($nolabel))
<label>
  {!! $label ?? '' !!}
  @if(empty($multiple))
    (Single)
  @else
    (Multiple)
  @endif
</label>
@endif


@if($image_upload_type == 'select')
<!-- type = file or image -->
<div class="image_selection" image_selection onclick="image_modal_open(this)" type="{{$type ?? 'image'}}" uploaded-image="{{$uploaded ?? ''}}">
    <div class="image_selection_child" image_selection_child {{$multiple ?? ''}}  >
       <i class="bi bi-cloud-arrow-up me-2 upload-icon"></i>
       <h3 class="img-upload-title"> {{ $lable ?? 'Click here to Select/upload Image'}}</h3>
    </div>
   <input type="hidden" name="{{$name ?? 'image_ids'}}" data-image value="{{$value ?? ''}}" accept="{{$accept ?? ''}}">
</div>
  @if(!empty($uploaded))
     <div>
     <?php
        $uploaded_arr = explode(',', $uploaded);
        $inc= 0;
        foreach( (array)$uploaded_arr as $file_val ){ ?>
           <div>
              {{++$inc}} ) Uploaded File :
              <a target="_blank" href="{{$file_val ?? '#'}}">View File</a>
           </div>
    <?php } ?>
        <hr>
    </div>
  @endif
@else

<input class="d-none" type="file" {{$multiple ?? ''}} accept="{{$accept ?? ''}}" {{$attr ?? ''}}>
<div id="{{ uniqid('drop_area_') }}" class="drop-area image_selection {{$comporess ?? ''}} {{$crop ?? ''}}"  uploaded-image="{{$uploaded ?? ''}}">
    <div class="text-center row">
      <i class="bi bi-cloud-arrow-up mb-2 upload-icon"></i>
       <h3 class="img-upload-title"> {{ $lable ?? 'Click here to Select/upload Image'}}</h3>
    </div>
</div> 
<input type="hidden" name="{{$name ?? 'image_ids'}}" data-image value="{{$value ?? ''}}" accept="{{$accept ?? ''}}">
  @if(!empty($uploaded))
     <div>
     <?php
        $uploaded_arr = explode(',', $uploaded);
        $inc= 0;
        foreach( (array)$uploaded_arr as $file_val ){ ?>
           <div>
              {{++$inc}} ) Uploaded File :
              <a target="_blank" href="{{$file_val ?? '#'}}">View File</a>
           </div>
      <?php } ?>
      <hr>
     </div>
  @endif
@endif