<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Sentinel;

class CompanyReservation extends Model {

    protected $table = 'company_reservations';
    public static $per_time = array(
        15 => '15 minutes',
        30 => '30 minutes',
        60 => '1 hour'
    );
    
    public function getTimeCarouselHTML($reservationDate = NULL, $data, $persons, $reservationTimesArray, $tomorrowArray, $hasDate, $deal) {
        $allTimesArray = static::getAllTimes();

        // Today
        foreach ($allTimesArray as $time) {
            if (isset($reservationTimesArray[$time][$data->id])) {
                $availableTimes[$data->id][$time] = 1;
            }
        }

        // Tomorrow
        foreach ($allTimesArray as $time) {
            if (!isset($availableTimes[$data->id])) {
                if (isset($tomorrowArray[$time][$data->id])) {
                    $availableTimes[$data->id][$time] = 1;
                    $tomorrow[$data->id][$time] = 1;
                }
            }
        }

        if (isset($tomorrow[$data->id])) {
            $dateReservation = (isset($reservationDate) ? date('Ymd', strtotime($reservationDate . ' +1 days')) : date('Ymd', strtotime('+1 days')));
        } else {
            $dateReservation = (isset($reservationDate) ? date('Ymd', strtotime($reservationDate)) : date('Ymd'));
        }

        if (trim($hasDate) != '' && $hasDate != date('Y-m-d') && $hasDate != date('Y-m-d', strtotime('+1 day'))) {
            $timeCarousel = '<b>Op ' . date('d-m-Y', strtotime($hasDate)) . ' beschikbaar voor ' . $persons . ' personen</b>';
        } else {
            $timeCarousel = '<b>' . ($dateReservation == date('Ymd') ? 'Vandaag' : 'Morgen') . ' beschikbaar voor ' . $persons . ' personen </b>';
        }

        $timeCarousel .= '<div class="calendar">
						  <div class="owl-wrapper">
                            <div class="customNavigation">
                                <a class="prev"><img src="' . asset('images/prev2.png') . '" alt="prev2"></a>
                            </div>

                            <div class="owl-carousel-container">
                                <div id="owl-example-' . $data->id . '" class="owl-carousel owl-theme">';

        $i = 0;

        foreach ($allTimesArray as $time) {
            if (
                    isset($reservationTimesArray[$time][$data->id])
                    OR isset($tomorrow[$data->id]) && isset($tomorrowArray[$time][$data->id])
            ) {
                $i++;

                // Available
                $reservationUrl = url('restaurant/reservation/' . $data->slug . '?date=' . $dateReservation . '&time=' . date('Hi', strtotime($time)) . '&persons=' . $persons . '&deal=' . @$deal);

                $timeCarousel .= '<div class="available-' . $i . ' time-available" data-time="' . date('Hi', strtotime($time)) . '">
                                    <a href="' . $reservationUrl . '" data-redirect="' . $reservationUrl . '" data-type-redirect="1" class="ui fluid blueseal mini ' . (Sentinel::check() == FALSE ? 'login' : '') . ' button guestClick">
                                        ' . $time . '
                                    </a>
                                  </div>';
            } else {
                // Unavailable
                $timeCarousel .= '<div class="unavailable" data-time="' . date('Hi', strtotime($time)) . '"><span class="ui fluid mini disabled button">' . $time . '</span></div>';
            }
        }

        $timeCarousel .= '</div>
                            </div>
                                <div class="customNavigation">
                                    <a class="next"><img src="' . asset('images/next2.png') . '" alt="next2"></a>
                                </div>
                            </div>
						</div>';
		
		if (isset($availableTimes[$data->id])) {
            return $timeCarousel;
        } else {
            return '<div class="ui tiny text-danger"> <i class="clock icon"></i> Helaas, er zijn momenteel geen plaatsen beschikbaar.</div>';
        }
    }

