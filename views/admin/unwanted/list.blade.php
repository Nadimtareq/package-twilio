@foreach($data as $data)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $data->word }}</td>
    <td>@if($data->short==0)	
	 E-mail 
	@elseif($data->short==1)
	 Website 
	@else
	 Both 
	@endif
	</td>
    <td><a href="{{ url('admin/'.$slugController.'/update/'.$data->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a></td>
</tr>
@endforeach