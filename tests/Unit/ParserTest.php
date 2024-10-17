<?php

use Phiki\Regex\Ast\Atoms\CharacterClass;
use Phiki\Regex\Ast\Atoms\CharacterClassMembers\Character;
use Phiki\Regex\Ast\Atoms\Group;
use Phiki\Regex\Ast\Atoms\LiteralCharacter;
use Phiki\Regex\Ast\Atoms\Period;
use Phiki\Regex\Ast\Pattern;
use Phiki\Regex\Ast\Quantifiers\Between;
use Phiki\Regex\Ast\Quantifiers\Exactly;
use Phiki\Regex\Ast\Quantifiers\ExactlyOrMore;
use Phiki\Regex\Ast\Quantifiers\OneOrMore;
use Phiki\Regex\Ast\Quantifiers\ZeroOrMore;
use Phiki\Regex\Ast\Quantifiers\ZeroOrOne;
use Phiki\Regex\Parser\Lexer;
use Phiki\Regex\Parser\Parser;

it('can parse a simple character pattern', function () {
    $pattern = parse('a');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
});

it('can parse a simple period pattern', function () {
    $pattern = parse('.');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(Period::class);
});

it('can parse a simple group pattern', function () {
    $pattern = parse('(abc)');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(Group::class);
    expect($pattern->elements[0]->atom->pattern->elements)->toHaveCount(3);

    $elements = $pattern->elements[0]->atom->pattern->elements;

    expect($elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($elements[1]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($elements[2]->atom)->toBeInstanceOf(LiteralCharacter::class);
});

it('can parse a simple character class', function () {
    $pattern = parse('[abc]');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(CharacterClass::class);
    expect($pattern->elements[0]->atom->members)->toHaveCount(3);

    $members = $pattern->elements[0]->atom->members;

    expect($members[0])->toBeInstanceOf(Character::class);
    expect($members[1])->toBeInstanceOf(Character::class);
    expect($members[2])->toBeInstanceOf(Character::class);
});

it('can parse a simple negated character class', function () {
    $pattern = parse('[^abc]');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(CharacterClass::class);
    expect($pattern->elements[0]->atom->members)->toHaveCount(3);
    expect($pattern->elements[0]->atom->negated)->toBeTrue();

    $members = $pattern->elements[0]->atom->members;

    expect($members[0])->toBeInstanceOf(Character::class);
    expect($members[1])->toBeInstanceOf(Character::class);
    expect($members[2])->toBeInstanceOf(Character::class);
});

it('can parse a character class with a simple range', function () {
    $pattern = parse('[a-z]');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(CharacterClass::class);
    expect($pattern->elements[0]->atom->members)->toHaveCount(1);

    $member = $pattern->elements[0]->atom->members[0];

    expect($member->start)->toBe('a');
    expect($member->end)->toBe('z');
});

it('can parse a pattern with a simple N-quantifier', function () {
    $pattern = parse('a{3}');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(Exactly::class);
    expect($pattern->elements[0]->quantifier->n)->toBe(3);
});

it('can parse a pattern with an N-inf quantifier', function () {
    $pattern = parse('a{3,}');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(ExactlyOrMore::class);
    expect($pattern->elements[0]->quantifier->n)->toBe(3);
});

it('can parse a pattern with an N-M quantifier', function () {
    $pattern = parse('a{3,5}');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(Between::class);
    expect($pattern->elements[0]->quantifier->min)->toBe(3);
    expect($pattern->elements[0]->quantifier->max)->toBe(5);
});

it('can parse a pattern with a simple ? (zero or one) quantifier', function () {
    $pattern = parse('a?');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(ZeroOrOne::class);
});

it('can parse a pattern with a ?? (zero or one) (lazy) quantifier', function () {
    $pattern = parse('a??');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(ZeroOrOne::class);
    expect($pattern->elements[0]->quantifier->greedy)->toBeFalse();
    expect($pattern->elements[0]->quantifier->lazy)->toBeTrue();
});

it('can parse a pattern with a ?+ (zero or one) (possessive) quantifier', function () {
    $pattern = parse('a?+');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(ZeroOrOne::class);
    expect($pattern->elements[0]->quantifier->greedy)->toBeFalse();
    expect($pattern->elements[0]->quantifier->possessive)->toBeTrue();
});

it('can parse a pattern with simple * (zero or more) quantifier', function () {
    $pattern = parse('a*');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(ZeroOrMore::class);
});

it('can parse a pattern with simple *? (zero or more) (lazy) quantifier', function () {
    $pattern = parse('a*?');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(ZeroOrMore::class);
    expect($pattern->elements[0]->quantifier->greedy)->toBeFalse();
    expect($pattern->elements[0]->quantifier->lazy)->toBeTrue();
});

it('can parse a pattern with simple *+ (zero or more) (possessive) quantifier', function () {
    $pattern = parse('a*+');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(ZeroOrMore::class);
    expect($pattern->elements[0]->quantifier->greedy)->toBeFalse();
    expect($pattern->elements[0]->quantifier->possessive)->toBeTrue();
});

it('can parse a pattern with simple + (one or more) quantifier', function () {
    $pattern = parse('a+');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(OneOrMore::class);
});

it('can parse a pattern with simple +? (one or more) (lazy) quantifier', function () {
    $pattern = parse('a+?');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(OneOrMore::class);
    expect($pattern->elements[0]->quantifier->greedy)->toBeFalse();
    expect($pattern->elements[0]->quantifier->lazy)->toBeTrue();
});

it('can parse a pattern with simple ++ (one or more) (possessive) quantifier', function () {
    $pattern = parse('a++');

    expect($pattern->elements)->toHaveCount(1);
    expect($pattern->elements[0]->atom)->toBeInstanceOf(LiteralCharacter::class);
    expect($pattern->elements[0]->quantifier)->toBeInstanceOf(OneOrMore::class);
    expect($pattern->elements[0]->quantifier->greedy)->toBeFalse();
    expect($pattern->elements[0]->quantifier->possessive)->toBeTrue();
});

function parse(string $pattern): Pattern
{
    $lexer = new Lexer;
    $parser = new Parser;

    return $parser->parse($lexer->tokenise($pattern));
}