    public function getTimeCarousel($reservationDate = NULL, $data, $persons, $reservationTimesArray, $tomorrowArray, $hasDate, $deal) {
        $allTimesArray = static::getAllTimes();

        // Today
        foreach ($allTimesArray as $time) {
            if (isset($reservationTimesArray[$time][$data->id])) {
                $availableTimes[$data->id][$time] = 1;
            }
        }

        // Tomorrow
        foreach ($allTimesArray as $time) {
            if (!isset($availableTimes[$data->id])) {
                if (isset($tomorrowArray[$time][$data->id])) {
                    $availableTimes[$data->id][$time] = 1;
                    $tomorrow[$data->id][$time] = 1;
                }
            }
        }

        if (isset($tomorrow[$data->id])) {
            $dateReservation = (isset($reservationDate) ? date('Ymd', strtotime($reservationDate . ' +1 days')) : date('Ymd', strtotime('+1 days')));
        } else {
            $dateReservation = (isset($reservationDate) ? date('Ymd', strtotime($reservationDate)) : date('Ymd'));
        }

        if (trim($hasDate) != '' && $hasDate != date('Y-m-d') && $hasDate != date('Y-m-d', strtotime('+1 day'))) {
            $timeCarousel = '<div class="ui header grey small">Op ' . date('d-m-Y', strtotime($hasDate)) . ' beschikbaar voor ' . $persons . ' personen</div>';
        } else {
            $timeCarousel = '<div class="ui header grey small">' . ($dateReservation == date('Ymd') ? 'Vandaag' : 'Morgen') . ' beschikbaar voor ' . $persons . ' personen </div>';
        }

        $timeCarousel .= '<div class="owl-wrapper">
                            <div class="customNavigation">
                                <a class="btn prev"><i class="chevron left icon"></i></a>
                            </div>

                            <div class="owl-carousel-container">
                                <div id="owl-example-' . $data->id . '" class="owl-carousel">';

        $i = 0;

        foreach ($allTimesArray as $time) {
            if (
                    isset($reservationTimesArray[$time][$data->id])
                    OR isset($tomorrow[$data->id]) && isset($tomorrowArray[$time][$data->id])
            ) {
                $i++;

                // Available
                $reservationUrl = url('restaurant/reservation/' . $data->slug . '?date=' . $dateReservation . '&time=' . date('Hi', strtotime($time)) . '&persons=' . $persons . '&deal=' . @$deal);

                $timeCarousel .= '<div class="available-' . $i . ' time-available" data-time="' . date('Hi', strtotime($time)) . '">
                                    <a href="' . $reservationUrl . '" data-redirect="' . $reservationUrl . '" data-type-redirect="1" class="ui fluid blue mini ' . (Sentinel::check() == FALSE ? 'login' : '') . ' button guestClick">
                                        ' . $time . '
                                    </a>
                                  </div>';
            } else {
                // Unavailable
                $timeCarousel .= '<div class="unavailable" data-time="' . date('Hi', strtotime($time)) . '"><span class="ui fluid mini disabled button">' . $time . '</span></div>';
            }
        }

        $timeCarousel .= '</div>
                            </div>
                                <div class="customNavigation">
                                    <a class="btn next"><i class="chevron right icon"></i></a>
                                </div>
                            </div>';

        
        return $timeCarousel;
        
    }

    public function getLastReservationId($companyId) {
        $lastReservation = static::select(
                        'date'
                )
                ->orderBy('date', 'desc')
                ->where('company_id', $companyId)
                ->where('date', '>=', date('Y-m-d'))
                ->limit(1)
                ->first()
        ;

        return count($lastReservation) >= 1 ? date('Ymd', strtotime($lastReservation->date)) : '';
    }

    public static function getAllTimes() {
        $startTime = strtotime(date('Y-m-d') . ' 08:00');
        $endTime = strtotime(date('Y-m-d') . ' 23:45');
        $timeResult[] = date('H:i', $startTime);

        while ($startTime < $endTime) {
            $startTime = strtotime('+15 minutes', $startTime);

            if ($endTime >= $startTime) {
                $timeResult[] = date('H:i', $startTime);
            }
        }

        return array_unique($timeResult);
    }

    public static function getAllDates($company, $year = null, $month = null, $jsDate = null, $persons = null) {
        $reservation = CompanyReservation::select(
                        'company_reservations.*', 'company_reservations.date as reservation_date', DB::raw('SUM(reservations.persons) as persons')
                )
                ->leftJoin('reservations', function ($join) {
                    $join
                    ->on('company_reservations.company_id', '=', 'reservations.company_id')
                    ->on('company_reservations.date', '=', 'reservations.date')
                    ;
                })
                ->where('company_reservations.company_id', $company)
                ->where('is_locked', 0)
        ;

        if ($year != null) {
            $reservation = $reservation->whereYear('company_reservations.date', '=', $year);
        }

        if ($month != null) {
            $reservation = $reservation->whereMonth('company_reservations.date', '=', $month);
        }

        $reservation = $reservation->groupBy(
                        'company_reservations.date', 'company_reservations.start_time', 'company_reservations.end_time'
                )
                ->get()
        ;

        $datesArray = array();
        $timeResult = array();
        $total = array();
        $totalCount = array();

        foreach ($reservation as $data) {
            $datesArray[] = array(
                $data->reservation_date => array(
                    'start_time' => $data->start_time,
                    'end_time' => $data->end_time,
                    'interval' => $data->per_time,
                    'available_persons' => $data->available_persons,
                    'persons' => $data->persons,
                    'date' => $data->reservation_date
                )
            );
        }

        foreach ($datesArray as $key => $datesFetch) {
            foreach ($datesFetch as $dateFetch) {
                $startTime = strtotime($dateFetch['start_time']);
                $endTime = strtotime($dateFetch['end_time']);

                $timeResult[] = array(
                    'available_persons' => $dateFetch['available_persons'],
                    'persons' => $dateFetch['persons'],
                    'date' => $dateFetch['date']
                );
            }
        }

        foreach ($timeResult as $date => $timeOutput) {
            // Set availablePersons in an array
            foreach (json_decode($timeOutput['available_persons']) as $timestamp => $availablePersons) {
                $totalCount[$timeOutput['date']][$timestamp] = (int) $availablePersons;
            }

            $total[$timeOutput['date']] = array_sum($totalCount[$timeOutput['date']]) - $timeOutput['persons'];
        }

        foreach ($datesArray as $key => $datesFetch) {
            foreach ($datesFetch as $datePrint => $dateFetch) {
                $carbonDate = Carbon::create(
                                date('Y', strtotime($datePrint)), date('m', strtotime($datePrint)), date('d', strtotime($datePrint))
                );

                if (!$carbonDate->isPast() && $total[$datePrint] >= 1 && $total[$datePrint] >= $persons) {
                    $date = $jsDate == 1 ? date('Y', strtotime($datePrint)) . '-' . date('m', strtotime($datePrint)) . '-' . date('d', strtotime($datePrint)) : $datePrint;

                    $endResult[] = array(
                        'date' => $date,
                        'availablePersons' => array_values(json_decode($dateFetch['available_persons'], true))[0]
                    );
                }
            }
        }

        return isset($endResult) ? $endResult : array();
    }

