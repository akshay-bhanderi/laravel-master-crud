<style type="text/css">
    /*All page in Image click css*/
    .image_selection{
        border: 1px dashed #ff6e40;
        border-radius: 15px;
        background: #ff6e4010;
        width: 100%;
        margin: 10px auto;
        cursor: pointer; 
        min-height: 100px; 
        justify-content: center; 
        display: flex; 
        align-items: center;
    }
    .image_selection_child{
        justify-content: center;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        padding: 10px;
    }
    .gallery-item.active{
        border: 3px solid black;
        border-radius: 10px;
        transition: .3s all;
    }
    .gallery-item.selected{
        border-radius: 10px;
        border: 3px solid #04a61f;
        transition: .3s all;
    }
    .gallery.gallery-md .gallery-item{
        width: 140px;
        height: 140px;
        transition: .3s all;
    }
    .gallery.gallery-fw .gallery-item {
        width: fit-content;
        height: 120px;
        object-fit: cover;
        transition: .3s all;
        margin: 2px;
    } 
    .active > .imagecheck-image,
    .selected > .imagecheck-image{
        width: 140px;
        height: 140px;
        object-fit: contain;
        padding: 1px;
        border-radius: 8px !important;
    } 
    .imagecheck-image{
        width: 140px;
        height: 140px;
        object-fit: contain;
        opacity: 1;
        transition: .3s all;
    }
    #append_images_here > *{
        width: max-content;
    }
    #append_images_here::-webkit-scrollbar {
        width: 2px;
        height: 2px;
    }
    #append_images_here{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: flex-start;
        height: 60vh;
        overflow-y: scroll;
    }
    #image_show:empty{
        display: none;
    }

    [pdf-section-list]{
        display: unset;
    }
    [image-section-list]{
        display: unset;
    }
    [type="pdf"] [image-section-list]{
        display: none;
    }
    [type="image"] [pdf-section-list]{
        /*display: none;*/
    }
    .hide-pdf [pdf-section-list],
    .hide-img [image-section-list]{
        display: none;
    }
    [image_parent]{
        text-align: center;
    }

    /*End*/
