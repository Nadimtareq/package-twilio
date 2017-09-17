<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dcrypt($encoded)
    {
        $decoded = "";
        for( $i = 0; $i < strlen($encoded); $i++ ) {
            $b = ord($encoded[$i]);
            $a = $b ^ 123;  // <-- must be same number used to encode the character
            $decoded .= chr($a);
        }
        return $decoded;
    }
}
