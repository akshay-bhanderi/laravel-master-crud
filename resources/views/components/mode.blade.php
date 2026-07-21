@csrf
@php( $id_found = $data['id'] ?? $id ?? '' )
@if( $id_found )
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="id" value="{{$data['id'] ?? $id ?? ''}}">
@else
<input type="hidden" name="mode" value="add">
@endif