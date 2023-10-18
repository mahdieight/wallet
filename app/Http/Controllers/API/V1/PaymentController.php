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

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Wallet",
 * )
 */
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
        $payment = Payment::create(array_merge($request->all(), ['user_id' => 1]));
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
