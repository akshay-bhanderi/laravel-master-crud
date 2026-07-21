<?php

    if(empty($id)){
    	$id = time()*rand(11,99999999);
    }
    if(empty($label)){
        $label = str_replace('_', ' ', $name).' : ';
    }


?>

@if(empty($nolabel))
<label for="{{$id ?? ''}}">{!! $label ?? '' !!}</label>
@endif
<input type="datetime-local" id="{{$id ?? ''}}" {!! $attr ?? ''!!}  name="{{$name ?? ''}}" class="form-control {{ $class ?? '' }}" value="{!! $value ?? '' !!}" >