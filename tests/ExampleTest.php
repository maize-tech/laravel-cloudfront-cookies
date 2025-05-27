<?php

use function Pest\Laravel\get;

it('can test', function () {
    expect(true)->toBeTrue();

    $response = get(
        uri: '/',
        // cookies: collect($cookies)
        //     ->mapWithKeys(fn (string $c) => [
        //         $c => fake()->word(),
        //     ])
        //     ->toArray(),
    );

});
