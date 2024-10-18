<?php

use Phiki\Regex\Engine;

pest()->group('engine');

it('can match against a simple single character pattern', function () {
    $matches = matches('/a/', 'cba');
    
    expect($matches)->toBe([0 => 'a']);
});

it('can match against a multi character pattern', function () {
    $matches = matches('/abc/', 'fedabc');

    expect($matches)->toBe([0 => 'abc']);
});

it('can match against a pattern with a period', function () {
    $matches = matches('/a.c/', 'abc');

    expect($matches)->toBe([0 => 'abc']);
});

it('can match against a pattern with a character class', function () {
    $matches = matches('/[abc]/', 'fedb');

    expect($matches)->toBe([0 => 'b']);
});

it('can match against a pattern with a negated character class', function () {
    $matches = matches('/[^abc]/', 'fedb');

    expect($matches)->toBe([0 => 'f']);
});

it('can match against a pattern inside of a group', function () {
    $matches = matches('/(abc)/', 'fedabc');

    expect($matches)->toBe([0 => 'abc', 1 => 'abc']);
});

function matches(string $pattern, string $subject): array
{
    $engine = new Engine(usePcre: false);
    $engine->match($pattern, $subject, $matches);

    return $matches;
}