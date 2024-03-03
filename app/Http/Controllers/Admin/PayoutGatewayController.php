<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload;
use App\Models\PayoutMethod;
use Illuminate\Http\Request;

class PayoutGatewayController extends Controller
{
    use Upload;
}
