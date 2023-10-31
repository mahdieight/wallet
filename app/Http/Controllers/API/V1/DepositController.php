<?php

namespace App\Http\Controllers\API\V1;

use App\Contracts\Controller\API\V1\DepositControllerInterface;
use App\Facades\Response;
use App\Http\Requests\DepositCreateRequest;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DepositController extends Controller implements DepositControllerInterface
{
    public function transfer(DepositCreateRequest $request)
    {
        DB::beginTransaction();

        $baseUser = User::whereId($request->base_user_id)->first();
        $targetUser = User::whereId($request->target_user_id)->first();

        $currency = Currency::whereKey($request->currency_key)->first();
        $baseUserBalance = json_decode($baseUser->balance)->{$currency->key};

        if ($request->amount > $baseUserBalance) {
            throw new BadRequestException(__('transfer.errors.amount_must_be_smaller_than_your_balance'));
        }

        $baseUser->transactions()->lockForUpdate();
        $targetUser->transactions()->lockForUpdate();

        $baseUser->transactions()->create([
            'amount' => $request->amount * -1,
            'currency_key' => $currency->key,
        ]);

        $targetUser->transactions()->create([
            'amount' => $request->amount,
            'currency_key' => $currency->key,
        ]);

        auth()->user()->deposits()->create([
            'from_user_id' => $baseUser->id,
            'to_user_id' => $targetUser->id,
            'currency_key' => $currency->key,
            'amount' => $request->amount,
        ]);

        DB::commit();

        return Response::message(__('transfer.messages.the_transfer_was_successfully_approved'))->send();
    }
}
