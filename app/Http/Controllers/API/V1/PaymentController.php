<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\Payment\PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Resources\PaymentResource;
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
            'messages' => 'Payment list found successfully',
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

        return response('', 201)->json([
            'messages' => 'payment successfuly created',
            'data' => new PaymentResource($payment)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        if ($payment->status != PaymentStatusEnum::PENDING->value) {
            throw new BadRequestException("You can only decline pending payments" , 403);
        }

        $payment->update([
            'status' => PaymentStatusEnum::REJECTED->value,
        ]);

        return response('', 201)->json([
            'messages' => 'The payment was successfully rejected',
            'data' => new PaymentResource($payment)
        ]);
    }
}
