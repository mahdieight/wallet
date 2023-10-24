<?php

namespace App\Contracts;

use App\Http\Requests\DepositCreateRequest;

interface DepositControllerInterface {
    
    public function transfer(DepositCreateRequest $request);
    
}