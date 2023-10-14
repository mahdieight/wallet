<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\Payment\PaymentStatusEnum;
use App\Events\PaymentRejected;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Resources\PaymentResource;
use App\Jobs\sendRejectPaymentEmail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::paginate(20);
        return response()->json([
            'messages' => __('payment.messages.payment_list_found_successfully'),
            'data' => PaymentResource::collection($payments)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentStoreRequest $request)
    {
        $payment = Payment::create(array_merge($request->all(), ['user_id' => 1]));

        return response([
            'message' => __('payment.messages.payment_successfuly_created'),
            'data' => [new PaymentResource($payment)],
            'errors' => []
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return response([
            'message' =>  __('payment.messages.payment_successfuly_found'),
            'data' => [new PaymentResource($payment)],
            'errors' => []
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function reject(Payment $payment)
    {
        // if ($payment->status->value != PaymentStatusEnum::PENDING->value) {
        //     throw new BadRequestException(__('payment.errors.you_can_only_decline_pending_payments'), 403);
        // }

        $payment->update([
            'status' => PaymentStatusEnum::REJECTED->value,
        ]);

        $message = $payment->user->name . " Dear, Payment " . $payment->unique_id . " Rejected.";

        PaymentRejected::dispatch($payment,$message);

        return response([
            'messages' => __('payment.messages.the_payment_was_successfully_rejected'),
            'data' => [new PaymentResource($payment)],
            'errors' => []
        ], 201);
    }
}
