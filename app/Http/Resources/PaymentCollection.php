<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'docs' => PaymentResource::collection($this->collection),
            'meta' => [
                'perPage' => $this->perPage(),
                'totalDocs' => $this->total(),
                'totalPages' => $this->lastPage(),
                'currentPage'=> $this->currentPage(),
                'nextPage' => $this->nextPageUrl(),
                'prevPage' => $this->previousPageUrl(),
            ]
        ];

    }
}
