<!DOCTYPE html>
<html>
<head>
	<title>Uwvoordeelpas</title>

    <link rel="stylesheet" href="{{ asset('css/app.css?rand='.str_random(40)) }}">

    <style type="text/css">
    	body { background: rgba(0, 0, 0, 0)!important; }
    </style>
</head>
<body>
	<div style="width: 100%;">
		<div class="ui center aligned segment stacked">
			<?php echo Form::open(['url' => 'restaurant/'.$company->slug, 'id' => 'reservationForm', 'class' => 'ui form']); ?>
				<?php echo Form::hidden('date_hidden', date('Y-m-d')); ?>
				<?php echo Form::hidden('date', date('Y-m-d')); ?>
				<?php echo Form::hidden('company_id', $company->id); ?>
				<?php echo Form::hidden('year', date('Y')); ?>
				<?php echo Form::hidden('month', date('m')); ?>
				<?php echo Form::hidden('iframe', 1); ?>
				<?php echo Form::hidden('reservation_url', URL::to('restaurant/reservation/'.$company->slug)); ?>

				<div class="two fields">
					<div class="field">
						<div id="month" class="ui normal compact selection dropdown">
							<input type="hidden" name="monthDate" value="<?php echo date('m') - 1; ?>-<?php echo date('Y'); ?>">

							<div class="default text">Maand</div>
							<i class="dropdown icon"></i>

							<div class="menu">
								<?php
								$st = \Carbon\Carbon::create(date('Y'), 1, 1, 0, 0, 0);
								$dt = \Carbon\Carbon::create(date('Y') + 1, 12, 1, 0, 0, 0);
								$dates = array();

								while ($st->lte($dt)) {
									$dates[] = $st->copy()->format('Y-m');
									$st->addMonth();
								}

								foreach ($dates as $date) {
									if (date('Y-m') <= $date) {
										?>
										<div class="item" data-month="<?php echo date('m', strtotime($date)); ?>" data-year="<?php echo date('Y', strtotime($date)); ?>" data-value="<?php echo date('n', strtotime($date)) - 1; ?>-<?php echo date('Y', strtotime($date)); ?>"><?php echo Config::get('preferences.months.'.date('n', strtotime($date))).' '.date('Y', strtotime($date)) ?></div>
										<?php
									}
								}
								?>
							</div>
						</div>
					</div>

					<div class="field">
						<div id="personsField" class="ui normal compact selection dropdown persons searchReservation calendarInput">
							<input type="hidden" name="persons" value="{{ ($userAuth && $userInfo->kids != 'null' && $userInfo->kids != NULL && $userInfo->kids != '[""]' ? $userInfo->kids : 1) }}">

							<div class="default text">Personen</div>
							<i class="dropdown icon"></i>

							<div class="menu">
								@for ($i = 1; $i <= 10; $i++)
								<div class="item" data-value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'persoon' : 'personen' }}</div>
								@endfor
							</div>
						</div>
					</div>
				</div>

				<div id="calendar"></div>

				<div class="loader">
					<div class="ui basic segment">
						<div class="ui active inverted dimmer">
							<div class="ui text loader">Laden</div>
						</div>
					</div>
				</div><br />

				<div class="two fields">
					<div class="field">
						<div id="timeField" class="ui normal compact selection dropdown time">
							<input type="hidden" name="time" value="{{ count(array_keys($reservationTimesArray)) >= 1 ? array_keys($reservationTimesArray)[0] : '' }}">
							<i class="time icon"></i>

							<div class="default text">Tijd</div>
							<i class="dropdown icon"></i>

							<div class="menu"></div>
						</div>
					</div>
					<div class="field">
						<button id="submitField" class="ui green fluid button">Reserveer nu</button>
					</div>

					<a href="<?php echo URL::to('restaurant/'.$company->slug) ?>" target="_blank">
						<img src="{{ url('images/vplogo.png') }}" width="70%" />
					</a>
				</div>
			<?php echo Form::close(); ?>
		</div>
	</div>

	<script type="text/javascript">
		var activateAjax = 'restaurant';
                var baseUrl = {!! json_encode(url('/')."/") !!};
	</script>
    <script type="text/javascript" src="{{ asset('js/jquery-1.11.3.min.js?rand='.str_random(40)) }}"></script>
    <script type="text/javascript" src="{{ asset('js/app.js?rand='.str_random(40)) }}"></script>
</body>
</html>
