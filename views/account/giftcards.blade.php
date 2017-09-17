@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
	$("#barcode").barcode(
		$("#barcode").data('code'), // Value barcode (dependent on the type of barcode)
		"code128" // type (string)
	);
});
</script>
@stop
<div class="shop">
	<div class="container">
        <div class="ui breadcrumb">
            <a href="{{ url('/') }}" class="section">Home</a>
            <i class="right chevron icon divider"></i>

            <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
            <i class="right chevron icon divider"></i>

            <div class="active section">Kopen Giftcard</div>
        </div>
        <div class="ui divider"></div>
        <div class="up">
            <div class="start">
                <h2>Verras uw partner of jarige met een cadeaubon van UWvoordeelpas!</h2>
			
                <ul class="list">
				
                    <li class="item">
                        <div class="wrap"><img src="{{asset('images/l1.png')}}" alt="l" /></div>
                        <p>1: Iemand verrassen met een cadeaubon? Bestel hem nu direct, keuze vanaf €5.</p>
                    </li>
					
                    <li class="item">
                        <div class="wrap"><img src="{{asset('images/l2.png')}}" alt="l" /></div>
                        <p>2: Kies hieronder uw gewenste bedrag en klik op de groene knop. U betaald snel en veilig online!</p>
                    </li>
					
                    <li class="item">
                        <div class="wrap"><img src="{{asset('images/l3.png')}}" alt="l" /></div>
                        <p>3: U ontvangt de code in uw account en op uw mail uw ontvanger activeert deze simpel <a href="{{ url('payment/giftcode') }}">hier</a>.</p>
                    </li>
					
                </ul>
            
        </div>
	</div>
</div>
<div class="container">
    <?php echo Form::open(array('url' => 'account/giftcards', 'method' => 'post', 'class' => 'ui form')) ?>

    <div class="field">
        <label>Selecteer de waarde van uw cadeaubon:</label>
        <?php echo Form::select('code', $data, 0, array('class' => 'ui normal search dropdown')); ?>
    </div>

    <button class="ui green button" name="action" value="update" type="submit">
        <i class="check mark icon"></i> Kopen Giftcard
    </button>

    <?php echo Form::close(); ?>
	
	<table class="ui sortable very basic collapsing celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th data-slug="disabled" class="disabled one wide">
            			<div class="ui master checkbox">
    					  	<input type="checkbox">
    					  	<label></label>
    					</div>
    				</th>
                    <th data-slug="is_active">Status</th>
                    <th data-slug="code">Code</th>
                    <th data-slug="amount">Saldo</th>
                    <th data-slug="max_usage">Maximale gebruik</th>
                    <th data-slug="used">Gebruikt</th>
                    <th data-slug="companyName">Bedrijf</th>
                    <th data-slug="created_at">Geactiveerd op</th>
                    <th data-slug="disabled" class="disabled"></th>
            	</tr>
            </thead>
            <tbody class="list search">
                @if(count($listing) >= 1)
                	@foreach($listing as $fetch)
						<tr>
							<td>
								<div class="ui child checkbox">
									<input type="checkbox" name="id[]" value="{{ $fetch->id }}">
									<label></label>
								</div>
							</td>
							<td>{{ $fetch->is_active == 1 ? 'Geactiveerd' : 'Niet geactiveerd' }}</td>
							<td>{{ $fetch->code }}</td>
							<td>{{ $fetch->amount }}</td>
							<td>{{ $fetch->max_usage }}</td>
							<td>{{ $fetch->used_no }}</td>
							<td>{{ trim($fetch->companyName) == '' ? 'UwVoordeelpas' : $fetch->companyName }}</td>
							<td>{{ date('d-m-Y', strtotime($fetch->created)) }}</td>
							<td><a href="{{ url('account/giftcards/updateGiftCard/'.$fetch->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a>
								<a href="{{ url('account/giftcards/usedGiftCard/'.$fetch->id) }}" class="ui label"><i class="eye icon"></i> Gebruik</a>
							</td>
						</tr>
					@endforeach
                @else
                    <tr>
                        <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                    </tr>
            	@endif
            </tbody>
   		</table>
</div>
