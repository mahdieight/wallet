<?php

namespace App\Http\Controllers\API\V1;

use App\Contracts\Controller\API\V1\CurrencyControllerInterface;
use App\Enums\Payment\PaymentStatusEnum;
use App\Events\Currency\CurrencyActivated;
use App\Events\Currency\CurrencyDeActivated;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyStoreRequest;
use App\Http\Resources\CurrencyCollection;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Models\Payment;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CurrencyController extends Controller implements CurrencyControllerInterface
{

    public function index()
    {
        $currencies = Currency::paginate(20);
        return Response::message('payment.messages.payment_list_found_successfully')->data(new CurrencyCollection($currencies))->send();
    }


    public function store(CurrencyStoreRequest $request)
    {
        $currency = Currency::create($request->all());

        CurrencyActivated::dispatch($currency);

        return Response::message('currency.messages.currency_successfully_created')->data(new CurrencyResource($currency))->status(200)->send();
    }


    public function active(Currency $currency)
    {
        if ($currency->is_active)  throw new BadRequestException(__('currency.errors.currency_is_currently_active_and_cannot_be_reactivated'));

        $currency->update(['is_active' => true]);

        CurrencyActivated::dispatch($currency);

        Response::message('currency.messages.the_currency_has_been_activated_successfully')->data(new CurrencyResource($currency))->send();
    }


    public function deActive(Currency $currency)
    {
        if (!$currency->is_active)  throw new BadRequestException(__('currency.errors.currency_is_currently_inactive_and_cannot_be_reactivated'));

        if (Payment::where('status', PaymentStatusEnum::PENDING)->where('currency_key', $currency->key)->first()) {
            throw new BadRequestException(__('currency.errors.it_is_not_possible_to_deactivate_this_currency_due_to_its_use_in_the_system'));
        }

        $currency->update(['is_active' => false]);

        CurrencyDeActivated::dispatch($currency);

        Response::message('currency.messages.the_currency_has_been_deactivated_successfully')->data(new CurrencyResource($currency))->send();
    }
}
