<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Role */
class RolesCollection extends ResourceCollection
{
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'count' => $this->collection->count(),
            ],
        ];
    }
}
