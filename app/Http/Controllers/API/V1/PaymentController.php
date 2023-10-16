<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\Payment\PaymentStatusEnum;
use App\Events\PaymentApproved;
use App\Events\PaymentRejected;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Transaction;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::paginate(20);
        return Response::message('payment.messages.payment_list_found_successfully')->date(PaymentResource::collection($payments))->send();
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentStoreRequest $request)
    {
        $payment = Payment::create(array_merge($request->all(), ['user_id' => 1]));
        return Response::message('payment.messages.payment_successfuly_created')->data(new PaymentResource($payment))->status(200)->send();
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return Response::message('payment.messages.payment_successfuly_found')->data(new PaymentResource($payment))->send();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * reject payment.
     */
    public function reject(Payment $payment)
    {
        if ($payment->status->value != PaymentStatusEnum::PENDING->value) {
            throw new BadRequestException(__('payment.errors.you_can_only_decline_pending_payments'), 403);
        }

        $payment->update([
            'status' => PaymentStatusEnum::REJECTED->value,
        ]);

        PaymentRejected::dispatch($payment, PaymentStatusEnum::REJECTED);

        return Response::message('payment.messages.the_payment_was_successfully_rejected')->data(new PaymentResource($payment))->send();
    }

    /**
     * approve payment.
     */
    public function approve(Payment $payment)
    {

        if ($payment->status->value != PaymentStatusEnum::PENDING->value) {
            throw new BadRequestException(__('payment.errors.you_can_only_decline_pending_payments'), 403);
        }

        $transactionExits = Transaction::wherePaymentId($payment->id)->first();
        if ($transactionExits) {
            throw new BadRequestException('payment.errors.this_payment_has_already_been_used', 403);
        }

        $payment->update([
            'status' => PaymentStatusEnum::APPROVED->value,
        ]);

        PaymentApproved::dispatch($payment, PaymentStatusEnum::APPROVED);

        return Response::message('payment.messages.the_payment_was_successfully_rejected')->data(new PaymentResource($payment))->send();
    }
}
