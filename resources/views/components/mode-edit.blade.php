@csrf
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="id" value="{{$data['id'] ?? $id ?? ''}}">