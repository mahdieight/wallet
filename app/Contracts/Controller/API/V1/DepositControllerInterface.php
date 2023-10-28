<?php

namespace App\Contracts\Controller\API\V1;

use App\Http\Requests\DepositCreateRequest;

interface DepositControllerInterface {
    
    public function transfer(DepositCreateRequest $request);
    
}