<?php

namespace ThePerfectWill\PhpLib;

/**
 * Simple Regex:
 * A lightweight PHP library that makes regular expressions more approachable and easier to use.
 * Simple Regex provides an intuitive syntax for common pattern matching tasks,
 * Making it for developers who want powerful pattern matching without the complexity of raw regex. * * * * * * * * * * * * * * * *
 *
 * @version 1.0.0.0
 *
 * @package ThePerfectWill\PhpLib
 */
class SimpleRegexException extends \RuntimeException
{
	/**
	 * Creates a new exception with a message and code.
	 *
	 * @param string          $message  The error message
	 * @param int             $code     Error code
	 * @param \Throwable|null $previous Previous exception
	 */
	public function __construct(
		string $message,
		int $code = 0,
		?\Throwable $previous = null
	) {
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Creates a new exception for an invalid pattern.
	 *
	 * @param string          $pattern  The invalid pattern
	 * @param string|null     $message  Optional custom error message
	 * @param int             $code     Error code
	 * @param \Throwable|null $previous Previous exception
	 *
	 * @return self
	 */
	public static function forInvalidPattern(
		string $pattern,
		?string $message = null,
		int $code = 0,
		?\Throwable $previous = null
	): self {
		return new self(
			$message ?? \sprintf('Invalid regex pattern: %s', $pattern),
			$code,
			$previous
		);
	}

	/**
	 * Creates a new exception for a matching error.
	 *
	 * @param string          $error    The error message
	 * @param int             $code     Error code
	 * @param \Throwable|null $previous Previous exception
	 *
	 * @return self
	 */
	public static function forMatchingError(
		string $error,
		int $code = 0,
		?\Throwable $previous = null
	): self {
		return new self(
			\sprintf('Error occurred during pattern matching: %s', $error),
			$code,
			$previous
		);
	}

	/**
	 * Creates a new exception for an invalid argument.
	 *
	 * @param string          $message  The error message
	 * @param int             $code     Error code
	 * @param \Throwable|null $previous Previous exception
	 *
	 * @return self
	 */
	public static function forInvalidArgument(
		string $message,
		int $code = 0,
		?\Throwable $previous = null
	): self {
		return new self($message, $code, $previous);
	}

	/**
	 * Creates a new exception for a cache-related error.
	 *
	 * @param string          $message  The error message
	 * @param int             $code     Error code
	 * @param \Throwable|null $previous Previous exception
	 *
	 * @return self
	 */
	public static function forCacheError(
		string $message,
		int $code = 0,
		?\Throwable $previous = null
	): self {
		return new self(
			\sprintf('Cache error: %s', $message),
			$code,
			$previous
		);
	}
}

class SimpleRegex
{
	/**
	 * Common pattern presets - organized by category.
	 */

	// Email and Web
	public const PRESET_EMAIL = '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}';
	public const PRESET_URL   = 'https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)';
	public const PRESET_IPV4  = '\b(?:\d{1,3}\.){3}\d{1,3}\b';
	public const PRESET_IPV6  = '(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))';

	// Date and Time
	public const PRESET_DATE_ISO8601     = '\d{4}-(?:0[1-9]|1[0-2])-(?:0[1-9]|[12][0-9]|3[01])';
	public const PRESET_TIME_24H         = '(?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d(\.\d+)?)?';
	public const PRESET_DATETIME_ISO8601 = '\d{4}-(?:0[1-9]|1[0-2])-(?:0[1-9]|[12][0-9]|3[01])[T ](?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d(\.\d+)?)?(Z|[+-]\d{2}:?\d{2})?';

	// Files and Paths
	public const PRESET_FILENAME  = '[^\\/\?%*:|"<>\x00-\x1F]+';
	public const PRESET_FILE_EXT  = '\.[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*';
	public const PRESET_FILE_PATH = '(?:[^\\/\?%*:|"<>\x00-\x1F]+[\\/]?)+';

	// Image Patterns
	public const PRESET_IMAGE_EXT             = '(?i)\.(jpe?g|png|gif|webp|svg|bmp|tiff?|heic|heif|ico)';
	public const PRESET_IMAGE_FILENAME        = '[^\\/\?%*:|"<>\x00-\x1F]+\.(jpe?g|png|gif|webp|svg|bmp|tiff?|heic|heif|ico)(?i)';
	public const PRESET_IMAGE_WITH_DIMENSIONS = '[^\\/\?%*:|"<>\x00-\x1F]+-\\d+x\\d+\.(jpe?g|png|gif|webp|bmp|tiff?|heic|heif)(?i)';
	public const PRESET_IMAGE_WITH_RETINA     = '[^\\/\?%*:|"<>\x00-\x1F]+@2x\.(jpe?g|png|gif|webp|bmp|tiff?|heic|heif)(?i)';
	public const PRESET_IMAGE_WITH_NUMBERS    = '[^\\/\?%*:|"<>\x00-\x1F]+\\d{1,4}\.(jpe?g|png|gif|webp|bmp|tiff?|heic|heif)(?i)';

