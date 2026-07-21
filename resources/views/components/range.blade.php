<?php 
if(empty($id)){
	$id = time()*rand(11,99999999);
}
if(empty($label)){
    $label = str_replace('_', ' ', $name).' : ';
}
?>
@if(empty($nolabel))
<label for="{{$id ?? ''}}">{!! $label ?? '' !!} (<small></small>) </label>
@endif
<input type="range" id="{{$id ?? ''}}" {!! $attr ?? ''!!}  name="{{$name ?? ''}}" class="form-range {{ $class ?? '' }}" value="{!! $value ?? $data[$name] ?? '' !!}" oninput="$(this).parent().find('small').text(this.value)" min="{{ $min ?? '1'}}" max="{{ $max ?? '100'}}" >
<div class="d-flex justify-content-between">
	<span>({{ $min ?? '1'}} - High)</span>
	<span>({{ $max ?? '100'}} - Low)</span>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('[type="range"]').trigger('input');
	});
</script>