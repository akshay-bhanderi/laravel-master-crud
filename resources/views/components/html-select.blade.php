@if(empty($id))
	@php($id = time())
@endif
<select data-selected="{{$selected ?? ''}}" class="form-control {{$class ?? ''}}" name="{{$name ?? ''}}" id="{{$id ?? ''}}" {{$multiple ?? ''}} >
	<option disabled selected >Loading Options</option>
</select>

@if(!empty($class) && str_contains($class, 'select2'))
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#{{$id}}').select2();
	});
</script>
@endif