	// Code and Identifiers
	public const PRESET_UUID                       = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}';
	public const PRESET_HEX_COLOR                  = '#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})';
	public const PRESET_ALPHANUMERIC               = '[A-Za-z0-9]+';
	public const PRESET_ALPHANUM_UNDERSCORE        = '[A-Za-z0-9_]+';
	public const PRESET_ALPHANUM_HYPHEN            = '[A-Za-z0-9-]+';
	public const PRESET_ALPHANUM_UNDERSCORE_HYPHEN = '[A-Za-z0-9_-]+';

	// Numbers
	public const PRESET_INTEGER      = '\d+';
	public const PRESET_FLOAT        = '\d+\.\d+';
	public const PRESET_POSITIVE_INT = '[1-9]\d*';
	public const PRESET_NEGATIVE_INT = '-[1-9]\d*';
	public const PRESET_PHONE_US     = '\+?1?[\s.-]?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}';
	public const PRESET_ZIPCODE_US   = '\d{5}(-\d{4})?';
	public const PRESET_SSN          = '\d{3}-\d{2}-\d{4}';

	// Development
	public const PRESET_SEMVER   = '(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?';
	public const PRESET_GIT_HASH = '[0-9a-f]{7,40}';

	/**
	 * Maximum allowed length for the input pattern to prevent potential DoS attacks.
	 * This is a reasonable default that can be overridden if needed.
	 */
	private const MAX_PATTERN_LENGTH = 4096;

	// Pattern aliases
	private const PATTERN_ALIASES = [
		// Email and Web
		'@email' => self::PRESET_EMAIL,
		'@url'   => self::PRESET_URL,
		'@ipv4'  => self::PRESET_IPV4,
		'@ipv6'  => self::PRESET_IPV6,

		// Date and Time
		'@date'     => self::PRESET_DATE_ISO8601,
		'@time'     => self::PRESET_TIME_24H,
		'@datetime' => self::PRESET_DATETIME_ISO8601,

		// Files and Paths
		'@filename' => self::PRESET_FILENAME,
		'@fileext'  => self::PRESET_FILE_EXT,
		'@filepath' => self::PRESET_FILE_PATH,

		// Image Patterns
		'@image'        => self::PRESET_IMAGE_FILENAME,
		'@image_ext'    => self::PRESET_IMAGE_EXT,
		'@image_dim'    => self::PRESET_IMAGE_WITH_DIMENSIONS,
		'@image_retina' => self::PRESET_IMAGE_WITH_RETINA,
		'@image_num'    => self::PRESET_IMAGE_WITH_NUMBERS,

		// Code and Identifiers
		'@uuid'     => self::PRESET_UUID,
		'@hexcolor' => self::PRESET_HEX_COLOR,
		'@alnum'    => self::PRESET_ALPHANUMERIC,
		'@alnum_'   => self::PRESET_ALPHANUM_UNDERSCORE,
		'@alnum-'   => self::PRESET_ALPHANUM_HYPHEN,
		'@alnum_-'  => self::PRESET_ALPHANUM_UNDERSCORE_HYPHEN,

		// Numbers
		'@int'     => self::PRESET_INTEGER,
		'@float'   => self::PRESET_FLOAT,
		'@posint'  => self::PRESET_POSITIVE_INT,
		'@negint'  => self::PRESET_NEGATIVE_INT,
		'@phone'   => self::PRESET_PHONE_US,
		'@zipcode' => self::PRESET_ZIPCODE_US,
		'@ssn'     => self::PRESET_SSN,

		// Development
		'@semver'  => self::PRESET_SEMVER,
		'@githash' => self::PRESET_GIT_HASH,
	];

	/**
	 * Cache for compiled patterns to avoid recompiling the same pattern multiple times.
	 *
	 * @var array<string, array{regex: string, size: int, last_used: int, hits: int}>
	 */
	private static array $patternCache = [];

	/**
	 * Whether to use the pattern cache.
	 *
	 * @var bool
	 */
	private static bool $useCache = true;