</style>
<input type="hidden" id="images_page_id" value="1">
<?php $image_save_url = url('image-update'); ?>
<!-- image modal -->
<div class="modal fade image_modal" id="image_modal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="height:93vh">
            <div class="modal-header">
                <h5 class="modal-title mb-1" id="myModalLabel">Select Files</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" style="overflow-y:auto;">
                <div class="row">
                    <div class="col-12 ">
                        <div class="card">
                          <!-- <div class="card-header">
                            <h4>Gallery <code>.gallery-md</code></h4>
                          </div> -->
                            <div class="">
                                <div class="row">
                                    <div class="col-md-12 pb-2">
                                        <div id="accordion_image">
                                            <div class="accordion">
                                                <a type="button" href="javascript:;" class="accordion-header collapsed btn btn-outline-primary me-3" role="button" data-bs-toggle="collapse" data-bs-target="#add-image-body" aria-expanded="true"> Upload New Files</a>
                                                <div class="accordion-body collapse hide" id="add-image-body" data-bs-parent="#accordion_image">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input class="d-none" type="file" multiple="multiple" id="uploader_type" accept="image/jpeg,png,webp" >
                                                            <div class="drop-area"> 
                                                                <div class="text-center row">
                                                                  <i class="bi bi-cloud-arrow-up mb-2 upload-icon"></i>
                                                                  <h3 class="img-upload-title">Click "Here" or drop your Image/PDF here</h3>
                                                                </div>
                                                            </div> 
                                                            <input type="hidden" name="images" data-image callback-function="load_first_page">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="card card-primary" style="background:#fafdfb;"> 
                                            <div class="card-header" id="show-onselect" style="display:none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        Selected Image/File(s)
                                                        <input type="hidden" class="form-control" 
                                                            search_images 
                                                            name="search_images" 
                                                            placeholder="Search Here...">
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-end">
                                                        <input type="hidden" id="selected_image_id">
                                                        <input type="hidden" id="selected_image_url">
                                                        <a class="btn btn-success waves-effect waves-light float-right text-white" onclick="selectedImageDone(this)">Add Selected File(s)</a>
                                                    </div>
                                                </div>
                                                <div class="row pt-2">
                                                    <div class="col-12">
                                                        <div class="card-header-action" style="">
                                                            <div class="gallery gallery-fw d-none" id="image_show" data-item-height="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body px-3 p-2">
                                                <div class="row" id="append_images_here" type="all">
                                                    
                                                </div>
                                                <!-- <div class="row" id="append_images_here"> -->
                                                    <!-- <div class="gallery gallery-md" id="append_images_here"> -->

                                                    <!-- </div> -->
                                                <!-- </div> -->
                                                <div class="row my-5 pb-4 d-none">
                                                    <div class="col-md-12 text-center card-header-action">
                                                        <a href="javascript:;" id="load_more_images" onclick="load_more_images()" class="btn btn-primary btn-lg" style="font-size: 20px;">View More</a>
                                                    </div>
                                                </div>
                                                    

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" >
                                        <div class="card card-primary" style="background:#fafdfb;"> 
                                        <form class="form image_form" method="post" id="image_form" action="{{$image_save_url}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-header d-flex justify-content-between">
                                                <h5>File Detail </h5>
                                                <div class="card-header-action">
                                                     <!--<button type="submit" class="btn btn-sm btn-outline-info waves-effect waves-light float-right"><i class="fa fa-spinner fa-spin d-none" tabindex="20"></i> update</button>-->
                                                    <button type="button" onclick="delete_image()" class="btn btn-sm btn-outline-danger waves-effect waves-light float-right">Delete</button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <input type="hidden" name="image_id">
                                                    <input type="hidden" name="image_status">
                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="1">Alt Tag: </label>
                                                            <input type="text" name="image_alt_tag"  class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="2">Detail: </label>
                                                            <input type="text" name="image_details"  class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" style="display:none;">
                                                        <div class="form-group">
                                                            <label for="3">Name: </label>
                                                            <input type="text" name="image_name"  class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="4">File Name: </label>
                                                            <input type="text" name="image_file_name" readonly class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="5">Thumb Url: </label>
                                                            <input type="text" name="image_thumb_url" readonly  class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="6">Original Url: </label>
                                                            <input type="text" name="image_original_url" readonly  class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- image modal -->


<script type="text/javascript">

const proxy_domain = 'imageproxy.inlancer.in';
// let proxy = 'https://'+proxy_domain+'/pr:sharp/f:webp/rs:fit:[[size]]/g:sm/plain/';
@if(env('APP_ENV') == 'production')
    let proxy = 'https://'+proxy_domain+'/pr:sharp/f:webp/rs:fit:[[size]]/g:sm/plain/';
@else
    let proxy = '';
@endif

window.image_selection_area = '';

window.image_ids  =[];
window.image_urls =[];
window.images_show=[];

jQuery(document).ready(function() { 
    set_images_in_area();
    $('#image_show').addClass('d-none'); 
    var imgFom = {
        beforeSend: function() { 
            $('.fa-spinner').removeClass('d-none');
            // console.log('before_page',$('.gallery-item[data-page_no]'));
        },
        uploadProgress: function(event, position, total, percentComplete) { 
        },
        success: function() {},
        complete: function(response) {
            // console.log('complete_page',$('#images_page_id').val());
            var result = jQuery.parseJSON(response.responseText);
            $('.fa-spinner').removeClass('d-none');
            $('.fa-spinner').addClass('d-none');
            if (result.status == 200) {
                successToast(result.message);
            }else{
                errorToast(result.message);
            }
        },
        error: function() { 
        }
    };
    jQuery("#image_form").ajaxForm(imgFom);
});

