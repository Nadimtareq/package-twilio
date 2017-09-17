@inject('affiliateHelper', 'App\Helpers\AffiliateHelper')
@foreach($data as $data)
<tr>
   <td>
        <div class="ui child checkbox">
            <input type="checkbox" name="id[]" value="{{ $data->id }}">
            <label></label>
       </div>
    </td>
    <td>{{ $data->name }}</td>
    <td>{{ $affiliateHelper->commissionMaxValue($data->compensations) }}</td>
    <td>{{ $data->clicks }}</td>
    <td>{{ $data->amount }}</td>
    <td>{{ strtotime($data->updated_at) > 0? date('d-m-Y', strtotime($data->updated_at)) : 'Niet gewijzigd' }}</td>
    <td>{{ $data->catName }}</td>
    <td>
        <a href="{{ URL::to('admin/'.$slugController.'/update/'.$data->id) }}" class="ui icon button">
            <i class="pencil icon"></i>
        </a>

        <a href="{{ URL::to('admin/'.$slugController.'/update/'.$data->id) }}" class="ui icon button">
            <i class="{{ $data->no_show  == 1 ? 'red remove' : 'green checkmark' }} icon"></i>
        </a>
    </td>
</tr>
@endforeach