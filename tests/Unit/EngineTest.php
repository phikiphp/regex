<?php

use Phiki\Regex\Engine;

it('can match against a simple single character pattern', function () {
    $matches = matches('/a/', 'cba');
    
    expect($matches)->toBe([0 => 'a']);
});

function matches(string $pattern, string $subject): array
{
    $engine = new Engine(usePcre: false);
    $engine->match($pattern, $subject, $matches);

    return $matches;
}