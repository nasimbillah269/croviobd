@if($datas->count() > 1)
<option value="">Select Option</option>
@endif
@foreach($datas as $data)
<option value="{{$data->id}}">{{$data->name}}</option>
@endforeach