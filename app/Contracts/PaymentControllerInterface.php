<?php

namespace App\Contracts;

use App\Http\Requests\PaymentStoreRequest;
use App\Models\Payment;

interface PaymentControllerInterface
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
    public function index();


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
    public function store(PaymentStoreRequest $request);


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
    public function show(Payment $payment);


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
    public function reject(Payment $payment);


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
    public function approve(Payment $payment);


    /**
     * @OA\Delete(
     *      path="/api/v1/payments/{id}",
     *      operationId="DeletePayment",
     *      tags={"Payments"},
     *      summary="Delete Payment",
     *      description="Delete  payment",
     *      @OA\Response(response=201,description="Payment Successfuly Removed"),
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
    public function destroy(Payment $payment);
}
