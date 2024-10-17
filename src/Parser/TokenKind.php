<?php

namespace Phiki\Regex\Parser;

enum TokenKind
{
    case LeftParen;
    case RightParen;
    case LeftCurly;
    case RightCurly;
    case LeftBracket;
    case RightBracket;
    case Period;
    case Asterisk;
    case Plus;
    case Dollar;
    case Circumflex;
    case Pipe;
    case Question;
    case Comma;
    case Slash;
    case Equal;
    case Colon;
    case Hyphen;
    case EscapeSequence;
    case Char;
}