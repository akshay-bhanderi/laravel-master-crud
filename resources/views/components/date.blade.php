<?php

    if(empty($id)){
    	$id = time()*rand(11,99999999);
    }
    if(empty($label)){
        $label = str_replace('_', ' ', $name).' : ';
    }

    $final_val = '';
    if (!empty($value)) {
        $final_val = date('Y-m-d', strtotime($value));
    } elseif (isset($data[$name])) {
        $final_val = date('Y-m-d', strtotime($data[$name]));
    } else {
        $final_val = date('Y-m-d');
    }

    // $final_val = '';
    // if(!empty($value)){
    //     $final_val = date('Y-m-d', $value);
    // }elseif(isset($data[$name])){
    //     $final_val = date('Y-m-d', strtotime($data[$name]) );
    // }else{
    //     if(isset($value) && $value == ''){
    //         $final_val = '';
    //     }else{
    //         $final_val = date('Y-m-d');
    //     }
    // }

?>
@if(empty($nolabel))
<label for="{{$id ?? ''}}">{!! $label ?? '' !!}</label>
@endif
<input type="date" id="{{$id ?? ''}}" {!! $attr ?? ''!!}  name="{{$name ?? ''}}" class="form-control {{ $class ?? '' }}" value="{!! $final_val ?? '' !!}" >