<?php

declare(strict_types=1);

namespace App\Enums;

enum GuardsEnum: string
{
    case API = 'api';
    case WEB = 'web';

    public static function all(): array
    {
        return [
            GuardsEnum::API->value,
            GuardsEnum::WEB->value,
        ];
    }
}