$(document).on('show.bs.modal', '.modal', function(){ /*Edit Time Set Ids & url */
    var type_of_form =  $('.modal').find('input[name="mode"]').val();
    if(type_of_form=='edit'){
        set_images_in_area();
    }
});

$(document).on("error", "img" ,function () {
    $(this).attr("src", '{{asset("/assets/inlancer_portal/img/placeholder.png")}}');
});

$('input[search_images]').keyup(delay(function (e) {
   // console.log(this.value);
    $("#images_page_id").val(1);
    load_more_images();
}, 500)) 
/*End Filter*/

/* image list ajax loading functions*/
    function load_first_page() { /*first page load immages*/
        $("#images_page_id").val(1);
        load_more_images();
        /*upload success toast here*/
        successToast("File uploaded Successfully");

        setTimeout(function(){
            reset_all_droparea();
            $('#add-image-body').addClass('hide');
            $('#add-image-body').removeClass('show');
        },1000);
    }
    function delay(callback, ms) {
      var timer = 0;
      return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
          callback.apply(context, args);
        }, ms || 0);
      };
    }
    function load_more_images() {
        $('#load_more_images').addClass('disabled');
        $('#load_more_images').text('loading..');
        var page_id = $("#images_page_id").val();
        var search = $("input[search_images]").val();
        jQuery.ajax({
            url: "{{url('image-list-ajax')}}",
            method: 'POST',
            data: {'_token':csrf_token,'search':search,'page_no':page_id},
            success: function(result){
                if(result.trim() != 'stop'){
                    if(page_id == 1){
                        $("#append_images_here").empty();
                    }
                    $("#append_images_here").append(result);
                    $("#images_page_id").val(parseFloat(page_id)+1);
                    // $("div[page_row]").add
                    // $("div[page_row]").attr('id', page_id);

                    $('#load_more_images').removeClass('disabled');
                    $('#load_more_images').removeClass('d-none');
                    $('#load_more_images').text('Load More');
                }else{
                    $('#load_more_images').remove();
                }
            }
        });
    }
/* image list ajax loading functions*/




/* open image modal & set window variable */
function image_modal_open(passed_this){ /*All Master image dorp click to open modal*/
    window.image_selection_area = $(passed_this);

    let type = $(passed_this).attr('type');
    $('#append_images_here').removeClass('hide-pdf hide-img');
    console.log(type);
    if(type){
        if(type=='file'){
            $('#append_images_here').addClass('hide-img');
        }else{
            $('#append_images_here').addClass('hide-pdf');
        }
    }else{
        $('#append_images_here').removeClass('hide-pdf hide-img');
    }

    var uploader_type = 'image/*'
    try{
        var uploader_type = $(passed_this).find('[data-image]').attr('accept');
        $('#append_images_here').removeAttr('type');

        if(uploader_type == '.pdf'){
            $('#append_images_here').attr('type','pdf');
        }else{
            $('#append_images_here').attr('type','image');
        }
    }catch(e){
        $('#append_images_here').removeAttr('type');
        $('#append_images_here').attr('type','all');
    }

    $('#uploader_type').attr('accept',uploader_type);
     
    // $('#image_modal').modal();
    $('#image_modal').modal('show');

    load_more_images();
    $('#selected_image_id').val('');
    $('#selected_image_url').val('');

    window.image_ids  =[];
    window.image_urls =[];
    window.images_show=[];

    setTimeout(function(){
        if(window.image_selection_area.find('[image_parent]').length == 0){
            $('#image_show').empty();
            $('#show-onselect').hide();
        }else{
            window.image_selection_area.find('[image_parent]').each(function(index, el) {
                set_selected_data_multi($(el).attr('id'),$(el).find('.previewImage').attr('src'));
            });
        }
    },200);
}

