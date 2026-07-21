<?php 
if(empty($id)){
	$id = time()*rand(11,99999999);
}
if(empty($label)){
    $label = str_replace('_', ' ', $name);
}
if( !empty($attr) && $attr == 'slug' ){
    $label = $label." <a href='#' tabindex='-1' class='bi bi-question-circle ms-1 small' data-bs-toggle='tooltip' title='Slug/Link will be Auto Generated! It Required for google SEO. Dont change anything if not required'></a>";
}
?>
@if(empty($nolabel))
<label for="{{$id ?? ''}}">{!! $label ?? '' !!} : </label>
@endif
<textarea 
    placeholder="{{$placeholder ?? ''}}"
    {{$attr ?? '' }} 
    id="{{$id ?? ''}}" 
    rows="{{$rows ?? ''}}" cols="{{$cols ?? ''}}" 
    name="{{$name ?? ''}}" class="form-control {{ $class ?? '' }}" 
    >{!! $data[$name] ?? $value ?? '' !!}</textarea>

@if(!empty($class) && str_contains($class, 'summernote'))
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#{{$id}}').summernote({
        height: {{  ($rows/2)*100 ??  '450'}},
        toolbar: [
            ['style', ['style', 'bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['font', ['fontname', 'fontsize' ,'height' , 'color', 'superscript', 'subscript']],
            ['table', ['table']],
            ['para', [ 'ol', 'ul', 'paragraph']],
            ['misc', ['undo', 'redo']],
        ],
        callbacks: {
            onImageUpload: function(files, editor, welEditable) {
                url = $(this).data('upload'); //path is defined as data attribute for  textarea
                var data = new FormData();
                data.append('image_file', files[0]); 
                data.append('_token', csrf_token); 

                $.ajax({
                    data    : data,
                    type    : "POST",
                    url     : "{{url('common-image-upload')}}",
                    cache   : false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#{{$id}}').summernote('insertImage', response.image_url, files.name);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    }
                });
            }
        }
    });
});
</script>
@endif