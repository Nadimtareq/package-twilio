@extends('template.theme')

@section('scripts')
@include('admin.template.editor')
<style>
    .user-image{
        width: 120px;
        height: 100px;
    }
</style>
<script type="text/javascript">
	$(document).ready(function() {
		closeBrowser();
	});
</script>
@stop

@section('content')
<div class="content">
	@include('admin.template.breadcrumb')

	<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form', 'files' => TRUE)) ?>
	<div class="ui grid">
		<div class="sixteen wide column">
			<div class="field">
				<label>Naam</label>
				<?php echo Form::text('name',$data->name); ?>
			</div>

			@if ($userAdmin)
			<div class="two fields">
				<div class="field">
					<label>Image</label>
					{{ Form::file('image',['class'=>'btn btn-file']) }}
                                        @if($data->image != '')
                                        <div class="user-image">
                                            <img src="{{ url('images/deals/'.$data->image) }}">
                                        </div>
                                        @endif
				</div>
				<div class="two fields">
					<div class="field">
						<label for="newsletter">{{trans('app.newsletter')}}</label>
						{{Form::select("newsletter", array('' => 'Not selected', '0' => 'niet toevoegen', '1' => 'toevoegen'), $data->newsletter, ['class' => 'ui normal icon search selection fluid dropdown margin-0','required' => 'required'])}}
					</div>
					<div class="field">
						<label for="no_show">Weergeven op site</label>
						{{Form::select("no_show", array('' => 'Not selected', '0' => 'Nee', '1' => 'Ja'), $data->no_show, ['class' => 'ui normal icon search selection fluid dropdown margin-0','required' => 'required'])}}
					</div>
				</div>
			</div>

			@endif

			@if ($userAdmin)
			<div class="field">
				<label>Bedrijf</label>
				<?php echo Form::select('company_id', $companies, $data->company_id, array('class' => 'ui normal search dropdown'));?>
			</div>
			@endif

			<br /> <br />

			<div class="two fields">
				<div class="field">
					<label>Datum van</label>

					<div class="ui icon input">
						<?php
						echo Form::text(
							'date_from',
							Carbon\Carbon::parse($data->date_from)->format('d-m-Y'),
							array(
								'class' => 'datepicker',
								'placeholder' => 'Selecteer een datum'
								)
							);
							?>
							<i class="calendar icon"></i>
						</div>
					</div>

					<div class="field">
						<label>Datum tot</label>

						<div class="ui icon input">
							<?php
							echo Form::text(
								'date_to',
                                Carbon\Carbon::parse($data->date_to)->format('d-m-Y'),
//								date('d M,Y',strtotime($data->date_to)),
								array(
									'class' => 'datepicker',
									'placeholder' => 'Selecteer een datum'
									)
								);
								?>
								<i class="calendar icon"></i>
							</div>
						</div>
					</div>

					<div class="two fields">
						<div class="field">
							<label>Tijd van</label>

							<div class="ui icon input">
								<?php
								echo Form::text(
									'time_from',
									date('H:i',strtotime($data->time_from)),
									array(
										'class' => 'timepicker',
										'placeholder' => 'Selecteer een tijd'
										)
									);
									?>
									<i class="clock icon"></i>
								</div>
							</div>

							<div class="field">
								<label>Tijd tot</label>

								<div class="ui icon input">
									<?php
									echo Form::text(
										'time_to',
										date('H:i',strtotime($data->time_to)),
										array(
											'class' => 'timepicker',
											'placeholder' => 'Selecteer een tijd'
											)
										);
										?>
										<i class="clock icon"></i>
									</div>
								</div>
							</div>

							<div class="two fields">
								<div class="field">
									<label>Aantal beschikbaar</label>
									<?php echo Form::number('total_amount', $data->total_amount, array('min' => 1)); ?>
								</div>

								<div class="field">
									<label>Prijs van</label>
									<?php echo Form::text('price_from',$data->price_from); ?>
								</div>

								<div class="field">
									<label>Dealprijs</label>
									<?php echo Form::text('price',$data->price); ?>
								</div>
								<div class="field">
									<label>Prijs per persoon</label>
									<?php echo Form::text('price_per_guest',$data->price_per_guest); ?>
								</div>
							</div>

							<div class="field">
								<label>Korte omschrijving</label>
								<?php echo Form::textarea('content', $data->description, ['class' => 'editor']); ?>
							</div>

							<div class="field">
								<label>Uitgebreide omschrijving</label>
								<?php echo Form::textarea('short_content', $data->short_description, ['class' => 'editor']); ?>
							</div>

			<div class="field">
				Uw ip-adres <strong>{{ $data->ip_address }}</strong>
			</div>


			@if ($data->signature_url == NULL)
				<h4>Vul hieronder uw handtekening in:</h4>
				<div id="signature"></div>

				<button id="resetSignature" class="ui small button disabled">Verwijder handtekening</button>
				<br/><br/>
			@else
				<h4>Uw handtekening</h4>
				<img src="data:{{ $data->signature_url }}"/><br/>
			@endif


							<button class="ui button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
						</div>
					</div>
					<?php echo Form::close(); ?>
				</div>
				<div class="clear"></div>
				@stop


