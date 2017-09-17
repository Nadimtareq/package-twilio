@foreach($data as $fetch)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $fetch->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $fetch->subject }}</td>
    <td>{{ $fetch->type }}</td>
    <td>{{ $fetch->name }}</td>
    <!--<td>{{ $fetch->count }}</td>-->
    <td><?php
        $link = mysqli_connect("localhost", "marvelso_martin", "capitive@305@pune", "marvelso_livepass");
        $sql = "select * from mail_templates where company_id=1 having type='$fetch->type'";
        $result = mysqli_query($link, $sql) or die(mysqli_error());
        echo mysqli_num_rows($result);

    ?></td>
    <td>{{ $fetch->price }}</td>
    <td>{{ $fetch->tot_price }}</td>
    <td>
        <div class="ui toggle checkbox activeChange" data-id="{{ $fetch->id }}">
            <input type="checkbox" name="public" {!! ($fetch->is_active == 0 ? 'checked="checked"' : '') !!}>
        </div>
    </td>
     <td>
        <a href="{{ url('../twilioapi/public/home') }}" class="ui button">
            <i class="icon"></i> Send
        </a>
    </td>
    <td>
        <a href="{{ url('admin/mailtemplates/update/'.$fetch->id) }}" class="ui button">
            <i class="pencil icon"></i> Bewerk
        </a>
    </td>
</tr>
@endforeach