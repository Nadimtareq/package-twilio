@foreach($data as $data)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $data->title }}</td>
    <td>{{ date('d-m-Y H:i:s', strtotime($data->created_at)) }}</td>
    <td>{{ date('d-m-Y H:i:s', strtotime($data->updated_at)) }}</td>
    <td>{{ $data->name }}</td>
    <td>{!! $data->is_published ? '<i class="circle large green mark icon"></i>' : '<i class="circle large orange icon"></i>'  !!}</td>
    <td>
        <a href="{{ URL::to('admin/news/update/'.$data->id) }}" class="ui label"><i class="pencil icon"></i> Wijzigen</a>
        <a href="{{ URL::to('news/'.$data->slug) }}" class="ui blue label" target="_blank"><i class="eye icon"></i> Bekijk</a>
    </td>
 </tr>
 @endforeach