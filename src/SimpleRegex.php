<?php

namespace ThePerfectWill\PhpLib;

/**
 * Simple Regex:
 * A lightweight PHP library that makes regular expressions more approachable and easier to use.
 * Simple Regex provides an intuitive syntax for common pattern matching tasks,
 * Making it for developers who want powerful pattern matching without the complexity of raw regex. * *
 *
 * @version 1.0.0.0
 *
 * @package ThePerfectWill\PhpLib
 */

class SimpleRegexException extends \Exception
{
}

class SimpleRegex
{
	/**
	 * Common pattern presets - organized by category
	 */
    
    // Email and Web
    public const PRESET_EMAIL = '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}';
    public const PRESET_URL = 'https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)';
    public const PRESET_IPV4 = '\b(?:\d{1,3}\.){3}\d{1,3}\b';
    public const PRESET_IPV6 = '(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))';
    
    // Date and Time
    public const PRESET_DATE_ISO8601 = '\d{4}-(?:0[1-9]|1[0-2])-(?:0[1-9]|[12][0-9]|3[01])';
    public const PRESET_TIME_24H = '(?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d(\.\d+)?)?';
    public const PRESET_DATETIME_ISO8601 = '\d{4}-(?:0[1-9]|1[0-2])-(?:0[1-9]|[12][0-9]|3[01])[T ](?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d(\.\d+)?)?(Z|[+-]\d{2}:?\d{2})?';
    
    // Files and Paths
    public const PRESET_FILENAME = '[^\\/\?%*:|"<>\x00-\x1F]+';
    public const PRESET_FILE_EXT = '\.[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*';
    public const PRESET_FILE_PATH = '(?:[^\\/\?%*:|"<>\x00-\x1F]+[\\/]?)+';
    
    // Image Patterns
    public const PRESET_IMAGE_EXT = '(?i)\.(jpe?g|png|gif|webp|svg|bmp|tiff?|heic|heif|ico)';
    public const PRESET_IMAGE_FILENAME = '[^\\/\?%*:|"<>\x00-\x1F]+\.(jpe?g|png|gif|webp|svg|bmp|tiff?|heic|heif|ico)(?i)';
    public const PRESET_IMAGE_WITH_DIMENSIONS = '[^\\/\?%*:|"<>\x00-\x1F]+-\\d+x\\d+\.(jpe?g|png|gif|webp|bmp|tiff?|heic|heif)(?i)';
    public const PRESET_IMAGE_WITH_RETINA = '[^\\/\?%*:|"<>\x00-\x1F]+@2x\.(jpe?g|png|gif|webp|bmp|tiff?|heic|heif)(?i)';
    public const PRESET_IMAGE_WITH_NUMBERS = '[^\\/\?%*:|"<>\x00-\x1F]+\\d{1,4}\.(jpe?g|png|gif|webp|bmp|tiff?|heic|heif)(?i)';
    