    public static function getReservationTimesArray($options) {
        $reservedQuery = Reservation::select(
                        DB::raw('sum(persons) as persons'), 'company_id', 'time'
                )
                ->where('date', $options['date'])
                ->whereIn('company_id', $options['company_id'])
                ->where('is_cancelled', 0)
                ->whereIn('status', array('reserved', 'reserved-pending', 'reserved-present', 'present', 'iframe', 'iframe-pending', 'iframe-reserved', 'iframe-present'))
                ->groupBy('date', 'company_id', 'time')
                ->get();

        foreach ($reservedQuery as $reservedFetch) {
            $time = date('H:i', strtotime($reservedFetch->time));
            $reservation[$reservedFetch->company_id][$time] = $reservedFetch->persons;
        }

        $data = CompanyReservation::select(
                        'company_reservations.company_id as companyId', 'company_reservations.date', 'company_reservations.is_manual', 'company_reservations.per_time', 'company_reservations.start_time', 'company_reservations.end_time', 'company_reservations.locked_times', 'company_reservations.available_persons', 'company_reservations.id as company_reservation_id', 'company_reservations.max_persons', 'company_reservations.extra_reservations', 'company_reservations.closed_before_time'
                )
                ->where('company_reservations.date', $options['date'])
                ->whereIn('company_reservations.company_id', $options['company_id'])
                ->where('company_reservations.is_locked', 0)
                ->get();

        $datesArray = array();
        $timeArray = array();
        $timeResult = array();
        $availablePersonsArray = array();

        foreach ($data as $result) {
            $datesArray[$result->company_reservation_id] = array(
                $result->date => array(
                    'startTime' => $result->start_time,
                    'endTime' => $result->end_time,
                    'intervalTime' => $result->per_time,
                    'availablePersons' => $result->available_persons,
                    'maxPersons' => $result->max_persons,
                    'companyId' => $result->companyId,
                    'lockedTimes' => $result->locked_times,
                    'closedBeforeTime' => $result->closed_before_time,
                    'reservationId' => $result->company_reservation_id,
                    'extraReservations' => $result->extra_reservations,
                    'isManual' => $result->is_manual
                )
            );
        }

        foreach ($datesArray as $reservationId => $datesFetch) {
            foreach ($datesFetch as $dateFetch) {
                $dataAvailablePersons = json_decode($dateFetch['availablePersons']);

                foreach ($dataAvailablePersons as $key => $persons) {
                    $availablePersonsArray[$key][$dateFetch['companyId']] = $persons;
                }

                $startTime = strtotime($dateFetch['startTime']);
                $endTime = strtotime($dateFetch['endTime']);
                $convertedTime = date('H:i', $startTime);

                $availablePersonsTime = (isset($availablePersonsArray[$convertedTime][$dateFetch['companyId']]) ? $availablePersonsArray[$convertedTime][$dateFetch['companyId']] : '');
                $personsTime = (isset($reservation[$dateFetch['companyId']][$convertedTime]) ? $reservation[$dateFetch['companyId']][$convertedTime] : 0);

                if (isset($options['groupReservations'])) {
                    $isManual = 1;
                } elseif ($dateFetch['extraReservations'] == 1 && $personsTime == $availablePersonsTime) {
                    $isManual = 1;
                } else {
                    $isManual = $dateFetch['isManual'];
                }

                $timeResult[$convertedTime][$dateFetch['companyId']] = array(
                    'availablePersons' => $availablePersonsTime,
                    'persons' => $personsTime,
                    'maxPersons' => $dateFetch['maxPersons'],
                    'reservationId' => $dateFetch['reservationId'],
                    'closedBeforeTime' => $dateFetch['closedBeforeTime'],
                    'extraReservations' => $dateFetch['extraReservations'],
                    'isManual' => $isManual
                );

                while ($startTime < $endTime) {
                    $startTime = strtotime('+' . self::$per_time[$dateFetch['intervalTime']], $startTime);

                    if ($endTime >= $startTime) {
                        $convertedTime = date('H:i', $startTime);

                        $availablePersonsTime = (isset($availablePersonsArray[$convertedTime][$dateFetch['companyId']]) ? $availablePersonsArray[$convertedTime][$dateFetch['companyId']] : '');
                        $personsTime = (isset($reservation[$dateFetch['companyId']][$convertedTime]) ? $reservation[$dateFetch['companyId']][$convertedTime] : 0);

                        if (isset($options['groupReservations'])) {
                            $isManual = 1;
                        } elseif ($dateFetch['extraReservations'] == 1 && $personsTime == $availablePersonsTime) {
                            $isManual = 1;
                        } else {
                            $isManual = $dateFetch['isManual'];
                        }

                        $timeResult[$convertedTime][$dateFetch['companyId']] = array(
                            'availablePersons' => $availablePersonsTime,
                            'persons' => $personsTime,
                            'maxPersons' => $dateFetch['maxPersons'],
                            'reservationId' => $dateFetch['reservationId'],
                            'closedBeforeTime' => $dateFetch['closedBeforeTime'],
                            'extraReservations' => $dateFetch['extraReservations'],
                            'isManual' => $isManual
                        );
                    }
                }

                // Remove a time when it's locked
                if (!isset($options['groupReservations'])) {
                    if (count($dateFetch['lockedTimes']) >= 1 && trim($dateFetch['lockedTimes']) != '') {
                        foreach (json_decode($dateFetch['lockedTimes']) as $lockedTimes) {
                            if (isset($timeResult[$lockedTimes][$dateFetch['companyId']])) {
                                unset($timeResult[$lockedTimes]);
                            }
                        }
                    }
                }
            }
        }

        foreach ($timeResult as $timeKey => $times) {
            foreach ($times as $companyId => $time) {

                if (isset($timeResult[$timeKey][$companyId])) {

                    $carbonDates = Carbon::create(
                                    date('Y', strtotime($options['date'])), date('m', strtotime($options['date'])), date('d', strtotime($options['date'])), date('H', strtotime($timeKey)), date('i', strtotime($timeKey)), 0
                    );

                    $closedDates = Carbon::create(
                                    date('Y', strtotime($options['date'])), date('m', strtotime($options['date'])), date('d', strtotime($options['date'])), date('H', strtotime($timeKey)), date('i', strtotime($timeKey)), 0
                    );

                    $timeNumber = explode(':', $timeKey);

                    $timeNumberArray[$timeNumber[0]][] = $timeResult[$timeKey][$companyId]['persons'];

                    $closedBeforeTime = $timeResult[$timeKey][$companyId]['closedBeforeTime'];
                    $reservationsAvailable = $timeResult[$timeKey][$companyId]['availablePersons'];
                    $reservationsReserved = $timeResult[$timeKey][$companyId]['persons'];

                    if (!isset($options['groupReservations'])) {
                        if ($closedDates->subMinutes($closedBeforeTime)->isPast()) {
                            unset($timeResult[$timeKey][$companyId]);
                        }
                    }

                    if ($carbonDates->isPast()) {
                        unset($timeResult[$timeKey]);
                    }

                    // There is no limited amount of persons when the Extra Reservations option is selected
                    if (
                            !isset($options['groupReservations']) && isset($timeResult[$timeKey][$companyId]['extraReservations']) && $timeResult[$timeKey][$companyId]['extraReservations'] == 0
                    ) {
                        if ($reservationsAvailable <= $reservationsReserved) {
                            unset($timeResult[$timeKey][$companyId]);
                        }

                        if (trim($options['selectPersons']) != null) {
                            $availablePlaces = $reservationsAvailable - $reservationsReserved;

                            if ($options['selectPersons'] > $availablePlaces) {
                                unset($timeResult[$timeKey][$companyId]);
                            }
                        }
                    }
                }
            }
        }
        foreach ($timeResult as $timeKey => $times) {
            foreach ($times as $companyId => $time) {
                $timeNumber = explode(':', $timeKey);

                if (isset($timeNumberArray[$timeNumber[0]])) {
                    if (isset($timeResult[$timeKey][$companyId]) && $timeResult[$timeKey][$companyId]['maxPersons'] >= 1) {
                        if ($timeResult[$timeKey][$companyId]['maxPersons'] - array_sum($timeNumberArray[$timeNumber[0]]) == 0) {
                            unset($timeResult[$timeKey]);
                        }
                    }
                }
            }
        }

        # Order by time
        ksort($timeResult);

        return array_filter($timeResult);
    }



