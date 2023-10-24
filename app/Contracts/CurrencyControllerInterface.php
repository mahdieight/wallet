<?php

namespace App\Contracts;

use App\Http\Requests\CurrencyStoreRequest;
use App\Models\Currency;

interface CurrencyControllerInterface
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
    public function index();


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
    public function store(CurrencyStoreRequest $request);



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
    public function active(Currency $currency);


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
    public function deActive(Currency $currency);
}
