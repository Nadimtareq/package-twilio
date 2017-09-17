<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Phoenix\EloquentMeta\MetaTrait;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\MailTemplate;
use Carbon;
use DateTime;
use Sentinel;

class Reservation extends Model {

    use MetaTrait;

    protected $table = 'reservations';

    public static function boot() {
        parent::boot();
    }

    public function createReservation($options) {
        extract(array_merge(
                        array(
                        ), $options
        ));

        $data = new Reservation;
        $data->date = $options['date'];
        $data->time = $options['time'];
        $data->persons = $options['persons'];
        $data->company_id = $options['companyId'];
        $data->user_id = $options['userId'];
        $data->reservation_id = $options['id'];
        $data->name = $options['name'];
        $data->email = $options['email'];
        $data->phone = $options['phone'];
        $data->comment = $options['comment'];
        $data->saldo = isset($options['reservationSaldo']) ? MoneyHelper::getAmount($options['saldo']) : 0;
        $data->newsletter_company = isset($options['newsletter']) ? 0 : 1;
        $data->allergies = json_encode($options['allergies']);
        $data->preferences = json_encode($options['preferences']);
        $data->status = $options['iframe'] == 1 ? ($options['isManual'] == 1 ? 'iframe-pending' : 'iframe') : ($options['isManual'] == 1 ? 'reserved-pending' : 'reserved');
        $data->source = (isset($options['source']) ? $options['source'] : 'UWvoordeelpas');
        $data->save();

        return $data;
    }

    public static function cancel($reservation) {
        if (
                new DateTime() < new DateTime($reservation->date . '' . $reservation->time) && new DateTime() < new DateTime($reservation->cancelBeforeTime)
        ) {
            // Give cash back to user
            if ($reservation->is_cancelled == 0) {
                if ($reservation->user_is_paid_back == 0) {
                    $user = Sentinel::getUser();
                    $user->saldo = $user->saldo + $reservation->saldo;
                    $user->save();
                }

                $reservationUpdate = Reservation::find($reservation->resId);
                $reservationUpdate->is_cancelled = 1;
                $reservationUpdate->user_is_paid_back = 1;
                $reservationUpdate->save();
            }

            $mailtemplate = new MailTemplate();

            // Send mail to client
            $mailtemplate->sendMail(array(
                'email' => $reservation->email,
                'template_id' => 'cancelled-reservation-client',
                'company_id' => $reservation->companyId,
                'replacements' => array(
                    '%name%' => $reservation->name,
                    '%cname%' => $reservation->companyCName,
                    '%saldo%' => $reservation->saldo,
                    '%phone%' => $reservation->phone,
                    '%email%' => $reservation->email,
                    '%date%' => date('d-m-Y', strtotime($reservation->date)),
                    '%time%' => date('H:i', strtotime($reservation->time)),
                    '%persons%' => $reservation->persons,
                    '%comment%' => $reservation->comment,
                    '%allergies%' => (count(json_decode($reservation->allergies)) >= 1 ? implode(',', json_decode($reservation->allergies)) : ''),
                    '%preferences%' => (count(json_decode($reservation->preferences)) >= 1 ? implode(',', json_decode($reservation->preferences)) : '')
                )
            ));

            // Send mail to owner
            $mailtemplate->sendMail(array(
                'email' => $reservation->companyEmail,
                'template_id' => 'cancelled-reservation-client-company',
                'company_id' => $reservation->companyId,
                'replacements' => array(
                    '%name%' => $reservation->name,
                    '%cname%' => $reservation->companyCName,
                    '%saldo%' => $reservation->saldo,
                    '%phone%' => $reservation->phone,
                    '%email%' => $reservation->email,
                    '%date%' => date('d-m-Y', strtotime($reservation->date)),
                    '%time%' => date('H:i', strtotime($reservation->time)),
                    '%persons%' => $reservation->persons,
                    '%comment%' => $reservation->comment,
                    '%allergies%' => (count(json_decode($reservation->allergies)) >= 1 ? implode(',', json_decode($reservation->allergies)) : ''),
                    '%preferences%' => (count(json_decode($reservation->preferences)) >= 1 ? implode(',', json_decode($reservation->preferences)) : '')
                )
            ));
        }
    }

    public static function countReservationByCriteria($params = array()) {
        $from_date = (isset($params['from_date']) && !empty($params['from_date'])) ? $params['from_date'] : NULL;
        $to_date = (isset($params['to_date']) && !empty($params['to_date'])) ? $params['to_date'] : NULL;
        $source = (isset($params['source']) && !empty($params['source'])) ? $params['source'] : NULL;
        $reservations = DB::table('reservations');
        if ($from_date && $to_date) {
            $reservations->where('date', '>=', $from_date)
                    ->where('date', '<=', $to_date);
        }
        if ($source) {
            if($source == 'wifi'){
                $reservations->join('users', 'users.id', '=', 'reservations.user_id');
                //$reservations->join('guests_wifi', 'guests_wifi.email', '=', 'users.email');                
            }
            else{
                $reservations->where('source', $source);
            }
            
        }
        return $reservations->get();        
    }

}
