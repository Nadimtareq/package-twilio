<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Phoenix\EloquentMeta\MetaTrait;
use DB;

class Transaction extends Model
{
    use MetaTrait;

    protected $table = 'transactions';
    
    protected $guarded = array();

    public function getCreatedAt() {
        return date('d-m-Y', strtotime($this->created_at));
    }

    /**
     * @author Soufyane Kaddouri - TodayDevelopment
     */
    public function getExpiredDate() {
        // Een functie die 90 dagen bij de created_at datum toevoegd
        return Carbon::createFromTimestamp(strtotime($this->created_at))->addDays(90)->format('d-m-Y');
    }
     public static function countTransactionByCriteria($params = array()) {
        $from_date = (isset($params['from_date']) && !empty($params['from_date'])) ? $params['from_date'] : NULL;
        $to_date = (isset($params['to_date']) && !empty($params['to_date'])) ? $params['to_date'] : NULL;        
        $transactions = DB::table('transactions');
        if ($from_date && $to_date) {
            $transactions->where('created_at', '>=', $from_date)
                    ->where('created_at', '<=', $to_date);
        }
      
        return $transactions->get();        
    }
}
