# RegEx

This package contains a custom RegEx engine that supports a small set of features not present in PCRE2. Alongside the engine, it also implements a PCRE2-compliant parser.

When attempting to execute a RegEx that is fully compatible with PCRE2, it will use PHP's internal `preg_*()` functions. If something in a pattern isn't supported, it will fallback to the PHP-based engine.