	/**
	 * Maximum number of patterns to cache (prevent memory issues with large numbers of patterns).
	 *
	 * @var int
	 */
	private static int $maxCacheSize = 1000;

	/**
	 * Maximum size of input string before using streaming approach (in bytes).
	 *
	 * @var int
	 */
	private int $largeInputThreshold = 1024 * 1024; // 1MB

	/**
	 * Whether to use JIT compilation for regex patterns (PHP 7.0+).
	 *
	 * @var bool
	 */
	private bool $useJit = true;

	/**
	 * Track cache hits and misses for statistics.
	 *
	 * @var array{hits: int, misses: int}
	 */
	private array $cacheStats = ['hits' => 0, 'misses' => 0];

	/**
	 * Array of tokens (regex patterns) found in the $pattern.
	 * These are extracted out of the $pattern and stored here so that they can be restored later after other regex patterns have been processed.
	 *
	 * @var array
	 */
	private array $tokens = [];

	/** @var string The original pattern provided to the constructor. */
	private string $originalPattern;

	/** @var string The processed pattern (after aliases and trimming) */
	private string $pattern;

	/** @var string Characters to exclude from wildcard matches (regex-escaped) */
	private string $exclusionChar;

	/**
	 * Constructor.
	 *
	 * @param string $pattern       The pattern to match against
	 * @param string $exclusionChar Characters to exclude from wildcard matches
	 *                              (defaults to '/' which is the standard separator
	 *                              used in URLs and file paths)
	 * @param bool   $forceMatchAll Whether to force start/end anchors (^$)
	 *                              (defaults to false)
	 *
	 * @throws \InvalidArgumentException If any parameter is invalid
	 */
	public function __construct(string $pattern, string $exclusionChar = '/', private bool $forceMatchAll = false)
	{
		// Validate pattern is not empty
		if ('' === $pattern) {
			throw new \InvalidArgumentException('Pattern cannot be empty');
		}

		// Validate pattern length to prevent potential DoS attacks
		if (\strlen($pattern) > self::MAX_PATTERN_LENGTH) {
			throw new \InvalidArgumentException(\sprintf('Pattern length exceeds maximum allowed length of %d characters', self::MAX_PATTERN_LENGTH));
		}

		// Store original pattern for reference
		$this->originalPattern = $pattern;

		// Sanitize pattern by trimming whitespace
		$pattern = trim($pattern);

		// Check for pattern aliases and replace them
		if (str_starts_with($pattern, '@')) {
			$alias = strtolower(explode(' ', $pattern, 2)[0]);

			if (isset(self::PATTERN_ALIASES[$alias])) {
				$pattern = str_replace($alias, self::PATTERN_ALIASES[$alias], $pattern);
			}
		}

		// Validate exclusion character
		if ('' === $exclusionChar) {
			throw new \InvalidArgumentException('Exclusion character cannot be empty');
		}

		// Ensure exclusion character is a single character
		if (mb_strlen($exclusionChar) > 1) {
			throw new \InvalidArgumentException('Exclusion character must be exactly one character');
		}

		// Initialize properties
		$this->pattern       = $pattern;
		$this->exclusionChar = preg_quote($exclusionChar, '/');
	}

