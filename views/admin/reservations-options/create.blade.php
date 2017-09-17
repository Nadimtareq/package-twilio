@extends('template.theme')

@section('scripts')
@include('admin.template.editor')

<script type="text/javascript">
	$(document).ready(function() {
		closeBrowser();
	});
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#signature').jSignature();

        $('#signature').bind('change', function (e) {
            $('#signatureInput').val($(this).jSignature('getData', 'image'));
            $('#resetSignature').removeClass('disabled');
        });

        $('#resetSignature').on('click', function (e) {
            $('#signature').jSignature('reset');
        });
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
				<?php echo Form::text('name'); ?>
			</div>

			<div class="two fields">
				<div class="field">
					<label>Image</label>
					{{ Form::file('image',['class'=>'btn btn-file']) }}
				</div>

				@if ($userAdmin)
				<div class="two fields">
					<div class="field">
						<label for="newsletter">{{trans('app.newsletter')}}</label>
						{{Form::select("newsletter", array('' => 'Not selected', '0' => 'niet toevoegen', '1' => 'toevoegen'), null, ['class' => 'ui normal icon search selection fluid dropdown margin-0','required' => 'required'])}}
					</div>
					<div class="field">
						<label for="no_show">Weergeven op site</label>
						{{Form::select("no_show", array('' => 'Not selected', '0' => 'Nee', '1' => 'Ja'), null, ['class' => 'ui normal icon search selection fluid dropdown margin-0','required' => 'required'])}}
					</div>
				</div>
				@endif
			</div>

			@if ($userAdmin)
			<div class="field">
				<label>Bedrijf</label>
				<?php echo Form::select('company_id', $companies, ($slug != NULL ? $company['id'] : NULL), array('class' => 'ui normal search dropdown'));?>
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
							'',
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
								'',
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
									'',
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
										'',
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
									<?php echo Form::number('total_amount', 1, array('min' => 1)); ?>
								</div>

								<div class="field">
									<label>Prijs van</label>
									<?php echo Form::text('price_from'); ?>
								</div>

								<div class="field">
									<label>Dealprijs</label>
									<?php echo Form::text('price'); ?>
								</div>
								<div class="field">
									<label>Prijs per persoon</label>
									<?php echo Form::text('price_per_guest'); ?>
								</div>
							</div>

							<div class="field">
								<label>Korte omschrijving</label>
								<?php echo Form::textarea('content', null, ['class' => 'editor']); ?>
							</div>

							<div class="field">
								<label>Uitgebreide omschrijving</label>
								<?php echo Form::textarea('short_content', null, ['class' => 'editor']); ?>
							</div>

			<div class="field">
				Uw ip-adres <strong>{{ Request::getClientIp() }}</strong>
			</div>

				<h4>Vul hieronder uw handtekening in:</h4>
				<div id="signature"></div>

				<button id="resetSignature" class="ui small button disabled">Verwijder handtekening</button>
				<br/><br/>
            <?php echo Form::hidden('signature', $signature_url, array('id' => 'signatureInput')); ?>


							<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
						</div>
					</div>
					<?php echo Form::close(); ?>
				</div>
				<div class="clear"></div>
				@stop
