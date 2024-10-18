<?php

namespace Phiki\Regex\Evaluator;

use Phiki\Regex\Ast\Pattern;

final class Evaluator
{
    public function eval(Pattern $pattern, string $subject): array
    {
        $state = new State($subject);

        return $this->match($pattern, $state);
    }

    private function match(Pattern $pattern, State $state): array
    {
        while (! $state->isEof()) {
            $position = $state->position();

            if (! $pattern->visit($state)) {
                $state->advance();

                continue;
            }

            $state->setRootCapture(substr($state->subject, $position, $state->position() - $position));

            break;
        }

        return $state->captures();
    }
}