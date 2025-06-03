<?php

declare(strict_types=1);

use App\Enums\GuardsEnum;
use ValueError;

test('it has expected values', function (): void {
    expect(GuardsEnum::API->value)->toBe('api')
        ->and(GuardsEnum::WEB->value)->toBe('web');
});

test('it has expected cases', function (): void {
    $cases = GuardsEnum::cases();

    expect($cases)->toHaveCount(2)
        ->and($cases[0])->toBeInstanceOf(GuardsEnum::class)
        ->and($cases[1])->toBeInstanceOf(GuardsEnum::class)
        ->and($cases[0]->name)->toBe('API')
        ->and($cases[1]->name)->toBe('WEB');
});

test('it can get all values', function (): void {
    expect(GuardsEnum::all())->toBe(['api', 'web']);
});

test('it can check if value exists', function (): void {
    expect(in_array('api', GuardsEnum::all()))->toBeTrue()
        ->and(in_array('web', GuardsEnum::all()))->toBeTrue()
        ->and(in_array('invalid_guard', GuardsEnum::all()))->toBeFalse();
});

test('it can get enum by value', function (): void {
    expect(GuardsEnum::from('api'))->toBe(GuardsEnum::API)
        ->and(GuardsEnum::from('web'))->toBe(GuardsEnum::WEB);
});

test('it throws exception for invalid value', function (): void {
    expect(fn () => GuardsEnum::from('invalid_guard'))
        ->toThrow(ValueError::class);
});
