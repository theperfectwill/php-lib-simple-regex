# Review Suggestions for src/SimpleRegex.php

Below are suggested improvements, optimizations, and security enhancements:

## Code Quality & Maintainability
- Add `declare(strict_types=1);` at the top of `SimpleRegex.php` for strict type enforcement.
- Adopt PSR-12 coding style: use 4-space indentation, consistent docblock formatting, and alphabetized imports.
- Add explicit return type declarations (e.g., `: void`) to private methods like `processCharacterClasses()`, `restoreTokens()`, etc.
- Mark constructor properties as `readonly` (PHP 8.1+) for `pattern`, `exclusionChar`, and `forceMatchAll` to enforce immutability.

## Bug Fixes & Correctness
- Fix `wrapWithDelimiters()`: on `forceMatchAll`, wrap as `'/^'.$regex.'$/'` instead of appending `'^'` at end.
- Validate the raw pattern early in the constructor by invoking `isValid()` to fail fast on invalid regex.
- Use `mb_strlen()` instead of `strlen()` when checking `MAX_PATTERN_LENGTH` to accurately count multibyte characters.

## Performance & Caching
- Apply Unicode (`u`) (and other needed) modifiers by default in delimiters (e.g., `'/…/u'`) to support multibyte matching and avoid silent failures.
- Introduce an input length limit (e.g., `MAX_INPUT_LENGTH`) in `doesMatch()` to prevent very large-string DoS attacks.
- Expose cache configuration via a PSR-6 or PSR-16 interface for greater interoperability with existing caching libraries.
- Optional: use `microtime(true)` for `last_used` timestamps to improve LRU precision.

## Security Enhancements
- Replace `error_get_last()` in `doesMatch()` with `preg_last_error()` and handle specific error codes (`PREG_BAD_UTF8_ERROR`, `PREG_BACKTRACK_LIMIT_ERROR`, etc.) to avoid catching unrelated PHP errors.
- Improve token placeholder generation (in `restoreTokens()`) using highly unique markers (e.g., UUIDs or non-printable characters) to avoid accidental collisions with user patterns.
- Enforce pattern validation on the final compiled regex to catch injection or malformed delimiters.
- Consider integrating a third-party ReDoS protection library or stricter checks (e.g., limiting nested quantifier depth) for advanced use cases.

## Testing & Documentation
- Add unit tests covering pattern conversion, matching, caching behavior, and error handling edge cases.
- Include integration tests to verify anchoring logic, modifier application, and alias resolution.
- Update documentation (README, Usage.md) to reflect new configuration options, strict types, and behavioral changes.
