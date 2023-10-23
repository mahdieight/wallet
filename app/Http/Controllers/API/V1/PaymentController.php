<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\Payment\PaymentStatusEnum;
use App\Events\Payment\PaymentApproved;
use App\Events\Payment\PaymentRejected;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class PaymentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/payments",
     *      operationId="getPaymentList",
     *      tags={"Payments"},
     *      summary="Get Payment List",
     *      description="Returns payment list",
     *      @OA\Response(response=201,description="Successful operation"),
     *      @OA\Response(response=404, description="Payment List Not Found"),
     * )
     */
    public function index()
    {
        $payments = Payment::paginate(20);
        return Response::message('payment.messages.payment_list_found_successfully')->data(PaymentResource::collection($payments))->send();
    }




    /**
     * @OA\Post(
     *      path="/api/v1/payments",
     *      operationId="createPayment",
     *      tags={"Payments"},
     *      summary="Create Payment",
     *      description="Store a newly created resource in storage.",
     *      @OA\Response(response=200,description="Payment Created"),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function store(PaymentStoreRequest $request)
    {

        $user_id = 1;
        $similarPayment = Payment::query()
            ->where('currency_key', $request->currency_key)
            ->where('user_id', $user_id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();


        if ($similarPayment) throw new BadRequestException(__('payment.errors.you_have_a_similar_payment_in_the_system', ['time' => $similarPayment->created_at->diffForHumans()]));

        $payment = Payment::create(array_merge($request->all(), ['user_id' => $user_id]));
        return Response::message('payment.messages.payment_successfuly_created')->data(new PaymentResource($payment))->status(200)->send();
    }



    /**
     * @OA\Get(
     *      path="/api/v1/payments/{id}",
     *      operationId="getPayment",
     *      tags={"Payments"},
     *      summary="Get Payment",
     *      description="Display the specified resource.",
     *      @OA\Response(response=201,description="Payment Found"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Not Found"),
     *      @OA\Parameter(
     *         description="Payment id",
     *         in="path",
     *         name="id",
     *         required=true,

     *     ),
     * )
     */
    public function show(Payment $payment)
    {
        return Response::message('payment.messages.payment_successfuly_found')->data(new PaymentResource($payment))->send();
    }




    /**
     * @OA\Patch(
     *      path="/api/v1/payments/{id}/reject",
     *      operationId="rejectPayment",
     *      tags={"Payments"},
     *      summary="Reject Payment",
     *      description="Display the specified resource.",
     *      @OA\Response(response=201,description="Payment Successfuly Rejected"),
     *      @OA\Response(response=403, description="Bad request"),
     *      @OA\Response(response=404, description="Not Found"),
     *      @OA\Parameter(
     *         description="Payment id",
     *         in="path",
     *         name="id",
     *         required=true,

     *     ),
     * )
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
     * @OA\Patch(
     *      path="/api/v1/payments/{id}/approve",
     *      operationId="approvePayment",
     *      tags={"Payments"},
     *      summary="Approve Payment",
     *      description="Approve  payment",
     *      @OA\Response(response=201,description="Payment Successfuly Approved"),
     *      @OA\Response(response=403, description="Bad request"),
     *      @OA\Response(response=404, description="Not Found"),
     *      @OA\Parameter(
     *         description="Payment id",
     *         in="path",
     *         name="id",
     *         required=true,

     *     ),
     * )
     */
    public function approve(Payment $payment)
    {

        // if ($payment->status->value != PaymentStatusEnum::PENDING->value) {
        //     throw new BadRequestException(__('payment.errors.you_can_only_decline_pending_payments'));
        // }

        dd($payment->transaction());
        if ($payment->transaction->get()) {
            throw new BadRequestException('payment.errors.this_payment_has_already_been_used');
        }

        DB::transaction(function () use ($payment) {
            Transaction::create([
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'currency_key' => $payment->currency_key,
            ]);

            $payment->update([
                'status' => PaymentStatusEnum::APPROVED->value,
            ]);
        });



        PaymentApproved::dispatch($payment, PaymentStatusEnum::APPROVED);


        return Response::message('payment.messages.the_payment_was_successfully_approved')->data(new PaymentResource($payment))->send();
    }
}