/* edit modal set images in area */
function set_images_in_area() {
    $('[image_selection]').each(function(index, el) {

        var uploaded_image = $(el).attr('uploaded-image');
        var uploaded_image_id = $(el).find('[data-image]').val();
        if (typeof uploaded_image !=='undefined' && uploaded_image !=='') {
            var uploaded_image_arr = uploaded_image.split(',');
            var uploaded_image_id_arr = uploaded_image_id.split(',');
            
            var div = '';
            var inc = 0;
            for(let temp_uploaded_image of uploaded_image_arr){
                div += '<div style="width: auto; min-width:150px;" image_parent id="'+uploaded_image_id_arr[inc]+'" class="mx-2"><div style="padding: 5px;margin-bottom: 0px;"><img class="previewImage" src="'+temp_uploaded_image+'"></div><a class="btn btn-danger text-white" onclick="event.stopImmediatePropagation();removeImg(this);" style="width: 100%;padding:0 0;cursor: pointer;">Remove</a></div>';
                inc++;
            }
            if(div !== ''){
                $(el).find('[image_selection_child]').empty();
                $(el).find('[image_selection_child]').append(div);
            }
        }
    });
}

/* on click get image data & set in side form -> if user wants to update */    
function get_and_set_image_detail(passData){
    // console.log(passData);
    $('#append_images_here').find('.gallery-item').removeClass('active');
    $(passData).addClass('active');

    //side form data set
        var image_id          =$(passData).data('image_id');
        var image_name        =$(passData).data('image_name');
        var image_file_name   =$(passData).data('image_file_name');
        var image_thumb_url   =$(passData).data('image_thumb_url');
        var image_original_url=$(passData).data('image_original_url');
        var image_alt_tag     =$(passData).data('image_alt_tag');
        var image_details     =$(passData).data('image_details');
        var image_status      =$(passData).data('image_status');


        let width = '280';
        let height = '120';
        width = Math.ceil(width)+50;
        height = Math.ceil(height)+50;
        if(width < height){
            image_original_url = proxy.replace('[[size]]', '0:'+ height) + image_original_url;
        }else{
            image_original_url = proxy.replace('[[size]]', width+':0')+image_original_url;
        }
        
        $('input[name="image_id"]').val(image_id);
        $('input[name="image_name"]').val(image_name);
        $('input[name="image_file_name"]').val(image_file_name);
        $('input[name="image_thumb_url"]').val(image_thumb_url);
        $('input[name="image_original_url"]').val(image_original_url);
        $('input[name="image_alt_tag"]').val(image_alt_tag);
        $('input[name="image_details"]').val(image_details);
        $('input[name="image_status"]').val(image_status);


    // console.log("gid",image_id,image_original_url);
    var isMultiple = window.image_selection_area.find('[multiple]'); 
    if(isMultiple.length == 1){ //img_id multi
        set_selected_data_multi(image_id,image_original_url) /*Set selected Data*/
    }else{
        set_selected_data_single(image_id,image_original_url) /*Set selected Data*/
    }
}

/* set multiple image in selection */
function set_selected_data_multi(image_id,image_original_url){
    $('#image_show').removeClass('d-none');
    $('#show-onselect').show();
    /*Image ids set value ","*/
    if(window.image_ids.indexOf(image_id) === -1){
        window.image_ids.push(image_id);
    }else{
        window.image_ids.splice(window.image_ids.indexOf(image_id),1);
    }
    $('#selected_image_id').val(window.image_ids.join(",")); 
   
    if(window.image_urls.indexOf(image_original_url) === -1){
        window.image_urls.push(image_original_url)
    }else{
        window.image_urls.splice(window.image_urls.indexOf(image_original_url),1);
    }
    $('#selected_image_url').val(window.image_urls.join(","));

    var passImg = '<img class="gallery-item" onclick="remove_image_from_selection(this)" img-id="'+image_id+'" src="'+image_original_url+'">';
    
    if(window.images_show.indexOf(passImg) === -1){
        window.images_show.push(passImg)
        $('[data-image_id="'+image_id+'"]').addClass('selected');
    }else{
        window.images_show.splice(window.images_show.indexOf(passImg),1);
        $('[data-image_id="'+image_id+'"]').removeClass('selected');
        $('[data-image_id="'+image_id+'"]').removeClass('active');
    }

    $('#image_show').html(window.images_show);
}

