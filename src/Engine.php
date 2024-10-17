<?php

namespace Phiki\Regex;

use Exception;
use Phiki\Regex\Ast\Pattern;
use Phiki\Regex\Evaluator\Evaluator;
use Phiki\Regex\Parser\Lexer;
use Phiki\Regex\Parser\Parser;

class Engine
{
    public function __construct(
        protected Lexer $lexer = new Lexer,
        protected Parser $parser = new Parser,
        protected bool $usePcre = true,
    ) {}

    public function match(string $pattern, string $subject, array &$matches = null, int $flags = 0, int $offset = 0): int|false
    {
        $parsed = $this->stringToPattern($pattern);

        if ($this->patternIsPcre($parsed)) {
            return preg_match($pattern, $subject, $matches, $flags, $offset);
        }

        $evaluator = new Evaluator();

        try {
            $matches = $evaluator->eval($parsed, $subject);
        } catch (Exception) {
            return false;
        }

        return count($matches) > 0 ? 1 : 0;
    }

    protected function patternIsPcre(Pattern $pattern): bool
    {
        // FIXME: Check for non-PCRE patterns here.
        return $this->usePcre && true;
    }

    protected function stringToPattern(string $pattern): Pattern
    {
        return $this->parser->parse($this->lexer->tokenise($this->stripDelimiters($pattern)));
    }

    protected function stripDelimiters(string $pattern): string
    {
        return trim($pattern, '/');
    }
}