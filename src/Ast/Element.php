<?php

namespace Phiki\Regex\Ast;

class Element
{
    public function __construct(
        public Atom $atom,
        public ?Quantifier $quantifier = null,
    ) {}
}