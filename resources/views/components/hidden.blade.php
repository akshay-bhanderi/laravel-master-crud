<?php 
if(empty($id)){
	$id = time()*rand(11,99999999);
}
if(empty($label)){
    $label = str_replace('_', ' ', $name).' : ';
}
?>
<input type="hidden" id="{{$id ?? ''}}" {!! $attr ?? ''!!}  name="{{$name ?? ''}}" class="{{ $class ?? '' }}" value="{!! $value ?? $data[$name] ?? '' !!}" >