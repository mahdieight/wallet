<?php

namespace App\Http\Controllers\API\V1;

use App\Contracts\DepositControllerInterface;
use App\Http\Requests\DepositCreateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller implements DepositControllerInterface
{
    public function transfer(DepositCreateRequest $request)
    {
        DB::beginTransaction();
        try {

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
