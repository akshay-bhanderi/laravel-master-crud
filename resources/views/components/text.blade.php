<?php 
if(empty($id)){
	$id = time()*rand(11,99999999);
}
if(empty($label)){
    $label = str_replace('_', ' ', $name).' : ';
}
if( !empty($attr) && $attr == 'slug' ){
	$label = $label." <a href='#' tabindex='-1' class='bi bi-question-circle ms-1 small' data-bs-toggle='tooltip' title='Slug/Link will be Auto Generated! It Required for google SEO. Dont change anything if not required'></a>";
}
?>
@if(empty($nolabel))
<label for="{{$id ?? ''}}">{!! $label ?? '' !!}</label>
@endif
<input placeholder="{{$placeholder ?? ''}}" type="text" id="{{$id ?? ''}}" {!! $attr ?? ''!!}  name="{{$name ?? ''}}" class="form-control {{ $class ?? '' }}" value="{!! $value ?? $data[$name] ?? '' !!}"   @if(!empty($readonly) && $readonly) readonly @endif>