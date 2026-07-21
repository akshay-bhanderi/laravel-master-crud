@if(isset($selected))
	@php($temp_selected = (array)explode(',', $selected))
@elseif(isset($data[$name]))
	@php($temp_selected = (array)explode(',', $data[$name]))
@endif
@if(empty($options))
	@php($options = ['' => 'Select options'])
@endif

@if(empty($label))
	@php( $label = str_replace('_', ' ', $name).' : ' )
	@php( $label = str_replace(['[',']'], '', $label) )
@endif
@if(empty($id))
	@php($id = time())
@endif
@if(empty($nolabel))
<label for="{{$id ?? ''}}">{!! $label ?? '' !!}</label>
@endif
<select {{$attr ?? ''}} class="form-control {{$class ?? ''}}" name="{{$name ?? ''}}" id="{{$id ?? ''}}" {{$multiple ?? ''}} data-selected="{{$selected ?? $data[$name] ?? ''}}">
	@if( !empty($selectoption))  
		<option>{{$selectoption ?? 'Select Option'}}</option>
	@endif
	@foreach($options as $key => $value) 
		@php($is_selected = '')
		@if( !empty($temp_selected) && in_array($key, $temp_selected))  
			@php($is_selected = 'selected')
		@endif
		<option {{$is_selected ?? ''}} value="{{$key}}">{{$value}}</option>
	@endforeach
</select>

@if(!empty($class) && str_contains($class, 'select2'))
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#{{$id}}').select2();
	});
</script>
@endif