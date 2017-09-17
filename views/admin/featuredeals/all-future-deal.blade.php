@extends('template.theme')

@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')

<div class="content">
    @include('admin.template.breadcrumb')
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="left floated  wide computer sixteen wide mobile column">
                <?php echo Form::open(array('method' => 'GET', 'class' => 'ui form')); ?>
                <div class="four fields">
					<div class="three field">
						<select name="company" class="multipleSelect">
						 @foreach ($companies as $company)
						  <option value="{{ $company->id }}">{{ $company->name }}</option>
						  @endforeach
						</select>
                       
                        </div>
                    <div class="field">
                        <?php 
                        echo Form::select(
                            'month', 
                            array_unique($months), 
                            Request::has('month') ? Request::has('month') : date('m'), 
                            array(
                                'class' => 'multipleSelect', 
                                'data-placeholder' => 'Bedrijf'
                                )
                            ); 
                            ?>
                        </div>

                        <div class="field">
                           <?php echo Form::select('year', array_unique($years), (Request::has('year') ? Request::get('year') : date('Y')), array('class' => 'multipleSelect', 'data-placeholder' => 'Jaar')); ?>
                       </div>
						
                       <div class="field">
                        <input type="submit" class="ui blue fluid filter button" value="Filteren" />
                    </div>
                </div>
                <?php echo Form::close(); ?>
            </div>
            <div class="right floated sixteen wide mobile seven wide computer column">
               <div class="ui grid">
                <div class="three column row">
                    <div class="sixteen wide mobile five wide computer column">
                        @include('admin.template.limit')
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
<button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
    <i class="trash icon"></i> Verwijderen
</button>   

<table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
    <thead>
        <tr>
            <th data-slug="disabled" class="disabled" width="5%">
                <div class="ui master checkbox"><input type="checkbox"></div>
            </th>
            <th data-slug="user_name" class="two wide" width="30%">Gebruikersnaam</th>
            <th data-slug="company_name" class="seven wide" width="30%">Deal naam</th>
            <th data-slug="future_deal_price" class="three wide" width="10%">Bedrijfsnaam</th>
            <th data-slug="created_at" class="two wide" width="10%">Gemaakt tijd</th>
            <th data-slug="expired_at" class="one wide" width="10%">Deal bedrag</th>
            <th data-slug="mollie_id" class="two wide" width="10%">Vervaldatum</th>
            <th data-slug="total_persons" class="two wide" width="5%">aantal</th>
            <!-- <th data-slug="payment_type" class="two wide">Blijf persoon</th> -->
        </tr>
    </thead>
    <tbody class="list search">
        @if(count($futureDeals) >= 1)
        @foreach($futureDeals as $futureDeal)
        <tr>
         <td>
            <div class="ui child checkbox">
                <input type="checkbox" name="id[]" value="{{ $futureDeal->future_deal_id }}">
                <label></label>
            </div>
        </td>
        <td>{{ $futureDeal->user_name }}</td>
        <td>{{ ucfirst($futureDeal->deal_name) }}</td>       
        <td>{{ ucfirst($futureDeal->company_name) }}</td> 
		<td>{{ date('d-m-Y', strtotime($futureDeal->created_at)) }}</td>		
        <td>&euro; {{ $futureDeal->future_deal_price }}</td>
       
        <td>{{ date('d-m-Y', strtotime($futureDeal->expired_at)) }}</td>
        <td>{{ $futureDeal->total_persons }}</td>
        <!-- <td>{{ $futureDeal->remain_persons }}</td>        -->
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
    </tr>
    @endif
</tbody>
</table>
<?php echo Form::close(); ?>
</div>
@stop