	/**
	 * Get a human-readable error message for a given PREG error code.
	 *
	 * This method provides detailed error messages for PCRE error codes, including
	 * both a short description and detailed information about the cause and potential
	 * solutions for each error.
	 *
	 * @param int $errorCode The error code from preg_last_error()
	 *
	 * @return string The human-readable error message with details
	 *
	 * @throws \InvalidArgumentException If the error code is not a valid PREG error code
	 *
	 * @example
	 * try {
	 *     $result = @preg_match('/invalid(/', 'test');
	 *     if (false === $result) {
	 *         $error = SimpleRegex::getPregErrorStatic(preg_last_error());
	 *         echo $error; // Outputs detailed error message
	 *     }
	 * } catch (\InvalidArgumentException $e) {
	 *     // Handle invalid error code
	 * }
	 */
	public static function getPregErrorStatic(int $errorCode): string
	{
		$messages = [
			PREG_INTERNAL_ERROR => [
				'short'    => 'Internal error',
				'details'  => 'An internal PCRE error occurred. This is likely a bug in PCRE itself.',
				'solution' => 'Please report this issue to the PHP maintainers.',
			],
			PREG_BACKTRACK_LIMIT_ERROR => [
				'short'    => 'Backtrack limit exhausted',
				'details'  => 'The pattern caused too much backtracking. This can happen with patterns that have ambiguous or inefficient quantifiers.',
				'solution' => 'Try to make your pattern more specific or use atomic grouping (e.g., (?>...)) to prevent excessive backtracking.',
			],
			PREG_RECURSION_LIMIT_ERROR => [
				'short'    => 'Recursion limit exhausted',
				'details'  => 'The pattern caused too much recursion. This can happen with recursive patterns or patterns with nested quantifiers.',
				'solution' => 'Try to simplify your pattern or increase the recursion limit using ini_set(\'pcre.recursion_limit\', ...).',
			],
			PREG_BAD_UTF8_ERROR => [
				'short'    => 'Malformed UTF-8 data',
				'details'  => 'The input string contains invalid UTF-8 data.',
				'solution' => 'Ensure your input is properly encoded in UTF-8. Use mb_convert_encoding() if needed.',
			],
			PREG_BAD_UTF8_OFFSET_ERROR => [
				'short'    => 'Invalid UTF-8 offset',
				'details'  => 'The offset did not correspond to the beginning of a valid UTF-8 code point.',
				'solution' => 'This usually happens when using multibyte characters with incorrect offsets. Ensure your offset points to the start of a UTF-8 character.',
			],
			PREG_JIT_STACKLIMIT_ERROR => [
				'short'    => 'JIT stack limit reached',
				'details'  => 'The Just-In-Time compiler stack limit was reached.',
				'solution' => 'This can happen with very complex patterns or large inputs. Try simplifying your pattern or increase the JIT stack size using ini_set(\'pcre.jit_stack_limit\', ...).',
			],
		];

		if (!isset($messages[$errorCode])) {
			throw new \InvalidArgumentException(\sprintf('Invalid PREG error code: %d', $errorCode));
		}

		$message = $messages[$errorCode];

		return \sprintf(
			"%s: %s\nSolution: %s",
			$message['short'],
			$message['details'],
			$message['solution']
		);
	}

	/**
	 * Check if the given pattern is a predefined pattern alias.
	 *
	 * @param string $pattern
	 *
	 * @return bool
	 */
	public static function isPatternAlias(string $pattern): bool
	{
		$pattern = trim($pattern);

		if (str_starts_with($pattern, '@')) {
			$alias = strtolower(explode(' ', $pattern, 2)[0]);

			return isset(self::PATTERN_ALIASES[$alias]);
		}

		return false;
	}

	/**
	 * Get the current cache size.
	 *
	 * @return int The number of cached patterns
	 */
	public static function getCacheSize(): int
	{
		return \count(self::$patternCache);
	}

	/**
	 * Configure the pattern cache.
	 *
	 * @param bool $enabled Whether to enable the cache
	 * @param int  $maxSize Maximum number of patterns to cache (default: 1000)
	 */
	public static function configureCache(bool $enabled, int $maxSize = 1000): void
	{
		self::$useCache     = $enabled;
		self::$maxCacheSize = max(1, $maxSize);
	}

	/**
	 * Convert a pattern to a regex.
	 *
	 * @param string $pattern       The pattern to convert
	 * @param string $exclusionChar Characters to exclude from wildcard matches
	 * @param bool   $forceMatchAll Whether to force start/end anchors
	 *
	 * @return string The compiled regex pattern
	 *
	 * @throws \InvalidArgumentException If any parameter is invalid
	 * @throws SimpleRegexException      If the pattern is invalid
	 */
	public static function convert(string $pattern, string $exclusionChar = '/', bool $forceMatchAll = false): string
	{
		$instance = new self($pattern, $exclusionChar, $forceMatchAll);
		$regex    = '';

		if (!$instance->toRegex($regex)) {
			throw SimpleRegexException::forInvalidPattern($pattern);
		}

		return $regex;
	}

	/**
	 * Check if a string matches a pattern using a one-off instance.
	 *
	 * @param string     $pattern       The pattern to match against
	 * @param string     $input         The string to check
	 * @param array|null &$matches      If provided, will be filled with the results of the match
	 * @param string     $exclusionChar Characters to exclude from wildcard matches (default: '/')
	 * @param bool       $forceMatchAll Whether to force start/end anchors (default: false)
	 *
	 * @return bool True if the input matches the pattern, false otherwise
	 *
	 * @throws \InvalidArgumentException If any parameter is invalid
	 * @throws SimpleRegexException      If the pattern is invalid
	 */
	public static function matches(string $pattern, string $input, ?array &$matches = null, string $exclusionChar = '/', bool $forceMatchAll = false): bool
	{
		$instance = new self($pattern, $exclusionChar, $forceMatchAll);

		return $instance->doesMatch($input, $matches);
	}