/* set single image in selection */
function set_selected_data_single(image_id,image_original_url){
    $('.gallery-item').removeClass('selected');
    $('#image_show').removeClass('d-none');
    $('#show-onselect').show();
    /*Image ids set value ","*/
    $('[data-image_id="'+image_id+'"]').addClass('selected');
    $('[data-image_id="'+image_id+'"]').removeClass('active');
    $('#selected_image_id').val(image_id); 
   
    /*Image Url set value ","*/
    $('#selected_image_url').val(image_original_url);
    /*End Image Url*/
    /*multiple images Show in modal*/
    var passImg = '<img class="gallery-item" onclick="remove_image_from_selection(this)" src="'+image_original_url+'">';
    // images_show.indexOf(passImg) === -1 ? images_show.push(passImg) : images_show.splice(images_show.indexOf(passImg),1);
    $('#image_show').html(passImg);
    /*End multiple img*/
}

/* set image in area + set id in hidden field & close modal */
function selectedImageDone(){
    window.image_selection_area.find("[image_selection_child]").empty();
    window.image_selection_area.find("[image_parent]").remove();
    var image_url_array = $('#selected_image_url').val().split(",");
    var selectedImageDone = $('#selected_image_id').val().split(",");        
    set_url(image_url_array,selectedImageDone);
    
    window.image_selection_area.attr('uploaded-image',$('#selected_image_url').val());

    $('#image_modal').modal('hide');
}



/*Remove Image Into Form*/
function removeImg(passed_this){
    $passed_this = $(passed_this).parents('[image_parent]');
    $passed_this_section = $(passed_this).parents('[image_selection]');

    var img_id = $passed_this.attr('id');

    var imgIds = $passed_this_section.find('input[data-image]').val();
    imgIds = $.grep(imgIds.split(','), function(value) {
      return value != img_id;
    });
    $passed_this_section.find('input[data-image]').val(imgIds.join(','));
    
    var remove_img_path = $passed_this_section.find('.previewImage').attr('src');
    var img_path = $passed_this_section.attr('uploaded-image');
    img_path = $.grep(img_path.split(','), function(value) {
      return value != remove_img_path;
    });
    $passed_this_section.attr('uploaded-image',img_path.join(','));

    $passed_this.remove();

    $('[image_selection_child]').each(function(index, el) {
        if($(el).find('[image_parent]').length <= 0){
            $(el).html('<i class="bi bi-cloud-arrow-up mb-2 upload-icon"></i><h3 class="img-upload-title">Select your Image here</h3>');
        }
    });
}
/*End Remove Image Into Form*/

