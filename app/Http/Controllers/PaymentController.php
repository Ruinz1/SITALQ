<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\MidtransService;


class PaymentController extends Controller
{
 
    public function notificationHandler(Request $request)
    {
        Log::info('Webhook masuk', $request->all());
        app(MidtransService::class)->handleNotification($request);
        return response()->json(['status' => 'ok']);
    }
} 