	/**
	 * Check if a string is a valid regular expression pattern.
	 * This method performs several security and validity checks:
	 * 1. Ensures the pattern is not empty
	 * 2. Validates the pattern length is within safe limits
	 * 3. Checks for known dangerous patterns that could lead to ReDoS attacks
	 * 4. Attempts to compile the pattern to catch syntax errors.
	 *
	 * @param string $pattern The pattern to validate
	 *
	 * @return bool True if the pattern is valid, false if it contains dangerous constructs
	 *
	 * @throws SimpleRegexException If the pattern is empty, too long, or contains syntax errors
	 *
	 * @example
	 * // Basic usage
	 * if (SimpleRegex::isValid('/^[a-z]+$/i')) {
	 *     echo 'Pattern is valid';
	 * }
	 *
	 * // Catching exceptions
	 * try {
	 *     SimpleRegex::isValid('(a+)+'); // Potentially dangerous pattern
	 * } catch (SimpleRegexException $e) {
	 *     echo 'Invalid pattern: ' . $e->getMessage();
	 * }
	 */
	public static function isValid(string $pattern): bool
	{
		// Check for empty pattern
		if ('' === $pattern) {
			throw SimpleRegexException::forInvalidArgument('Pattern cannot be empty');
		}

		// Check pattern length (prevent DoS through extremely large patterns)
		if (\strlen($pattern) > self::MAX_PATTERN_LENGTH) {
			throw SimpleRegexException::forInvalidPattern($pattern, \sprintf('Pattern length (%d) exceeds maximum allowed length of %d characters', \strlen($pattern), self::MAX_PATTERN_LENGTH));
		}

		// Check for potentially dangerous patterns that can lead to ReDoS
		$dangerousPatterns = [
			// Nested quantifiers, e.g., (a+)+ or (a*)*
			'/\(((?!\?)[^()]*)[*+?]\)[\s\S]*[*+?]/u' => 'Nested quantifiers can cause catastrophic backtracking',

			// Nested curly-brace quantifiers, e.g., a{1{2}}
			'/(\{[^}]*\{)/u' => 'Nested curly-brace quantifiers are not allowed',

			// Recursion, which can be very costly
			'/(\(\?R\))/u' => 'Recursive patterns are not allowed',

			// Backreferences to groups with quantifiers, e.g., (a+)\1
			'/\\((\d+)\\)[^*+?]*[*+?]/u' => 'Backreferences to groups with quantifiers can cause excessive backtracking',

			// Quantifiers inside lookarounds, e.g., (?=(a+))
			'/\(\?[=!<][^)]*[*+]\)/u' => 'Quantifiers inside lookarounds can be dangerous',

			// Potentially exponential backtracking patterns
			'/(.)*\1{10,}/u' => 'Potentially exponential backtracking pattern detected',
			'/(?:(a+)+)$/u'  => 'Nested quantifiers can cause catastrophic backtracking',

			// Empty character classes
			'/\[\s*\]/u' => 'Empty character class is not allowed',

			// Unescaped forward slashes in patterns without delimiters
			'/(?<!\\)\//u' => 'Unescaped forward slashes must be escaped',
		];

		foreach ($dangerousPatterns as $dangerousPattern => $errorMessage) {
			if (preg_match($dangerousPattern, $pattern)) {
				return false;
			}
		}

		// Use error handler to catch warnings from preg_match
		set_error_handler(static function ($errno, $errstr) use ($pattern) {
			restore_error_handler();

			throw SimpleRegexException::forInvalidPattern($pattern, $errstr, $errno);
		}, E_WARNING);

		try {
			// Attempt to compile the pattern to check for syntax errors
			$result = @preg_match($pattern, '');
			restore_error_handler();

			// A result of `false` indicates a compilation error
			if (false === $result) {
				throw SimpleRegexException::forInvalidPattern($pattern, self::getPregErrorStatic(preg_last_error()));
			}

			return true;
		} catch (\Throwable $e) {
			restore_error_handler();

			throw $e;
		}
	}

	/**
	 * Enable or disable the pattern cache (legacy method).
	 *
	 * @param bool $enabled Whether to enable the cache
	 */
	public function setCacheEnabled(bool $enabled): void
	{
		$this->configureCache($enabled);
	}

