<?php

namespace Phiki\Regex\Parser;

use Exception;
use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Ast\Atoms\CharacterClass;
use Phiki\Regex\Ast\Atoms\CharacterClassMembers\Character;
use Phiki\Regex\Ast\Atoms\CharacterClassMembers\CharacterRange;
use Phiki\Regex\Ast\Atoms\Group;
use Phiki\Regex\Ast\Atoms\LiteralCharacter;
use Phiki\Regex\Ast\Atoms\Period;
use Phiki\Regex\Ast\CharacterClassMember;
use Phiki\Regex\Ast\Element;
use Phiki\Regex\Ast\Pattern;
use Phiki\Regex\Ast\Quantifier;
use Phiki\Regex\Ast\Quantifiers\Between;
use Phiki\Regex\Ast\Quantifiers\Exactly;
use Phiki\Regex\Ast\Quantifiers\ExactlyOrMore;
use Phiki\Regex\Ast\Quantifiers\ZeroOrOne;

class Parser
{
    /** @param Token[] $tokens */
    public function parse(array $tokens): Pattern
    {
        $stream = new TokenStream($tokens);
        $elements = [];

        while (! $stream->isEof()) {
            $elements[] = $this->element($stream);
        }

        return new Pattern($elements);
    }

    protected function element(TokenStream $stream): Element
    {
        $atom = $this->atom($stream);

        // FIXME: Make sure $atom is quantifiable.
        $quantifier = $this->quantifier($stream);

        return new Element($atom, $quantifier);
    }

    protected function atom(TokenStream $stream): Atom
    {
        $token = $stream->current();

        if ($token->kind === TokenKind::LeftParen) {
            return $this->group($stream);
        }

        if ($token->kind === TokenKind::LeftBracket) {
            return $this->characterClass($stream);
        }

        if ($token->kind === TokenKind::Period) {
            return $this->period($stream);
        }

        if ($token->kind === TokenKind::Char) {
            return $this->char($stream);
        }

        throw new Exception('Unexpected token: '.$token->kind->name);
    }

    protected function group(TokenStream $stream): Atom
    {
        // FIXME: Add group type parsing.

        $stream->expect(TokenKind::LeftParen);
        $elements = [];

        while (! $stream->isEof() && $stream->current()->kind !== TokenKind::RightParen) {
            $elements[] = $this->element($stream);
        }

        $stream->expect(TokenKind::RightParen);

        return new Group(new Pattern($elements));
    }

    protected function characterClass(TokenStream $stream): Atom
    {
        $stream->expect(TokenKind::LeftBracket);

        $negated = $stream->is(TokenKind::Circumflex);

        if ($negated) {
            $stream->next();
        }

        $members = [];

        while (! $stream->isEof() && $stream->current()->kind !== TokenKind::RightBracket) {
            $members[] = $this->characterClassMember($stream);
        }

        $stream->expect(TokenKind::RightBracket);

        return new CharacterClass($members, $negated);
    }

    protected function characterClassMember(TokenStream $stream): CharacterClassMember
    {
        $next = $stream->peek();

        if ($next?->kind !== TokenKind::Hyphen) {
            $token = $stream->current();

            $stream->next();

            return new Character($token->value);
        }

        $start = $stream->expect(TokenKind::Char)->value;

        $stream->expect(TokenKind::Hyphen);

        $end = $stream->expect(TokenKind::Char)->value;

        return new CharacterRange($start, $end);
    }

    protected function period(TokenStream $stream): Atom
    {
        $stream->next();

        return new Period;
    }

    protected function char(TokenStream $stream): Atom
    {
        $token = $stream->current();

        $stream->next();

        return new LiteralCharacter($token->value);
    }

    protected function quantifier(TokenStream $stream): ?Quantifier
    {
        if ($stream->isEof()) {
            return null;
        }

        if (! $stream->isAny(TokenKind::Question, TokenKind::Asterisk, TokenKind::Plus, TokenKind::LeftCurly)) {
            return null;
        }

        if ($stream->is(TokenKind::LeftCurly)) {
            return $this->curlyBracesQuantifier($stream);
        }

        $token = $stream->current()->kind;

        $stream->next();

        $modifier = $stream->isAny(TokenKind::Question, TokenKind::Plus) ? $stream->expectAny(TokenKind::Question, TokenKind::Plus)->kind : null;

        $quantifier = match ([$token, $modifier]) {
            [TokenKind::Question, null] => new ZeroOrOne(),
            [TokenKind::Question, TokenKind::Question] => new ZeroOrOne(greedy: false, lazy: true),
            [TokenKind::Question, TokenKind::Plus] => new ZeroOrOne(greedy: false, possessive: true),
            default => dd($token, $modifier),
        };

        return $quantifier;
    }

    protected function curlyBracesQuantifier(TokenStream $stream): Exactly|ExactlyOrMore|Between
    {
        $stream->expect(TokenKind::LeftCurly);

        $min = $this->integer($stream);

        if ($stream->is(TokenKind::RightCurly)) {
            $stream->next();

            return new Exactly($min);
        }

        $stream->expect(TokenKind::Comma);

        if ($stream->is(TokenKind::RightCurly)) {
            $stream->next();

            return new ExactlyOrMore($min);
        }

        $max = $this->integer($stream);

        $stream->expect(TokenKind::RightCurly);

        return new Between($min, $max);
    }

    protected function integer(TokenStream $stream): int
    {
        $char = $stream->expect(TokenKind::Char)->value;

        if (! ctype_digit($char)) {
            throw new Exception('Expected digit, got: '.$char);
        }

        if ($stream->current()?->kind !== TokenKind::Char) {
            return (int) $char;
        }

        while ($stream->current()?->kind === TokenKind::Char && ctype_digit($stream->current()?->value)) {
            $char .= $stream->expect(TokenKind::Char)->value;
        }

        return (int) $char;
    }
}
