<?php

namespace App\Http\Controllers\Port;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PortController extends Controller
{
    public function port(){
        $url="http://xue.qianqianya.xyz/userreg";
        $ch=curl_close($url);

    }
}