	/**
	 * Clear the pattern cache.
	 *
	 * @param bool $shrink If true, reduce cache size instead of clearing it completely
	 */
	public function clearCache(bool $shrink = false): void
	{
		if ($shrink && !empty(self::$patternCache)) {
			// Sort by last used timestamp (oldest first)
			uasort(self::$patternCache, function ($a, $b) {
				return $a['last_used'] <=> $b['last_used'];
			});
			// Remove oldest half of the cache (preserve keys)
			$keys        = array_keys(self::$patternCache);
			$removeCount = (int) (\count($keys) / 2);

			foreach (\array_slice($keys, 0, $removeCount) as $key) {
				unset(self::$patternCache[$key]);
			}
		} else {
			self::$patternCache = [];
		}

		// Reset cache statistics
		$this->cacheStats = ['hits' => 0, 'misses' => 0];
	}

	/**
	 * Convert a pattern to a regex. *.
	 *
	 * @param string|null &$regex If provided, will be filled with the compiled regex pattern
	 *
	 * @return bool True if the pattern was successfully compiled, false otherwise
	 *
	 * @throws SimpleRegexException If an error occurs during pattern processing
	 */
	public function toRegex(?string &$regex = null): bool
	{
		$cacheKey = $this->getCacheKey();

		if ($this->useCache && isset(self::$patternCache[$cacheKey])) {
			self::$patternCache[$cacheKey]['hits']      = (self::$patternCache[$cacheKey]['hits'] ?? 0) + 1;
			self::$patternCache[$cacheKey]['last_used'] = time();
			$regex                                      = self::$patternCache[$cacheKey]['regex'];

			return true;
		}

		$regex         = preg_quote($this->pattern, '/');
		$exclusionChar = preg_quote($this->exclusionChar, '/');

		// Process patterns in optimal order to minimize string operations
		$patterns = [
			// Process character class shorthands first
			function () use (&$regex) {$this->processCharacterClassShorthands($regex); },
			// Then handle character classes as they might contain special characters
			function () use (&$regex) {$this->processCharacterClasses($regex); },
			// Process alternations
			function () use (&$regex) {$this->processAlternations($regex); },
			// Process anchors if needed
			function () use (&$regex) {
				if (!$this->forceMatchAll) {
					$this->processAnchors($regex);
				}
			},
			// Process question marks and wildcards together
			function () use (&$regex, $exclusionChar) {
				$this->processQuestionMarks($regex, $exclusionChar);
				$this->processWildcards($regex, $exclusionChar);
			},
			// Process common patterns like \d, \w, etc.
			function () use (&$regex) {$this->processCharacterClassShorthands($regex); },
		];

		// Execute all pattern processors with error handling
		foreach ($patterns as $processor) {
			try {
				$processor();
			} catch (\Exception $e) {
				throw new SimpleRegexException('Error processing pattern: ' . $e->getMessage(), 0, $e);
			}
		}

		// Restore tokens
		// This is necessary because we replaced tokens with placeholders during processing.
		// We need to restore the original tokens now that processing is complete.
		$this->restoreTokens($regex);

		// Wrap with delimiters
		$regex = $this->wrapWithDelimiters($regex);

		// Cache the result if caching is enabled
		if ($this->useCache) {
			// Enforce max cache size by removing least recently used entries
			if (\count(self::$patternCache) >= $this->maxCacheSize) {
				// Sort by last_used (oldest first)
				uasort(self::$patternCache, function ($a, $b) {
					return $a['last_used'] <=> $b['last_used'];
				});

				// Remove oldest entries until under maxCacheSize - 1 (to make space for the new one)
				while (\count(self::$patternCache) >= $this->maxCacheSize) {
					array_shift(self::$patternCache);
				}
			}
			// Store pattern with metadata
			self::$patternCache[$cacheKey] = [
				'regex'     => $regex,
				'size'      => \strlen($regex),
				'last_used' => time(),
				'hits'      => 1,
				'misses'    => 0,
			];
		}

		return true;
	}

	/**
	 * Get the last generated regex pattern.
	 *
	 * @return string The compiled regex pattern
	 *
	 * @throws SimpleRegexException If the pattern is invalid or hasn't been compiled yet
	 */
	public function getPattern(): string
	{
		$regex = '';

		if (!$this->toRegex($regex)) {
			throw SimpleRegexException::forInvalidPattern($this->pattern);
		}

		if (!self::isValid($regex)) {
			throw SimpleRegexException::forInvalidPattern($this->pattern);
		}

		return $regex;
	}

