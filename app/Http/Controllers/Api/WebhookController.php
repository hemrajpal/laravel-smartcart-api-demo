<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Payment;
use App\Helpers\ApiResponse;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function stripe(Request $request)
    {
        Log::info('webhook called');

        $payload = $request->getContent();

        $signature = $request->header('Stripe-Signature');

        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload');

            return response()->json([
                'message' => 'Invalid payload'
            ], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid Stripe webhook signature');

            return response()->json([
                'message' => 'Invalid signature'
            ], 400);
        }

        switch ($event->type) {

            case 'payment_intent.succeeded':

                $paymentIntent = $event->data->object;

                Log::info('Stripe payment succeeded', [
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                ]);

                $payment = Payment::where(
                    'gateway_transaction_id',
                    $paymentIntent->id
                )->first();

                if ($payment) {
                    $payment->update([
                        'status' => 'paid'
                    ]);

                    $order = $payment->order;
                    if ($order && $order->status !== 'confirmed') {
                        $order->update([
                            'status' => 'confirmed',
                        ]);
                    }
                }                

                break;


            case 'payment_intent.payment_failed':

                $paymentIntent = $event->data->object;

                Log::error('Stripe payment failed', [
                    'payment_intent_id' => $paymentIntent->id,
                    'error' => $paymentIntent->last_payment_error?->message,
                ]);

                $payment = Payment::where(
                    'gateway_transaction_id',
                    $paymentIntent->id
                )->first();

                if ($payment) {
                    $payment->update([
                        'status' => 'failed',
                    ]);
                }

                break;
        }

        return response()->json([
            'received' => true
        ], 200);
    }

}