    public static function getReservationDealsArray($options) {
        $reservedQuery = Reservation::select(
                        DB::raw('sum(persons) as persons'), 'company_id', 'time'
                )
                ->where('date', $options['date'])
                ->whereIn('company_id', $options['company_id'])
                ->where('is_cancelled', 0)
                ->whereIn('status', array('reserved', 'reserved-pending', 'reserved-present', 'present', 'iframe', 'iframe-pending', 'iframe-reserved', 'iframe-present'))
                ->groupBy('date', 'company_id', 'time')
                ->get();

        foreach ($reservedQuery as $reservedFetch) {
            $time = date('H:i', strtotime($reservedFetch->time));
            $reservation[$reservedFetch->company_id][$time] = $reservedFetch->persons;
        }

        $data = CompanyReservation::select(
                        'company_reservations.company_id as companyId', 'company_reservations.date', 'company_reservations.is_manual', 'company_reservations.per_time', 'company_reservations.start_time', 'company_reservations.end_time', 'company_reservations.locked_times', 'company_reservations.available_deals', 'company_reservations.id as company_reservation_id', 'company_reservations.max_persons', 'company_reservations.extra_reservations', 'company_reservations.closed_before_time'
                )
                ->where('company_reservations.date', $options['date'])
                ->whereIn('company_reservations.company_id', $options['company_id'])
                ->where('company_reservations.is_locked', 0)
                ->get();

        $datesArray = array();
        $timeArray = array();
        $timeResult = array();
        $availablePersonsArray = array();

        foreach ($data as $result) {
            $datesArray[$result->company_reservation_id] = array(
                $result->date => array(
                    'startTime' => $result->start_time,
                    'endTime' => $result->end_time,
                    'intervalTime' => $result->per_time,
                    'availablePersons' => $result->available_deals,
                    'maxPersons' => $result->max_persons,
                    'companyId' => $result->companyId,
                    'lockedTimes' => $result->locked_times,
                    'closedBeforeTime' => $result->closed_before_time,
                    'reservationId' => $result->company_reservation_id,
                    'extraReservations' => $result->extra_reservations,
                    'isManual' => $result->is_manual
                )
            );
        }

        foreach ($datesArray as $reservationId => $datesFetch) {
            foreach ($datesFetch as $dateFetch) {
                $dataAvailablePersons = json_decode($dateFetch['availablePersons']);

                foreach ($dataAvailablePersons as $key => $persons) {
                    $availablePersonsArray[$key][$dateFetch['companyId']] = $persons;
                }

                $startTime = strtotime($dateFetch['startTime']);
                $endTime = strtotime($dateFetch['endTime']);
                $convertedTime = date('H:i', $startTime);

                $availablePersonsTime = (isset($availablePersonsArray[$convertedTime][$dateFetch['companyId']]) ? $availablePersonsArray[$convertedTime][$dateFetch['companyId']] : '');
                $personsTime = (isset($reservation[$dateFetch['companyId']][$convertedTime]) ? $reservation[$dateFetch['companyId']][$convertedTime] : 0);

                if (isset($options['groupReservations'])) {
                    $isManual = 1;
                } elseif ($dateFetch['extraReservations'] == 1 && $personsTime == $availablePersonsTime) {
                    $isManual = 1;
                } else {
                    $isManual = $dateFetch['isManual'];
                }

                $timeResult[$convertedTime][$dateFetch['companyId']] = array(
                    'availablePersons' => $availablePersonsTime,
                    'persons' => $personsTime,
                    'maxPersons' => $dateFetch['maxPersons'],
                    'reservationId' => $dateFetch['reservationId'],
                    'closedBeforeTime' => $dateFetch['closedBeforeTime'],
                    'extraReservations' => $dateFetch['extraReservations'],
                    'isManual' => $isManual
                );

                while ($startTime < $endTime) {
                    $startTime = strtotime('+' . self::$per_time[$dateFetch['intervalTime']], $startTime);

                    if ($endTime >= $startTime) {
                        $convertedTime = date('H:i', $startTime);

                        $availablePersonsTime = (isset($availablePersonsArray[$convertedTime][$dateFetch['companyId']]) ? $availablePersonsArray[$convertedTime][$dateFetch['companyId']] : '');
                        $personsTime = (isset($reservation[$dateFetch['companyId']][$convertedTime]) ? $reservation[$dateFetch['companyId']][$convertedTime] : 0);

                        if (isset($options['groupReservations'])) {
                            $isManual = 1;
                        } elseif ($dateFetch['extraReservations'] == 1 && $personsTime == $availablePersonsTime) {
                            $isManual = 1;
                        } else {
                            $isManual = $dateFetch['isManual'];
                        }

                        $timeResult[$convertedTime][$dateFetch['companyId']] = array(
                            'availablePersons' => $availablePersonsTime,
                            'persons' => $personsTime,
                            'maxPersons' => $dateFetch['maxPersons'],
                            'reservationId' => $dateFetch['reservationId'],
                            'closedBeforeTime' => $dateFetch['closedBeforeTime'],
                            'extraReservations' => $dateFetch['extraReservations'],
                            'isManual' => $isManual
                        );
                    }
                }

                // Remove a time when it's locked
                if (!isset($options['groupReservations'])) {
                    if (count($dateFetch['lockedTimes']) >= 1 && trim($dateFetch['lockedTimes']) != '') {
                        foreach (json_decode($dateFetch['lockedTimes']) as $lockedTimes) {
                            if (isset($timeResult[$lockedTimes][$dateFetch['companyId']])) {
                                unset($timeResult[$lockedTimes]);
                            }
                        }
                    }
                }
            }
        }

        foreach ($timeResult as $timeKey => $times) {
            foreach ($times as $companyId => $time) {

                if (isset($timeResult[$timeKey][$companyId])) {

                    $carbonDates = Carbon::create(
                                    date('Y', strtotime($options['date'])), date('m', strtotime($options['date'])), date('d', strtotime($options['date'])), date('H', strtotime($timeKey)), date('i', strtotime($timeKey)), 0
                    );

                    $closedDates = Carbon::create(
                                    date('Y', strtotime($options['date'])), date('m', strtotime($options['date'])), date('d', strtotime($options['date'])), date('H', strtotime($timeKey)), date('i', strtotime($timeKey)), 0
                    );

                    $timeNumber = explode(':', $timeKey);

                    $timeNumberArray[$timeNumber[0]][] = $timeResult[$timeKey][$companyId]['persons'];

                    $closedBeforeTime = $timeResult[$timeKey][$companyId]['closedBeforeTime'];
                    $reservationsAvailable = $timeResult[$timeKey][$companyId]['availablePersons'];
                    $reservationsReserved = $timeResult[$timeKey][$companyId]['persons'];

                    if (!isset($options['groupReservations'])) {
                        if ($closedDates->subMinutes($closedBeforeTime)->isPast()) {
                            unset($timeResult[$timeKey][$companyId]);
                        }
                    }

                    if ($carbonDates->isPast()) {
                        unset($timeResult[$timeKey]);
                    }

                    // There is no limited amount of persons when the Extra Reservations option is selected
                    if (
                            !isset($options['groupReservations']) && isset($timeResult[$timeKey][$companyId]['extraReservations']) && $timeResult[$timeKey][$companyId]['extraReservations'] == 0
                    ) {
                        if ($reservationsAvailable <= $reservationsReserved) {
                            unset($timeResult[$timeKey][$companyId]);
                        }

                        if (trim($options['selectPersons']) != null) {
                            $availablePlaces = $reservationsAvailable - $reservationsReserved;

                            if ($options['selectPersons'] > $availablePlaces) {
                                unset($timeResult[$timeKey][$companyId]);
                            }
                        }
                    }
                }
            }
        }
        foreach ($timeResult as $timeKey => $times) {
            foreach ($times as $companyId => $time) {
                $timeNumber = explode(':', $timeKey);

                if (isset($timeNumberArray[$timeNumber[0]])) {
                    if (isset($timeResult[$timeKey][$companyId]) && $timeResult[$timeKey][$companyId]['maxPersons'] >= 1) {
                        if ($timeResult[$timeKey][$companyId]['maxPersons'] - array_sum($timeNumberArray[$timeNumber[0]]) == 0) {
                            unset($timeResult[$timeKey]);
                        }
                    }
                }
            }
        }

        # Order by time
        ksort($timeResult);

        return array_filter($timeResult);
    }

