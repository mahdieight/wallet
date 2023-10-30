<?php

namespace App\Http\Controllers\API\V1;

use App\Contracts\Controller\API\V1\PaymentControllerInterface;
use App\Enums\Payment\PaymentStatusEnum;
use App\Events\Payment\PaymentApproved;
use App\Events\Payment\PaymentRejected;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class PaymentController extends Controller implements PaymentControllerInterface
{


    public function index()
    {
        $payments = Payment::paginate();
        return Response::message('payment.messages.payment_list_found_successfully')->data(new PaymentCollection($payments))->send();
    }


    public function store(PaymentStoreRequest $request)
    {
        $similarPayment = auth()->user()->payments()
            ->where('currency_key', $request->currency_key)
            ->where('created_at', '>=', now()->subMinutes(config('app.global.payment.similar_payment_registration_limit_per_minute')))
            ->first();
        if ($similarPayment) {
            throw new BadRequestException(__('payment.errors.you_have_a_similar_payment_in_the_system', ['time' => $similarPayment->created_at->diffForHumans()]));
        }

        $payment = auth()->user()->payments()->create($request->all());
        return Response::message('payment.messages.payment_successfuly_created')->data(new PaymentResource($payment))->status(200)->send();
    }


    public function show(Payment $payment)
    {
        return Response::message('payment.messages.payment_successfuly_found')->data(new PaymentResource($payment))->send();
    }


    public function reject(Payment $payment)
    {
        if ($payment->status != PaymentStatusEnum::PENDING) {
            throw new BadRequestException(__('payment.errors.you_can_only_decline_pending_payments'));
        }

        $payment->update([
            'status' => PaymentStatusEnum::REJECTED->value,
        ]);

        PaymentRejected::dispatch($payment, PaymentStatusEnum::REJECTED);

        return Response::message('payment.messages.the_payment_was_successfully_rejected')->data(new PaymentResource($payment))->send();
    }


    public function approve(Payment $payment)
    {

        if ($payment->status != PaymentStatusEnum::PENDING) {
            throw new BadRequestException(__('payment.errors.you_can_only_decline_pending_payments'));
        }

        if ($payment->transaction()->exists()) {
            throw new BadRequestException('payment.errors.this_payment_has_already_been_used');
        }

        DB::transaction(function () use ($payment) {
            $payment->transaction()->create([
                'amount' => $payment->amount,
                'currency_key' => $payment->currency_key,
                'user_id' => $payment->user_id,
            ]);
            $payment->update([
                'status' => PaymentStatusEnum::APPROVED->value,
            ]);
        });



        PaymentApproved::dispatch($payment, PaymentStatusEnum::APPROVED);


        return Response::message('payment.messages.the_payment_was_successfully_approved')->data(new PaymentResource($payment))->send();
    }

    public function destroy(Payment $payment)
    {
        if ($payment->status != PaymentStatusEnum::PENDING) {
            return throw new BadRequestException(__('payment.errors.you_can_delete_pending_payments'));
        }

        $payment->delete();

        return Response::message(__('payment.messages.payment_successfully_removed'))->send();
    }
}