    // Code and Identifiers
    public const PRESET_UUID = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}';
    public const PRESET_HEX_COLOR = '#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})';
    public const PRESET_ALPHANUMERIC = '[A-Za-z0-9]+';
    public const PRESET_ALPHANUM_UNDERSCORE = '[A-Za-z0-9_]+';
    public const PRESET_ALPHANUM_HYPHEN = '[A-Za-z0-9-]+';
    public const PRESET_ALPHANUM_UNDERSCORE_HYPHEN = '[A-Za-z0-9_-]+';
    
    // Numbers
    public const PRESET_INTEGER = '\d+';
    public const PRESET_FLOAT = '\d+\.\d+';
    public const PRESET_POSITIVE_INT = '[1-9]\d*';
    public const PRESET_NEGATIVE_INT = '-[1-9]\d*';
    public const PRESET_PHONE_US = '\+?1?[\s.-]?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}';
    public const PRESET_ZIPCODE_US = '\d{5}(-\d{4})?';
    public const PRESET_SSN = '\d{3}-\d{2}-\d{4}';
    
    // Development
    public const PRESET_SEMVER = '(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?';
    public const PRESET_GIT_HASH = '[0-9a-f]{7,40}';

    // Pattern aliases for common use cases - organized by category
    private const PATTERN_ALIASES = [
        // Email and Web
        '@email' => self::PRESET_EMAIL,
        '@url' => self::PRESET_URL,
        '@ipv4' => self::PRESET_IPV4,
        '@ipv6' => self::PRESET_IPV6,
        
        // Date and Time
        '@date' => self::PRESET_DATE_ISO8601,
        '@time' => self::PRESET_TIME_24H,
        '@datetime' => self::PRESET_DATETIME_ISO8601,
        
        // Files and Paths
        '@filename' => self::PRESET_FILENAME,
        '@fileext' => self::PRESET_FILE_EXT,
        '@filepath' => self::PRESET_FILE_PATH,
        
        // Image Patterns
        '@image' => self::PRESET_IMAGE_FILENAME,
        '@image_ext' => self::PRESET_IMAGE_EXT,
        '@image_dim' => self::PRESET_IMAGE_WITH_DIMENSIONS,
        '@image_retina' => self::PRESET_IMAGE_WITH_RETINA,
        '@image_num' => self::PRESET_IMAGE_WITH_NUMBERS,
        
        // Code and Identifiers
        '@uuid' => self::PRESET_UUID,
        '@hexcolor' => self::PRESET_HEX_COLOR,
        '@alnum' => self::PRESET_ALPHANUMERIC,
        '@alnum_' => self::PRESET_ALPHANUM_UNDERSCORE,
        '@alnum-' => self::PRESET_ALPHANUM_HYPHEN,
        '@alnum_-' => self::PRESET_ALPHANUM_UNDERSCORE_HYPHEN,
        
        // Numbers
        '@int' => self::PRESET_INTEGER,
        '@float' => self::PRESET_FLOAT,
        '@posint' => self::PRESET_POSITIVE_INT,
        '@negint' => self::PRESET_NEGATIVE_INT,
        '@phone' => self::PRESET_PHONE_US,
        '@zipcode' => self::PRESET_ZIPCODE_US,
        '@ssn' => self::PRESET_SSN,
        
        // Development
        '@semver' => self::PRESET_SEMVER,
        '@githash' => self::PRESET_GIT_HASH,
    ];
	
    /**
     * Cache for compiled patterns to avoid recompiling the same pattern multiple times.
     * 
     * @var array<string, string>
     */
    private static array $patternCache = [];
    
    /**
     * Whether to use the pattern cache.
     * 
     * @var bool
     */
    private static bool $useCache = true;
	
	/**
	 * Maximum allowed length for the input pattern to prevent potential DoS attacks.
	 * This is a reasonable default that can be overridden if needed.
	 */
	private const MAX_PATTERN_LENGTH = 4096;

	/**
	 * Array of tokens (regex patterns) found in the $pattern.
	 * These are extracted out of the $pattern and stored here so that they can be restored later after other regex patterns have been processed.
	 *
	 * @var array
	 */
	private array $tokens = [];
	
	/**
	 * The original pattern provided to the constructor.
	 *
	 * @var string
	 */
	private string $originalPattern;

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
	public function __construct(private string $pattern, private string $exclusionChar = '/', private bool $forceMatchAll = false)
	{
		// Store original pattern for reference
		$this->originalPattern = $pattern;

		// Validate pattern is not empty
		if ('' === $pattern) {
			throw new \InvalidArgumentException('Pattern cannot be empty');
		}

		// Validate pattern length to prevent potential DoS attacks
		if (\strlen($pattern) > self::MAX_PATTERN_LENGTH) {
			throw new \InvalidArgumentException(sprintf(
				'Pattern length exceeds maximum allowed length of %d characters',
				self::MAX_PATTERN_LENGTH
			));
		}

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

		// Escape special regex characters in the exclusion character
		$this->exclusionChar = preg_quote($exclusionChar, '/');

		// Store the sanitized pattern
		$this->pattern = $pattern;
	}

	/**
	 * Process character classes in the pattern.
	 *
	 * @param string $regex
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
	 * Process alternation patterns.
	 *
	 * This function takes a pattern that is written in the form of `\{this,that\}` and converts it into a regex
	 * pattern in the form of `(?:this|that)`. This conversion is done to make the pattern compatible with pure
	 * regex syntax.
	 *
	 * The function uses a recursive regex pattern to match any number of comma-separated values inside the
	 * alternation brackets. The pattern is then replaced with a regex alternation pattern using the
	 * `preg_replace_callback` function.
	 *
	 * @param string $regex
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
	 * Process start/end anchors.
	 *
	 * This function is used to escape the start/end anchors when the forceMatchAll flag is set to false.
	 * This is done to prevent the regex engine from treating the start/end anchors as literal characters.
	 *
	 * @param string $regex
	 */
	private function processAnchors(string &$regex): void
	{
		// Combine both anchor replacements into a single operation
		$regex = str_replace(['\\^', '\\$'], ['^', '$'], $regex);
	}

	/**
	 * Process question mark patterns.
	 *
	 * @param string $regex
	 * @param string $exclusionChar
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
                '/(?:\\\\\?){2}/u'
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
     * Process predefined character classes like \d, \w, \s, etc.
     * 
     * @param string $regex
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
	 * Process wildcard patterns.
	 *
	 * @param string $regex
	 * @param string $exclusionChar
	 */
    private function processWildcards(string &$regex, string $exclusionChar): void
    {
		// Handle single question mark
		// `?` matches any single character that is not the exclusion character
		// $regex = preg_replace(
		// 	'/\\\\\?/u',
		// 	'[^' . $exclusionChar . ']',
		// 	$regex
		// );

		// @review->*? in last $exclusion_char
		//  which I think from testing should be done, or become optional if only need in certain scenarios
		//  like our deactivation rules plugin because if there is an ending ** the ending slash is still excluded
		// Example: /page-test/ will not be matched with **page-test** because it is still excluding the ending slash in the match
		// @todo->more testing, seems to break when matching 'equals" uri (an exact match comparison in our deactivation rules plugin)
		// `?` matches any single character that is not the exclusion character
		// ? also matches zero characters (i.e., it is optional)
		
        // Combine all wildcard replacements into a single operation using an array of patterns and replacements
        $replacements = [
            // Handle double or more asterisks (convert to non-greedy match any character including newlines)
            '/(?:\\\\\*){2,}/u' => '[\\s\\S]*?',
            // Handle single question mark (convert to non-greedy match any character except exclusion char)
            '/\\\\\?/u' => '[^' . $exclusionChar . ']*?',
            // Handle single asterisk (convert to match any single character except exclusion char)
            '/\\\\\*/u' => '[^' . $exclusionChar . ']'
        ];

        // Apply all replacements in a single pass
        $regex = preg_replace(
            array_keys($replacements),
            array_values($replacements),
            $regex
        );
    }

	/**
	 * Restore tokens in the pattern.
	 *
	 * @param string $regex
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
	 * @param string $regex
	 *
	 * @return string
	 */
	private function wrapWithDelimiters(string $regex): string
	{
		// Add the regex delimiters
		// Only add the start anchor if we're forcing a full match
		return '/' . $regex . ($this->forceMatchAll ? '^' : '') . '/';
	}

    /**
     * Check if the given pattern is a predefined pattern alias.
     *
     * @param string $pattern
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
     * Get a list of all available pattern aliases.
     *
     * @return array
     */
    public static function getAvailablePatterns(): array
    {
        return array_keys(self::PATTERN_ALIASES);
    }

	/**
     * Generate a cache key for the current pattern and configuration.
     *
     * @return string The cache key
     */
    private function getCacheKey(): string
    {
        return md5(serialize([
            'pattern' => $this->pattern,
            'exclusionChar' => $this->exclusionChar,
            'forceMatchAll' => $this->forceMatchAll
        ]));
    }

    /**
     * Enable or disable the pattern cache.
     *
     * @param bool $enabled Whether to enable the cache
     */
    public static function setCacheEnabled(bool $enabled): void
    {
        self::$useCache = $enabled;
    }

    /**
     * Clear the pattern cache.
     */
    public static function clearCache(): void
    {
        self::$patternCache = [];
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
	 * Convert the pattern to a proper regex pattern.
	 *
	 * This method takes the pattern and runs it through a series of processes to convert it to a valid regex pattern.
	 * It first quotes the pattern to escape any special regex characters.
	 * It then processes any character classes, alternations, question marks, and wildcards.
	 * Finally, it restores any tokens that were replaced during processing and wraps the pattern with delimiters.
	 *
	 * @param string|null $regex Reference to store the generated regex pattern
	 *
	 * @throws SimpleRegexException If the pattern is invalid
	 */
    public function toRegex(?string &$regex = null): void
    {
        $cacheKey = $this->getCacheKey();

        // Return cached pattern if available
        if (self::$useCache && isset(self::$patternCache[$cacheKey])) {
            $regex = self::$patternCache[$cacheKey];
            return;
        }

        // Ensure exclusion character is not empty
        // This is a sanity check to ensure that the exclusion character is valid
        // The exclusion character is used to escape special regex characters in the pattern
        // If it is empty, it will cause issues with the regex engine
        if (!isset($this->exclusionChar[0])) {
            throw new SimpleRegexException('Exclusion character cannot be empty');
        }

        // Combine multiple replacements into a single operation where possible
        $regex = preg_quote($this->pattern, '/');
        $exclusionChar = preg_quote($this->exclusionChar, '/');

        // Process patterns in optimal order to minimize string operations
        $patterns = [
            // Process character class shorthands first
            function() use (&$regex) { $this->processCharacterClassShorthands($regex); },
            // Then handle character classes as they might contain special characters
            function() use (&$regex) { $this->processCharacterClasses($regex); },
            // Process alternations
            function() use (&$regex) { $this->processAlternations($regex); },
            // Process anchors if needed
            function() use (&$regex) {
                if (!$this->forceMatchAll) {
                    $this->processAnchors($regex);
                }
            },
            // Process question marks and wildcards together
            function() use (&$regex) {
                $this->processQuestionMarks($regex, $this->exclusionChar);
                $this->processWildcards($regex, $this->exclusionChar);
            },
            // Process common patterns like \d, \w, etc.
            function() use (&$regex) { $this->processCharacterClassShorthands($regex); }
        ];

        // Execute all pattern processors with error handling
        foreach ($patterns as $processor) {
            try {
                $processor();
            } catch (\Exception $e) {
                throw new SimpleRegexException(
                    'Error processing pattern: ' . $e->getMessage(),
                    0,
                    $e
                );
            }
        }

        // Restore tokens
		// This is necessary because we replaced tokens with placeholders during processing.
		// We need to restore the original tokens now that processing is complete.
        $this->restoreTokens($regex);

        // Wrap with delimiters and store in cache
		// This is necessary because we need to wrap the pattern with delimiters so that it can.
        $regex = $this->wrapWithDelimiters($regex);

        // Cache the result if caching is enabled
        if (self::$useCache) {
            self::$patternCache[$cacheKey] = $regex;
        }
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
		$this->toRegex($regex);

		if (!self::isValid($regex)) {
			throw new SimpleRegexException('Invalid regex pattern');
		}

		return $regex;
	}

	/**
	 * Check if the given string matches the compiled regex pattern.
	 *
	 * @param string     $input    The string to check against the pattern
	 * @param array|null &$matches If provided, it will be filled with the results of the match
	 *
	 * @return bool True if the input matches the pattern, false otherwise
	 *
	 * @throws SimpleRegexException If the pattern is invalid
	 */
	public function doesMatch(string $input, ?array &$matches = null): bool
	{
		$regex  = $this->getPattern();
		$result = @preg_match($regex, $input, $matches);

		if (false === $result) {
			throw new SimpleRegexException('Error occurred during pattern matching');
		}

		return 1 === $result;
	}

	/**
	 * Static helper to create and convert a pattern in one call.
	 *
	 * This is a shortcut for creating an instance of the class and calling the `toRegex()` method.
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
			throw new SimpleRegexException('Invalid regex pattern');
		}

		return $regex;
	}

	/**
	 * Static helper to check if a string matches a pattern in one call.
	 *
	 * @param string     $pattern       The pattern to match against
	 * @param string     $input         The string to check
	 * @param array|null &$matches      If provided, it will be filled with the results of the match
	 * @param string     $exclusionChar Characters to exclude from wildcard matches
	 * @param bool       $forceMatchAll Whether to force start/end anchors
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
	 * Static helper to check if a string is a valid regular expression.
	 *
	 * This method checks the pattern against a set of known potentially dangerous
	 * constructs that could lead to ReDoS attacks, and also checks for nested
	 * quantifiers which can cause catastrophic backtracking.
	 *
	 * @param string $pattern The pattern to validate
	 *
	 * @return bool True if the pattern is valid, false otherwise
	 *
	 * @throws \InvalidArgumentException If the pattern is empty or invalid
	 */
	public static function isValid(string $pattern): bool
	{
		if ('' === $pattern) {
			throw new \InvalidArgumentException('Pattern cannot be empty');
		}

		// Check for potentially dangerous patterns that could lead to ReDoS
		if (preg_match('/(?:(?:\\.|\{(?:\d+,?\d*\})?|\[[^\]]*\]|\([^)]*\)|\|[^|]*\|\?\*\+\{\d+,?\d*\})/u', $pattern)) {
			// @Note->This is a simplified check - in a production environment, you might want to use
			// a more sophisticated approach or a dedicated library to detect dangerous patterns
			throw new \InvalidArgumentException('Pattern contains potentially dangerous constructs that could lead to ReDoS attacks');
		}

		// Check for nested quantifiers which can cause catastrophic backtracking
		if (preg_match('/(?:\*\+|\+\*|\?\*|\*\?|\+\?|\?\+|\{\d+,\d*\}\?|\{\d+,\d*\}\*|\{\d+,\d*\}\+)/', $pattern)) {
			throw new \InvalidArgumentException('Pattern contains nested quantifiers that could lead to performance issues');
		}

		// Check for patterns that could be too broad
		if (preg_match('/\*\.\*|\?\*\?|\*\.\*\?|\?\.\*\?/', $pattern)) {
			throw new \InvalidArgumentException('Pattern is too broad and could match too many inputs');
		}

		// Use a more robust validation approach that doesn't just check for syntax errors
		set_error_handler(function($errno, $errstr) {
			restore_error_handler();
			throw new \InvalidArgumentException($errstr, $errno);
		}, E_WARNING);

		try {
			// Test the pattern with a dummy string to ensure it compiles
			$result = @preg_match($pattern, '');
			restore_error_handler();

			// Check for compilation errors
			if (false === $result && PREG_NO_ERROR !== preg_last_error()) {
				throw new \InvalidArgumentException('Invalid regular expression: ' . $this->getPregError(preg_last_error()));
			}

			return true;
		} catch (\Throwable $e) {
			restore_error_handler();
			throw new \InvalidArgumentException('Invalid regular expression: ' . $e->getMessage());
		}
	}

	/**
	 * Get a human-readable error message for a given PREG error code.
	 *
	 * @param int $errorCode The error code from preg_last_error()
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
}
