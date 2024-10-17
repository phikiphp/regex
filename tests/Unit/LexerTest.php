<?php

use Phiki\Regex\Parser\Lexer;
use Phiki\Regex\Parser\TokenKind;

it('produces valid tokens', function () {
    $lexer = new Lexer;
    $tokens = $lexer->tokenise('(){}[].*+$^|?,/=:\\$abc');

    expect($tokens)
        ->toHaveCount(21)
        ->sequence(
            fn ($token) => $token->kind->toBe(TokenKind::LeftParen),
            fn ($token) => $token->kind->toBe(TokenKind::RightParen),
            fn ($token) => $token->kind->toBe(TokenKind::LeftCurly),
            fn ($token) => $token->kind->toBe(TokenKind::RightCurly),
            fn ($token) => $token->kind->toBe(TokenKind::LeftBracket),
            fn ($token) => $token->kind->toBe(TokenKind::RightBracket),
            fn ($token) => $token->kind->toBe(TokenKind::Period),
            fn ($token) => $token->kind->toBe(TokenKind::Asterisk),
            fn ($token) => $token->kind->toBe(TokenKind::Plus),
            fn ($token) => $token->kind->toBe(TokenKind::Dollar),
            fn ($token) => $token->kind->toBe(TokenKind::Circumflex),
            fn ($token) => $token->kind->toBe(TokenKind::Pipe),
            fn ($token) => $token->kind->toBe(TokenKind::Question),
            fn ($token) => $token->kind->toBe(TokenKind::Comma),
            fn ($token) => $token->kind->toBe(TokenKind::Slash),
            fn ($token) => $token->kind->toBe(TokenKind::Equal),
            fn ($token) => $token->kind->toBe(TokenKind::Colon),
            fn ($token) => $token->kind->toBe(TokenKind::EscapeSequence),
            fn ($token) => $token->kind->toBe(TokenKind::Char),
            fn ($token) => $token->kind->toBe(TokenKind::Char),
            fn ($token) => $token->kind->toBe(TokenKind::Char),
        );
});