	/**
	 * Check if the input matches the compiled regex pattern.
	 *
	 * @param string     $input    The input string to check against the pattern
	 * @param array|null &$matches If provided, will be filled with the results of the match
	 *
	 * @return bool True if the input matches the pattern, false otherwise
	 *
	 * @throws SimpleRegexException If an error occurs during pattern matching
	 */
	public function doesMatch(string $input, ?array &$matches = null): bool
	{
		try {
			$regex  = $this->getPattern();
			$result = @preg_match($regex, $input, $matches);

			if (false === $result) {
				throw SimpleRegexException::forMatchingError(self::getPregErrorStatic(preg_last_error()));
			}

			return 1 === $result;
		} catch (\Throwable $e) {
			throw SimpleRegexException::forMatchingError($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	 * Process character classes in the pattern.
	 *
	 * Converts custom character class syntax (e.g. \[abc\]) into valid regex character classes.
	 *
	 * @param string &$regex The regex pattern (by reference)
	 */
	private function processCharacterClasses(string &$regex): void
	{
		// Convert `\[seq\]` and/or `\[\!seq\]` into `[seq]` and `[^seq]` for pure regex.
		// Converts to character class.
		$regex = preg_replace_callback(
			'/\\\\\[((?:(?:[^[\]]+)|(?R))*)\\\\\]/u',
			function ($matches) {
				// Check if the character class is a negated character class
				$content = 0 === mb_strpos($matches[1], '\\!') ?
				// If it is, remove the negation indicator and add the caret
				'^' . mb_substr($matches[1], 2) :
				// Otherwise, just use the character class as is
				$matches[1];

				// Replace any escaped hyphens with a hyphen
				// This is to handle cases where the hyphen is not actually meant to be a range indicator
				$content        = preg_replace('/([a-z0-9])\\\\\-([a-z0-9])/u', '${1}-${2}', $content);
				$this->tokens[] = '[' . $content . ']';

				// Replace the matched pattern with a placeholder, which will be restored later

				return '|%#%|' . (\count($this->tokens) - 1) . '|%#%|';
			},
			$regex
		);
	}

	/**
	 * Process alternation patterns in the pattern.
	 *
	 * Converts custom alternation syntax (e.g. \{foo,bar\}) into valid regex alternation (|).
	 *
	 * @param string &$regex The regex pattern (by reference)
	 */
	private function processAlternations(string &$regex): void
	{
		// Convert `\{this,that\}` into `(?:this|that)` for pure regex.
		// Converts to `(a|b|c)` alternation.
		$regex = preg_replace_callback(
			'/\\\\\{((?:(?:[^{}]+)|(?R))*)\\\\\}/u',
			function ($matches) {
				// Replace the alternation brackets with a regex alternation pattern and escape any commas with a backslash.
				return '(?:' . str_replace(['\\{', '\\}', ','], ['(?:', ')', '|'], $matches[1]) . ')';
			},
			$regex
		);
	}

	/**
	 * Process start/end anchors in the pattern.
	 *
	 * Escapes anchors when forceMatchAll is false to avoid treating them as literal characters.
	 *
	 * @param string &$regex The regex pattern (by reference)
	 */
	private function processAnchors(string &$regex): void
	{
		// Combine both anchor replacements into a single operation
		$regex = str_replace(['\\^', '\\$'], ['^', '$'], $regex);
	}

	/**
	 * Process question mark patterns in the pattern.
	 *
	 * Handles sequences of question marks and replaces them with appropriate regex for matching.
	 *
	 * @param string &$regex        The regex pattern (by reference)
	 * @param string $exclusionChar The character to exclude from wildcard matches
	 */
	private function processQuestionMarks(string &$regex, string $exclusionChar): void
	{
		// Handle three or more question marks
		// Because ??? (or more) should be treated like `?` instead of as a partial `??` operator.
		// The `preg_replace_callback` is used instead of `preg_replace` because the replacement string
		// needs to be built dynamically based on the length of the matched string.
		// Combine all question mark replacements into a single operation
		$regex = preg_replace_callback(
			[
				// Match three or more question marks and replace with exact length matcher
				'/(?:\\\\\?){3,}/u',
				// Match exactly two question marks and replace with any character matcher
				'/(?:\\\\\?){2}/u',
			],
			function ($matches) use ($exclusionChar) {
				$length = \strlen(str_replace('\\', '', $matches[0]));

				// For three or more question marks, use exact length matching
				if ($length >= 3) {
					return '[^' . $exclusionChar . ']{' . $length . '}';
				}

				// For exactly two question marks, match any character including newlines

				return '[\\s\\S]';
			},
			$regex
		);
	}

	/**
	 * Process predefined character class shorthands in the pattern.
	 *
	 * Converts \d, \w, \s, etc. to their regex equivalents.
	 *
	 * @param string &$regex The regex pattern (by reference)
	 */
	private function processCharacterClassShorthands(string &$regex): void
	{
		// Convert common regex character class shorthands
		$replacements = [
			'\\\\d' => '[0-9]',
			'\\\\D' => '[^0-9]',
			'\\\\w' => '[a-zA-Z0-9_]',
			'\\\\W' => '[^a-zA-Z0-9_]',
			'\\\\s' => '[\\s]',
			'\\\\S' => '[^\\s]',
			'\\\\b' => '\\b',
			'\\\\B' => '\\B',
		];

		$regex = strtr($regex, $replacements);
	}

	/**
	 * Process wildcard patterns in the pattern.
	 *
	 * Handles asterisks and question marks, converting them to regex wildcards.
	 *
	 * @param string &$regex        The regex pattern (by reference)
	 * @param string $exclusionChar The character to exclude from wildcard matches
	 */
	private function processWildcards(string &$regex, string $exclusionChar): void
	{
		// <KEEP>
		// Handle single question mark
		// `?` matches any single character that is not the exclusion character
		// $regex = preg_replace(
		//     '/\\\\\?/u',
		//     '[^' . $exclusionChar . ']',
		//     $regex
		// );

		// @review->*? in last $exclusion_char
		//  which I think from testing should be done, or become optional if only need in certain scenarios
		//  like our deactivation rules plugin because if there is an ending ** the ending slash is still excluded
		// Example: /page-test/ will not be matched with **page-test** because it is still excluding the ending slash in the match
		// @todo->more testing, seems to break when matching 'equals" uri (an exact match comparison in our deactivation rules plugin)
		// `?` matches any single character that is not the exclusion character
		// ? also matches zero characters (i.e., it is optional)
		// </KEEP>

		// Combine all wildcard replacements into a single operation using an array of patterns and replacements
		$replacements = [
			// `**` matches any characters including newlines (non-greedy)
			'/(?:\\\*){2,}/u' => '[\\s\\S]*?',
			// `?` matches any character except the exclusion character (non-greedy)
			'/\\\?/u' => '[^' . $exclusionChar . ']*?',
			// `*` matches a single character except the exclusion character
			'/\\\*/u' => '[^' . $exclusionChar . ']',
		];

		// Apply all replacements in a single pass
		$regex = preg_replace(array_keys($replacements), array_values($replacements), $regex);
	}

	/**
	 * Restore tokens in the pattern.
	 *
	 * Replaces placeholder tokens in the regex with their original values.
	 *
	 * @param string &$regex The regex pattern (by reference)
	 */
	private function restoreTokens(string &$regex): void
	{
		if (empty($this->tokens)) {
			return;
		}

		// Build a single replacement array for all tokens
		$replacements = [];

		foreach ($this->tokens as $token => $brackets) {
			$replacements['|%#%|' . $token . '|%#%|'] = $brackets;
		}

		// Apply all replacements at once
		$regex = strtr($regex, $replacements);
	}

	/**
	 * Wrap the final regex with delimiters.
	 *
	 * Adds regex delimiters and applies start anchor if forceMatchAll is enabled.
	 *
	 * @param string $regex The regex pattern
	 *
	 * @return string The delimited regex pattern
	 */
	private function wrapWithDelimiters(string $regex): string
	{
		// Add the regex delimiters
		// Only add the start anchor if we're forcing a full match
		return '/' . ($this->forceMatchAll ? '^' : '') . $regex . '/';
	}

	/**
	 * Get a human-readable error message for a given PREG error code.
	 *
	 * @param int $errorCode The error code from preg_last_error()
	 *
	 * @return string The human-readable error message
	 */
	private function getPregError(int $errorCode): string
	{
		switch ($errorCode) {
			case PREG_INTERNAL_ERROR:
				return 'Internal error';

			case PREG_BACKTRACK_LIMIT_ERROR:
				return 'Backtrack limit exhausted';

			case PREG_RECURSION_LIMIT_ERROR:
				return 'Recursion limit exhausted';

			case PREG_BAD_UTF8_ERROR:
				return 'Malformed UTF-8 data';

			case PREG_BAD_UTF8_OFFSET_ERROR:
				return 'The offset didn\'t correspond to the beginning of a valid UTF-8 code point';

			case PREG_JIT_STACKLIMIT_ERROR:
				return 'PCRE JIT stack limit reached';

			default:
				return 'Unknown error';
		}
	}

	/**
	 * Generate a cache key for the current pattern and options.
	 *
	 * @return string The cache key
	 */
	private function getCacheKey(): string
	{
		return md5($this->originalPattern . $this->exclusionChar . ($this->forceMatchAll ? '1' : '0'));
	}
}