    public static function getReservationsCompaniesArray($companies) {
        $reservedQuery = Reservation::select(
                        DB::raw('sum(persons) as persons'), 'date', 'company_id', 'time'
                )
                ->whereIn('company_id', $companies)
                ->where('is_cancelled', 0)
                ->whereIn('status', array('reserved', 'reserved-pending', 'reserved-present', 'present', 'iframe', 'iframe-pending', 'iframe-reserved', 'iframe-present'))
                ->groupBy('date', 'company_id', 'time')
                ->get()
        ;

        foreach ($reservedQuery as $reservedFetch) {
            $time = date('H:i', strtotime($reservedFetch->time));
            $reservation[$reservedFetch->company_id][$reservedFetch->date][$time] = $reservedFetch->persons;
        }

        $companyReservations = CompanyReservation::select(
                        'company_reservations.company_id as companyId', 'company_reservations.date', 'company_reservations.is_manual', 'company_reservations.per_time', 'company_reservations.start_time', 'company_reservations.end_time', 'company_reservations.locked_times', 'company_reservations.available_persons', 'company_reservations.id as company_reservation_id', 'company_reservations.max_persons', 'company_reservations.closed_before_time'
                )
                ->whereIn('company_reservations.company_id', $companies)
                ->where('company_reservations.is_locked', 0)
                ->get()
        ;

        foreach ($companyReservations as $companyReservation) {
            $datesArray[$companyReservation->company_reservation_id] = array(
                $companyReservation->date => array(
                    'startTime' => $companyReservation->start_time,
                    'endTime' => $companyReservation->end_time,
                    'intervalTime' => $companyReservation->per_time,
                    'availablePersons' => $companyReservation->available_persons,
                    'maxPersons' => $companyReservation->max_persons,
                    'companyId' => $companyReservation->companyId,
                    'lockedTimes' => $companyReservation->locked_times,
                    'closedBeforeTime' => $companyReservation->closed_before_time,
                    'reservationId' => $companyReservation->company_reservation_id,
                    'isManual' => $companyReservation->is_manual
                )
            );
        }

        if (isset($datesArray)) {
            foreach ($datesArray as $reservationId => $datesFetch) {
                foreach ($datesFetch as $dateKey => $dateFetch) {
                    $dataAvailablePersons = json_decode($dateFetch['availablePersons']);

                    foreach ($dataAvailablePersons as $key => $persons) {
                        $availablePersonsArray[$key][$dateFetch['companyId']] = $persons;
                    }

                    $startTime = strtotime($dateFetch['startTime']);
                    $endTime = strtotime($dateFetch['endTime']);
                    $convertedTime = date('H:i', $startTime);
                    $timeResult[$dateFetch['companyId']][$dateKey][$convertedTime] = array(
                        'availablePersons' => (isset($availablePersonsArray[$convertedTime][$dateFetch['companyId']]) ? (int) $availablePersonsArray[$convertedTime][$dateFetch['companyId']] : ''),
                        'persons' => (isset($reservation[$dateFetch['companyId']][$dateKey][$convertedTime]) ? $reservation[$dateFetch['companyId']][$dateKey][$convertedTime] : 0),
                        'maxPersons' => $dateFetch['maxPersons'],
                        'reservationId' => $dateFetch['reservationId'],
                        'closedBeforeTime' => $dateFetch['closedBeforeTime'],
                        'isManual' => $dateFetch['isManual']
                    );

                    while ($startTime < $endTime) {
                        $startTime = strtotime('+' . self::$per_time[$dateFetch['intervalTime']], $startTime);

                        if ($endTime >= $startTime) {
                            $convertedTime = date('H:i', $startTime);
                            $timeResult[$dateFetch['companyId']][$dateKey][$convertedTime] = array(
                                'availablePersons' => (isset($availablePersonsArray[$convertedTime][$dateFetch['companyId']]) ? (int) $availablePersonsArray[$convertedTime][$dateFetch['companyId']] : ''),
                                'persons' => (isset($reservation[$dateFetch['companyId']][$dateKey][$convertedTime]) ? $reservation[$dateFetch['companyId']][$dateKey][$convertedTime] : 0),
                                'maxPersons' => $dateFetch['maxPersons'],
                                'reservationId' => $dateFetch['reservationId'],
                                'closedBeforeTime' => $dateFetch['closedBeforeTime'],
                                'isManual' => $dateFetch['isManual']
                            );
                        }
                    }

                    if (count($dateFetch['lockedTimes']) >= 1 && trim($dateFetch['lockedTimes']) != '') {
                        foreach (json_decode($dateFetch['lockedTimes']) as $lockedTime) {
                            if (isset($timeResult[$dateFetch['companyId']][$dateKey][$lockedTime])) {
                                unset($timeResult[$dateFetch['companyId']][$dateKey][$lockedTime]);
                            }
                        }
                    }
                }
            }
        }

        if (isset($timeResult)) {
            // All companies
            foreach ($timeResult as $companyKey => $dates) {

                // All reservations date of a company
                foreach ($dates as $dateKey => $times) {

                    // Times of the reservation date
                    foreach ($times as $timeKey => $time) {
                        if (isset($timeResult[$companyKey][$dateKey][$timeKey])) {
                            $carbonDates = Carbon::create(
                                            date('Y', strtotime($dateKey)), date('m', strtotime($dateKey)), date('d', strtotime($dateKey)), date('H', strtotime($timeKey)), date('i', strtotime($timeKey)), 0
                            );

                            $timeNumber = explode(':', $timeKey);
                            $timeNumberArray[$timeNumber[0]][] = $timeResult[$companyKey][$dateKey][$timeKey]['persons'];

                            $closedBeforeTime = $timeResult[$companyKey][$dateKey][$timeKey]['closedBeforeTime'];
                            $reservationsAvailable = $timeResult[$companyKey][$dateKey][$timeKey]['availablePersons'];
                            $reservationsReserved = $timeResult[$companyKey][$dateKey][$timeKey]['persons'];

                            if ($reservationsAvailable <= $reservationsReserved) {
                                unset($timeResult[$companyKey][$dateKey][$timeKey]);
                            }
                            /*
                              if (trim($selectPersons) != null) {
                              $availablePlaces = $reservationsAvailable - $reservationsReserved;
                              if ($selectPersons > $availablePlaces) {
                              unset($resultKey);
                              }
                              }
                             */
                        }
                    }
                }
            }

            // All companies
            foreach ($timeResult as $companyKey => $dates) {

                // All reservations date of a company
                foreach ($dates as $dateKey => $times) {

                    // Times of the reservation date
                    foreach ($times as $timeKey => $time) {
                        if (isset($timeResult[$companyKey][$dateKey][$timeKey])) {
                            $timeNumber = explode(':', $timeKey);

                            if (isset($timeNumberArray[$timeNumber[0]])) {
                                if (isset($timeResult[$companyKey][$dateKey][$timeKey]) && $timeResult[$companyKey][$dateKey][$timeKey]['maxPersons'] >= 1) {
                                    if ($timeResult[$companyKey][$dateKey][$timeKey]['maxPersons'] - array_sum($timeNumberArray[$timeNumber[0]]) == 0) {
                                        unset($$timeResult[$companyKey][$dateKey][$timeKey]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $timeResult;
        }
    }

    public static function getReservationsDatesTimes($companies, $year = null, $month) {
        $reservedQuery = Reservation::select(
                        DB::raw('sum(persons) as persons'), 'date', 'company_id', 'time'
                )
                ->whereIn('company_id', $companies)
                ->where('is_cancelled', 0)
                ->whereIn('status', array('reserved', 'reserved-pending', 'reserved-present', 'present', 'iframe', 'iframe-pending', 'iframe-reserved', 'iframe-present'))
                ->groupBy('date', 'company_id', 'time')
                ->get()
        ;

        foreach ($reservedQuery as $reservedFetch) {
            $time = date('H:i', strtotime($reservedFetch->time));
            $reservation[$reservedFetch->company_id][$reservedFetch->date][$time] = $reservedFetch->persons;
        }

        $companyReservations = CompanyReservation::select(
                        'company_reservations.company_id as companyId', 'company_reservations.date', 'company_reservations.is_manual', 'company_reservations.per_time', 'company_reservations.start_time', 'company_reservations.end_time', 'company_reservations.locked_times', 'company_reservations.available_persons', 'company_reservations.id as company_reservation_id', 'company_reservations.max_persons', 'company_reservations.closed_before_time'
                )
                ->whereIn('company_reservations.company_id', $companies)
                ->where('company_reservations.is_locked', 0)
        ;

        if ($year != null) {
            $companyReservations = $companyReservations->whereYear('company_reservations.date', '=', $year);
        }

        if ($month != null) {
            $companyReservations = $companyReservations->whereMonth('company_reservations.date', '=', $month);
        }

        $companyReservations = $companyReservations->get();

        foreach ($companyReservations as $companyReservation) {
            $datesArray[$companyReservation->company_reservation_id] = array(
                $companyReservation->date => array(
                    'startTime' => $companyReservation->start_time,
                    'endTime' => $companyReservation->end_time,
                    'intervalTime' => $companyReservation->per_time,
                    'availablePersons' => $companyReservation->available_persons,
                    'maxPersons' => $companyReservation->max_persons,
                    'companyId' => $companyReservation->companyId,
                    'lockedTimes' => $companyReservation->locked_times,
                    'closedBeforeTime' => $companyReservation->closed_before_time,
                    'reservationId' => $companyReservation->company_reservation_id,
                    'isManual' => $companyReservation->is_manual
                )
            );
        }

        if (isset($datesArray)) {
            foreach ($datesArray as $reservationId => $datesFetch) {
                foreach ($datesFetch as $dateKey => $dateFetch) {
                    $dataAvailablePersons = json_decode($dateFetch['availablePersons']);

                    foreach ($dataAvailablePersons as $key => $persons) {
                        $availablePersonsArray[$key][$dateFetch['companyId']] = $persons;
                    }

                    $startTime = strtotime($dateFetch['startTime']);
                    $endTime = strtotime($dateFetch['endTime']);
                    $convertedTime = date('H:i', $startTime);
                    $timeResult[$dateFetch['companyId']][$dateKey][$convertedTime] = array(
                        'availablePersons' => (isset($availablePersonsArray[$convertedTime][$dateFetch['companyId']]) ? (int) $availablePersonsArray[$convertedTime][$dateFetch['companyId']] : ''),
                        'persons' => (isset($reservation[$dateFetch['companyId']][$dateKey][$convertedTime]) ? $reservation[$dateFetch['companyId']][$dateKey][$convertedTime] : 0),
                        'maxPersons' => $dateFetch['maxPersons'],
                        'reservationId' => $dateFetch['reservationId'],
                        'closedBeforeTime' => $dateFetch['closedBeforeTime'],
                        'isManual' => $dateFetch['isManual']
                    );

                    while ($startTime < $endTime) {
                        $startTime = strtotime('+' . self::$per_time[$dateFetch['intervalTime']], $startTime);

                        if ($endTime >= $startTime) {
                            $convertedTime = date('H:i', $startTime);
                            $timeResult[$dateFetch['companyId']][$dateKey][$convertedTime] = array(
                                'availablePersons' => (isset($availablePersonsArray[$convertedTime][$dateFetch['companyId']]) ? (int) $availablePersonsArray[$convertedTime][$dateFetch['companyId']] : ''),
                                'persons' => (isset($reservation[$dateFetch['companyId']][$dateKey][$convertedTime]) ? $reservation[$dateFetch['companyId']][$dateKey][$convertedTime] : 0),
                                'maxPersons' => $dateFetch['maxPersons'],
                                'reservationId' => $dateFetch['reservationId'],
                                'closedBeforeTime' => $dateFetch['closedBeforeTime'],
                                'isManual' => $dateFetch['isManual']
                            );
                        }
                    }

                    if (count($dateFetch['lockedTimes']) >= 1 && trim($dateFetch['lockedTimes']) != '') {
                        foreach (json_decode($dateFetch['lockedTimes']) as $lockedTime) {
                            if (isset($timeResult[$dateFetch['companyId']][$dateKey][$lockedTime])) {
                                unset($timeResult[$dateFetch['companyId']][$dateKey][$lockedTime]);
                            }
                        }
                    }
                }
            }
        }

        if (isset($timeResult)) {
            // All companies
            foreach ($timeResult as $companyKey => $dates) {
                // All reservations date of a company
                foreach ($dates as $dateKey => $times) {
                    // Times of the reservation date
                    foreach ($times as $timeKey => $time) {
                        if (isset($timeResult[$companyKey][$dateKey][$timeKey])) {
                            $carbonDates = Carbon::create(
                                            date('Y', strtotime($dateKey)), date('m', strtotime($dateKey)), date('d', strtotime($dateKey)), date('H', strtotime($timeKey)), date('i', strtotime($timeKey)), 0
                            );

                            $timeNumber = explode(':', $timeKey);
                            $timeNumberArray[$timeNumber[0]][] = $timeResult[$companyKey][$dateKey][$timeKey]['persons'];

                            $closedBeforeTime = $timeResult[$companyKey][$dateKey][$timeKey]['closedBeforeTime'];
                            $reservationsAvailable = $timeResult[$companyKey][$dateKey][$timeKey]['availablePersons'];
                            $reservationsReserved = $timeResult[$companyKey][$dateKey][$timeKey]['persons'];

                            // Disable time when there is no place available
                            if ($reservationsAvailable <= $reservationsReserved) {
                                unset($timeResult[$companyKey][$dateKey][$timeKey]);
                            }

                            // Disable time when the date has past
                            if ($carbonDates->isPast()) {
                                unset($timeResult[$companyKey][$dateKey][$timeKey]);
                            }

                            // Disable time before closing
                            if ($carbonDates->subMinutes($closedBeforeTime)->isPast()) {
                                unset($timeResult[$companyKey][$dateKey][$timeKey]);
                            }
                        }
                    }
                }
            }

            // All companies
            foreach ($timeResult as $companyKey => $dates) {
                // All reservations date of a company
                foreach ($dates as $dateKey => $times) {

                    // Times of the reservation date
                    foreach ($times as $timeKey => $time) {
                        if (isset($timeResult[$companyKey][$dateKey][$timeKey])) {
                            $timeNumber = explode(':', $timeKey);

                            if (isset($timeNumberArray[$timeNumber[0]])) {
                                if (isset($timeResult[$companyKey][$dateKey][$timeKey]) && $timeResult[$companyKey][$dateKey][$timeKey]['maxPersons'] >= 1) {
                                    if ($timeResult[$companyKey][$dateKey][$timeKey]['maxPersons'] - array_sum($timeNumberArray[$timeNumber[0]]) == 0) {
                                        unset($$timeResult[$companyKey][$dateKey][$timeKey]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $timeResult;
        }
    }
    static function getCompanyReservation($id){
        return ReservationOption::where('company_id',$id)->get()->first();
    }

    static function getUnwantedWords($word){
        return  DB::table('unwanted_word')->select('word')->where('word',$word)->get();
    }

    static function getWords(){
        return  DB::table('unwanted_word')->get();
    }

}
