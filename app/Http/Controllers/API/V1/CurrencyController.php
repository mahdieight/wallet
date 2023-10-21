<?php

namespace App\Http\Controllers\API\V1;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyStoreRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;


class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        return Response::message('currency.messages.currency_successfully_created')->data(new CurrencyResource($currency))->status(200)->send();

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
}
