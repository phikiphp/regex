<?php

namespace Phiki\Regex\Parser;

class Lexer
{
    /** @return Token[] */
    public function tokenize(string $input): array
    {
        return $this->tokenise($input);
    }

    /** @return Token[] */
    public function tokenise(string $input): array
    {
        $stream = new InputStream($input);
        $tokens = [];

        while (! $stream->isEof()) {
            $value = $stream->current();

            $kind = match ($value) {
                '(' => TokenKind::LeftParen,
                ')' => TokenKind::RightParen,
                '{' => TokenKind::LeftCurly,
                '}' => TokenKind::RightCurly,
                '[' => TokenKind::LeftBracket,
                ']' => TokenKind::RightBracket,
                '.' => TokenKind::Period,
                '*' => TokenKind::Asterisk,
                '+' => TokenKind::Plus,
                '$' => TokenKind::Dollar,
                '^' => TokenKind::Circumflex,
                '|' => TokenKind::Pipe,
                '?' => TokenKind::Question,
                ',' => TokenKind::Comma,
                '/' => TokenKind::Slash,
                '=' => TokenKind::Equal,
                ':' => TokenKind::Colon,
                '-' => TokenKind::Hyphen,
                '\\' => TokenKind::EscapeSequence,
                default => TokenKind::Char,
            };

            $position = $stream->position();

            if ($kind === TokenKind::EscapeSequence && $stream->nextIs(...$this->escapables())) {
                $value .= $stream->peek();
                $stream->next();
            }

            $tokens[] = new Token($kind, $value, $position);

            $stream->next();
        }

        return $tokens;
    }

    protected function escapables(): array
    {
        return [
            '^', '.', '[', ']', '$', '(', ')', '|', '*', '+', '?', '{', '\\',
        ];
    }
}