function remove_image_from_selection(passed_this) {

    var result = confirm("Remove image from selection ?");
    if (result) {
        // console.log($('#selected_image_id').val())
        var remove_img_id = $(passed_this).attr('img-id');
        var remove_img_url = $(passed_this).attr('src');
        $('#append_images_here').find('[data-image_id="'+remove_img_id+'"]').removeClass('active selected')
        // selected_image_url
        // selected_image_id

        /*selected_image_ids remove into input */
        var imgIds = $('#selected_image_id').val();
        var NewimgIds = $.grep(imgIds.split(','), function(value) {
          return value.trim() != remove_img_id;
        });

        /*selected_image_url remove into input */
        var imgUrl = $('#selected_image_url').val();
        var NewimgUrl = $.grep(imgUrl.split(','), function(value) {
          return value.trim() != remove_img_url;
        });
        
        window.image_ids = NewimgIds;
        window.image_urls = NewimgUrl;

        var passImg = '<img class="gallery-item" onclick="remove_image_from_selection(this)" img-id="'+remove_img_id+'" src="'+remove_img_url+'">';

        if(window.images_show.indexOf(passImg) === -1){
            window.images_show.push(passImg)
            $('[data-image_id="'+remove_img_id+'"]').addClass('selected');
        }else{
            window.images_show.splice(window.images_show.indexOf(passImg),1);
            $('[data-image_id="'+remove_img_id+'"]').removeClass('selected');
            $('[data-image_id="'+remove_img_id+'"]').removeClass('active');
        }

        $('#selected_image_id').val(NewimgIds.join(','));
        $('#selected_image_url').val(NewimgUrl.join(','));
        // console.log($('#selected_image_id').val())

        $(passed_this).remove();
        if ($('#image_show').children().length < 1) {
            $('#show-onselect').hide();
        }
    }

}

function set_url(image_url_array,img_ids){
    image_url_array = image_url_array.filter(n => n);
    img_ids = img_ids.filter(n => n);

    if(image_url_array.length <= 0){
        window.image_selection_area.find('[image_selection_child]').html('<i class="bi bi-cloud-arrow-up mb-2 upload-icon"></i><h3 class="img-upload-title">Select your Image here</h3>'); 
        return false;
    }
    for (i=0;i<image_url_array.length;i++){
        var image_html = '<div style="width: auto; min-width:150px;" image_parent id="'+img_ids[i]+'" class="mx-2"><div style="padding: 5px;margin-bottom: 0px;"><img class="previewImage" src="'+image_url_array[i]+'"></div><a class="btn btn-danger text-white" onclick="removeImg(this)" style="width: 100%;padding:0 0;cursor: pointer;">Remove</a></div>';
        window.image_selection_area.find('[image_selection_child]').append(image_html); /*append url*/
    }
    window.image_selection_area.find('input[data-image]').val(img_ids.join(",")); /* ',' append ids */
}

function delete_image(){
    let id = $('input[name="image_id"]').val();    
    var form_data={_token:'{{csrf_token()}}',id:id}; 
    Swal.fire({
      title: "Confirm",
      text: "Are you sure you want to delete this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#1FAB45",
      confirmButtonText: "Yes, Delete it.",
      cancelButtonText: "Cancel",
      buttonsStyling: true
    }).then((result) => {
        if (result.value == true) {
          jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            type: "POST",
            data: form_data,
            url: "{{url('image-delete')}}",
            cache: false,
            success: function(response) {
                var json_data = response;       
                if (json_data.status=="200") { 
                    Swal.fire({
                        title: "Success!",
                        text: "Data deleted successfully!",
                        type: "success",
                        timer: 800,
                    });
                    $('[data-image_id="'+id+'"]').remove();
                }else{
                  Swal.fire(
                      "Internal Error",
                      "Oops,Error Occurred.",
                      "error"
                  )
                };
            }
            });
        } else{
            Swal.fire({
            title: "Cancelled",
            text: "Your data is safe Now! ",
            type:"error",
            timer:800
            })  ;
        }
    }, 
    function (dismiss) {
      if (dismiss === "cancel") {
        Swal.fire(
        "Internal Error",
        "Oops, Some Error Occurred.",
        "error"
        );
      }
    })
  }
 
/* Load more images on scroll */
$(document).ready(function() {
    $('#append_images_here').scroll(function() {
        var scrollHeight = $(this)[0].scrollHeight;
        var scrollPosition = $(this).height() + $(this).scrollTop();
        
        // When user scrolls to 80% of the container height, load more images
        if (scrollPosition >= (scrollHeight * 0.8)) {
            // Only load more if not already loading (prevent multiple calls)
            if (!$('#load_more_images').hasClass('disabled')) {
                load_more_images();
            }
        }
    });
});
</script>
