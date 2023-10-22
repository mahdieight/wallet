<?php

namespace App\Http\Controllers\API\V1;

use App\Events\Currency\CurrencyActivated;
use App\Events\Currency\CurrencyDeActivated;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyStoreRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CurrencyController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/currencies",
     *      operationId="getCurrencyList",
     *      tags={"Currency"},
     *      summary="Get Currency List",
     *      description="Returns currency list",
     *      @OA\Response(response=201,description="Successful operation"),
     *      @OA\Response(response=404, description="Payment List Not Found"),
     * )
     */
    public function index()
    {
        $currencies = Currency::paginate(20);
        return Response::message('payment.messages.payment_list_found_successfully')->data(CurrencyResource::collection($currencies))->send();
    }

    /**
     * @OA\Post(
     *      path="/api/v1/currencies",
     *      operationId="createCurrency",
     *      tags={"Currency"},
     *      summary="Create Currency",
     *      description="Store a newly created resource in storage.",
     *      @OA\Response(response=200,description="Currency Created"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=true,
     *         description="key currency",
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="name currency",
     *     ),
     *      @OA\Parameter(
     *         name="symbol",
     *         in="query",
     *         required=true,
     *         description="symbol currency",
     *     ),
     *      @OA\Parameter(
     *         name="iso_code",
     *         in="query",
     *         required=true,
     *         description="symbol currency",
     *     ),
     * )
     */
    public function store(CurrencyStoreRequest $request)
    {
        $currency = Currency::create($request->all());

        CurrencyActivated::dispatch($currency);

        return Response::message('currency.messages.currency_successfully_created')->data(new CurrencyResource($currency))->status(200)->send();
    }



    /**
     * @OA\Patch(
     *      path="/api/v1/currencies/{key}/active",
     *      operationId="activeCurrency",
     *      tags={"Currency"},
     *      summary="Active Currency",
     *      description="Active Currency",
     *      @OA\Response(response=201,description="Currency Successfully Activated"),
     *      @OA\Response(response=403, description="Bad request"),
     *      @OA\Response(response=404, description="Not Found"),
     *      @OA\Parameter(
     *         description="currency key",
     *         in="path",
     *         name="key",
     *         required=true,
     *     ),
     * )
     */
    public function active(Currency $currency)
    {
        if ($currency->is_active)  throw new BadRequestException(__('currency.errors.currency_is_currently_active_and_cannot_be_reactivated'));

        $currency->update(['is_active' => 1]);

        CurrencyActivated::dispatch($currency);

        Response::message('currency.messages.the_currency_has_been_activated_successfully')->data(new CurrencyResource($currency))->send();
    }


    /**
     * @OA\Patch(
     *      path="/api/v1/currencies/{key}/deactive",
     *      operationId="deactiveCurrency",
     *      tags={"Currency"},
     *      summary="DeActive Currency",
     *      description="DeActive Currency",
     *      @OA\Response(response=201,description="Currency Successfully DeActivated"),
     *      @OA\Response(response=403, description="Bad request"),
     *      @OA\Response(response=404, description="Not Found"),
     *      @OA\Parameter(
     *         description="currency key",
     *         in="path",
     *         name="key",
     *         required=true,
     *     ),
     * )
     */
    public function deActive(Currency $currency)
    {
        if (!$currency->is_active)  throw new BadRequestException(__('currency.errors.currency_is_currently_inactive_and_cannot_be_reactivated'));

        $currency->update(['is_active' => 0]);

        CurrencyDeActivated::dispatch($currency);

        Response::message('currency.messages.the_currency_has_been_deactivated_successfully')->data(new CurrencyResource($currency))->send();
    }
}
