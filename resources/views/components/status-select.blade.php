@if(empty($selected) && isset($data[$name]))
	@php($selected = $data[$name])
@endif
@if(empty($optionlist) && isset($status_arr))
	@php($optionlist = $status_arr)
@endif
@if(isset($selected))
	@php($temp_selected = (array)explode(',', $selected))
@endif
@if(empty($optionlist))
	@php($optionlist = ['1' => 'Active','0' => 'InActive'])
@endif
<label for="withdrawinput1">{{ $lable ?? 'Status'}} : </label>
<input type="hidden" name="{{$name ?? 'status'}}" value="1">
<select {{$attr ?? ''}} class="form-control {{$class ?? ''}}" name="{{$name ?? 'status'}}" {{$multiple ?? ''}} onchange="this.querySelectorAll('option').forEach(option => option.removeAttribute('selected')); this.options[this.selectedIndex].setAttribute('selected', 'selected');" >
	@foreach($optionlist as $key => $value) 
		@php($is_selected = '')
		@if( !empty($temp_selected) && in_array($key, $temp_selected))  
			@php($is_selected = 'selected')
		@endif
		<option {{$is_selected}} value="{{$key}}">{{$value}}</option>
	@endforeach
</select>