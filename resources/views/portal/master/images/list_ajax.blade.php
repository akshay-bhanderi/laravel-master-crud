<?php 
foreach($image_list as $key=>$value){ 
    $path_info = pathinfo($value->image_thumb_url); 
    ?>
    @if($path_info['extension'] == 'pdf')
        <div 
            class="p-1" 
            pdf-section-list
            onclick="get_and_set_image_detail(this)" 
            data-page_no="{{$page_no}}" 
            data-image_id="{{$value->image_id}}" 
            data-image_name="{{$value->image_name}}" 
            data-image_file_name="{{$value->image_file_name}}" 
            data-image_thumb_url="{{asset($value->image_thumb_url)}}" 
            data-image_original_url="{{asset('assets/pdf_icon.png')}}" 
            data-image_alt_tag="{{$value->image_alt_tag}}" 
            data-image_details="{{$value->image_details}}" 
            data-image_status="{{$value->image_status}}" 
            data-image="{{asset('assets/pdf_icon.png')}}" 
            data-title="{{$value->image_file_name}}" 
            href="{{$value->image_original_url}}" 
            title="{{$value->image_alt_tag}}" >
            <img src="{{asset('assets/pdf_icon.png')}}" title="{{$value->image_alt_tag}}" class="imagecheck-image img-thumbnail">
        </div> 
    @else
    <?php
        if(env('APP_ENV') == 'production'){
            $proxy_url = 'https://imageproxy.inlancer.in/pr:sharp/f:webp/rs:fit:300:0/g:sm/plain/';
        }else{
            $proxy_url = '';
        }
    ?>
        <div 
            class="p-1" 
            image-section-list
            onclick="get_and_set_image_detail(this)" 
            data-page_no="{{$page_no}}" 
            data-image_id="{{$value->image_id}}" 
            data-image_name="{{$value->image_name}}" 
            data-image_file_name="{{$value->image_file_name}}" 
            data-image_thumb_url="{{asset($value->image_thumb_url)}}" 
            data-image_original_url="{{asset($value->image_original_url)}}" 
            data-image_alt_tag="{{$value->image_alt_tag}}" 
            data-image_details="{{$value->image_details}}" 
            data-image_status="{{$value->image_status}}" 
            data-image="{{$value->image_original_url}}" 
            data-title="{{$value->image_file_name}}" 
            href="{{$value->image_original_url}}" 
            title="{{$value->image_alt_tag}}" >
             <img src="{{$proxy_url ?? ''}}{{asset($value->image_original_url)}}" alt="{{$value->image_alt_tag}}" class="imagecheck-image img-thumbnail">
        </div>
    @endif
<?php } ?> 

<?php if( count($image_list) < $limit){ ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#load_more_images').remove();
});
</script>
<?php } ?>
