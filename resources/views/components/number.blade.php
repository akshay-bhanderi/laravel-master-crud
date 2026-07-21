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
<input step="{{ $step ?? 'any' }}" placeholder="{{$placeholder ?? ''}}" inputmode="numeric" type="number" id="{{$id ?? ''}}" {!! $attr ?? ''!!}  name="{{$name ?? ''}}" class="form-control {{ $class ?? '' }}" value="{!! $value ?? $data[$name] ?? '' !!}" min="{{$min ?? '' }}" max="{{$max ?? '' }}" >