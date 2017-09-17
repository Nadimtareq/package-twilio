<?php
namespace App\Helpers;

class AffiliateHelper 
{

    public function getAffiliateUrl($obj, $subId) 
    {
        $network = is_array($obj) ? $obj['affiliate_network'] : $obj->affiliate_network;

        if (!isset($network) || !isset($subId)) {
            return false;
        }
        
        $url = is_array($obj) ? $obj['tracking_link']: $obj->tracking_link;
        
        switch ($network) {
            case 'tradedoubler':
                $url = str_replace(array('epi=#SUB_ID#'), 'epi='.$subId, $url);
                $url = str_replace(array('a=#SITEID#'), 'a='.getenv('TRADEDOUBLER_SITE_ID'), $url);
                break;

            case 'zanox':
                $url = str_replace(array('?zpar0=', '&zpar0='), '', $url);
                $url .= '&zpar0='.$subId;
                break;

            case 'affilinet':
                $url = str_replace(array('subid=#SUB_ID#'), 'subid='.$subId, $url);
                $url = str_replace(array('ref=#SITEID#'), 'ref='.getenv('AFFILINET_SITE_ID'), $url);
                break;

            case 'tradetracker':
                if (strpos($url, 'tc.tradetracker') !== false) {
                    $url = str_replace(array('r='), 'r='.$subId, $url);
                } else {
                    preg_match('/tt=([^&]*)/i', $url, $matches, PREG_OFFSET_CAPTURE);

                    if (isset($matches[1][0])) {
                        $url = str_replace(array('tt='.$matches[1][0]), 'tt='.$matches[1][0].$subId, $url);
                    }
                }
                break;

            case 'daisycon':
                $url = str_replace(array('ws=#SUB_ID#'), 'ws='.$subId, $url);
                $url = str_replace(array('wi=#MEDIA_ID#'), 'wi='.getenv('DAISYCON_SITE_ID'), $url);
                break;
        }
        
        return $url;
    }

    public function arrayAssoc($array) 
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
    
    public static function sortArrayCommissionsAsc($item_1, $item_2)
    {
        if ($item_1['commissionsNoUnit'] == $item_2['commissionsNoUnit']) {
            return 0;
        }
        
        return $item_1['commissionsNoUnit'] < $item_2['commissionsNoUnit'] ? -1 : 1;
    }

    public static function sortArrayCommissionsDesc($item_1, $item_2)
    {
        return $item_2['commissionsNoUnit'] - $item_1['commissionsNoUnit'];
    }

    public static function sortArray($item_1, $item_2)
    {
        return $item_2['value'] - $item_1['value'];
    }

    public function amountAsUnit($amount, $unit, $noUnit = NULL) 
    {
        if(!is_numeric($amount)){
            return "Not Available";
        }
        $isPercentage = $unit == '%' ? 1 : 0;
        $maxAmount = $isPercentage == 1 ? ($amount / 100 * 70) : number_format($amount / 100 * 70, 2, ',', ' ');

        return ($isPercentage == 0  && $noUnit != 1 ? '&euro;' : '').$maxAmount.($isPercentage == 1 && $noUnit != 1 ? '%' : '');
    }

    public static function commissionMaxValue($commisions, $noUnit = NULL) 
    {
        if (count(json_decode($commisions)) >= 1) {
            foreach (json_decode($commisions) as $key => $commission) {
                if(isset($commission->value) && !isset($commission->noshow)) {
                    $max[] = array(
                        'value' => $commission->value,
                        'unit' => $commission->unit,
                        'noshow' => isset($commission->noshow) ? 1 : 0
                    );
                }
            }

            if (isset($max)) {
                usort($max, array('App\Helpers\AffiliateHelper', 'sortArray'));

                $affiliateHelper = new AffiliateHelper();
                return $affiliateHelper->amountAsUnit($max[0]['value'], $max[0]['unit'], $noUnit);
            }
        }
    }
    
    public function commissionUnique($commissions) 
    {
        $commissionArray = array();
        $resultArray = array();

        foreach ($commissions as $key => $commission) {
            if (!isset($commission->noshow)) {
                $commissionArray[$commission->name][] = array(
                    'value' => $commission->value,
                    'unit' => $commission->unit,
                    'name' => $commission->name
                );
            }
        }

        foreach ($commissionArray as $key => $value) {
            $resultArray[] = max($value);
        }

        return $resultArray;
    }

    public function domainRestriction($domain) 
    {
        if (
            preg_match('/(.*?)\.fr$/', parse_url($domain, PHP_URL_HOST)) 
            OR preg_match('/(.*?)\.de$/', parse_url($domain, PHP_URL_HOST))
        ) {
            return 0;
        } else {
            return 1;
        }
    }

    public function categoryDuplicates($name, $categories) 
    {
        $nameWords = explode(' ', str_replace( ',', '', $name));

        $blockWords = array(
            'en',
            '&amp;',
            '&',
            ',',
            '´',
            "'",
        );

        foreach ($nameWords as $key => $nameWord) {
            if (in_array($nameWord, $blockWords)) {
                unset($nameWords[$key]);
            }
        }

        foreach ($categories as $cat) {
            foreach ($nameWords as $value) {
                if (in_array(strtolower($value), explode(' ', strtolower($cat)))) {
                    $categoriesExists[$name][] = $cat;
                } 

                if(count($nameWords) == 1) {
                    if(substr(strtolower($name), 0, 4) == substr(strtolower($cat), 0, 4))  {
                        $categoriesExists[$name][] = $cat;
                    }
                }
            }
        }

        return isset($categoriesExists) ? $categoriesExists : array();
    }

}