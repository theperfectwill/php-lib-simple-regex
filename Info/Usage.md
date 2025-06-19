# Simple Regex - Usage

[Back to ReadMe](../ReadMe.md) | [Roadmap](Info/Roadmap.md) | [Get Support](https://github.com/theperfectwill/php-lib-simple-regex/issues)

## Table of Contents

- [Simple Regex Explanation](#simple-regex-explanation)
- [Usage Overview](#usage-overview)
  - [Common Cases](#common-cases)
  - [URL Routing](#url-routing)
  - [File Validation](#file-validation)
  - [Form Validation](#form-validation)
  - [API Versioning](#api-versioning)
- [Pattern Examples](#pattern-examples)
  - [URL Patterns](#url-patterns)
  - [Image Patterns](#image-patterns)
  - [File Patterns](#file-patterns)
  - [System Patterns](#system-patterns)
  - [String Patterns](#string-patterns)
  - [Date / Number Patterns](#date--number-patterns)
  - [Development Patterns](#development-patterns)
  - [Identifiers / Code Patterns](#identifiers--code-patterns)
- [Pattern Aliases](#pattern-aliases)
  - [Available Pattern Aliases](#available-pattern-aliases)
  - [Pattern Alias Usage](#pattern-alias-usage)
  - [Combining Aliases with Custom Patterns](#combining-aliases-with-custom-patterns)
  - [Getting All Available Aliases](#getting-all-available-aliases)

## Simple Regex Explanation

- `**` Matches zero or more characters that are not a /
- `*` Matches zero or more characters of any kind
- `?` Matches exactly one character that is not a /
- `??` Matches exactly one character of any kind
- `[abc]` Matches exactly one character: a, b, or c
- `[a-z0-9]` Matches exactly one character: a thru z or 0 thru 9
- `[!abc]` A leading ! inside [] negates; i.e., anything that is not: a, b, or c
- `{abc,def}` Matches the fragment abc or def (one or the other)
- `{abc,def,}` Matches abc, def or nothing; i.e., an optional match
- `{abc,!def}` Matches abc, or not def; i.e., negates an optional match
- `{,}` Matches a / followed by zero or more characters, or nothing
- `[*?[]!{},^$]` Matches a literal special character (one of: *?[]!{},^$)

## Usage Overview

```php
use ThePerfectWill\PhpLib\SimpleRegex;

// 1. Static conversion (quick one-liner)
$regex = SimpleRegex::convert('*.php'); // Matches any PHP file

// 2. Static conversion with options
$regex = SimpleRegex::convert(
    '*.txt', // Pattern
    '/', // Exclusion character (defaults to '/')
    true // Force full string matching (adds ^ and $)
);

// 3. Object-oriented usage with pattern validation
$simpleRegex = new SimpleRegex('*.{jpg,png,gif}');

// 4. Get regex pattern (throws on invalid pattern)
try {
    $regex = $simpleRegex->getPattern();
    echo "Generated regex: " . $regex . "\n";
} catch (\Exception $e) {
    echo "Invalid pattern: " . $e->getMessage() . "\n";
}

// 5. Check if a string matches the pattern
$matches = [];
if ($simpleRegex->doesMatch('image.jpg', $matches)) {
    echo "Pattern matches!\n";
    print_r($matches); // Shows full match and capture groups
}

// 6. Static matching (one-liner)
if (SimpleRegex::matches('user-*.{jpg,png}', 'user-profile.png')) {
    echo "User image matched!\n";
}

// 7. Validate a regex pattern
if (SimpleRegex::isValid('/^[a-z0-9]+$/i')) {
    echo "Pattern is valid\n";
}

// 8. Using toRegex() to get the regex pattern
$regex = '';
$simpleRegex->toRegex($regex);
echo "Regex pattern: " . $regex . "\n";

// 9. Working with capture groups
$matcher = new SimpleRegex('user/([0-9]+)/profile');
if ($matcher->doesMatch('user/123/profile', $matches)) {
    $userId = $matches[1]; // '123'
    echo "User ID: $userId\n";
}

// 10. Complex pattern with multiple options
$complex = new SimpleRegex('{admin,user}/*.{php,html}', '/', true);
if ($complex->doesMatch('admin/dashboard.php')) {
    echo "Admin dashboard accessed\n";
}
```

## Common Cases

#### URL Routing
```php
$route = '/blog/post/123';
$pattern = '/blog/post/[0-9]+';

if (SimpleRegex::matches($pattern, $route)) {
    // Handle blog post route
}
```

#### File Validation
```php
$allowedFiles = new SimpleRegex('*.{jpg,jpeg,png,gif}');
$filename = 'profile.jpg';

if ($allowedFiles->doesMatch($filename)) {
    // Process image file
}
```

#### Form Validation
```php
$emailPattern = new SimpleRegex('*@*.*');
if (!$emailPattern->doesMatch($_POST['email'])) {
    echo "Please enter a valid email address\n";
}
```

#### API Versioning
```php
$apiPath = '/api/v2/users';
if (SimpleRegex::matches('/api/v[0-9]+/*', $apiPath)) {
    // Handle API request
}
```

## Pattern Aliases

### URL Patterns
- `/blog/*` - Matches all blog post URLs
- `/products/[0-9]+` - Matches product detail pages with numeric IDs
- `*.[a-z]{2,4}` - Matches common file extensions (2-4 letters)
- `*?page=[0-9]+` - Matches pagination URLs
- `/user/[a-zA-Z0-9_]{3,20}` - Matches user profile URLs with 3-20 alphanumeric chars
- `/category/[a-z-]+` - Matches category URLs with lowercase letters and hyphens
- `/api/v[0-9]+/*` - Matches API endpoints with version numbers
- `*.{com,org,net}/*` - Matches URLs by top-level domain
- `/search?q=*` - Matches search query URLs

### Image Patterns
- `image-??.jpg` - Matches 'image-01.jpg' but not 'image-1.jpg'
- `*.{jpg,jpeg,png,gif}` - Matches common image file extensions (case-sensitive)

### File Patterns
- `*.php` - Matches any PHP file in current directory
- `!(*.min).js` - Matches .js files but not .min.js files

### System Patterns
- `{admin,dashboard}/**` - Matches all files in 'admin' or 'dashboard' directories
- `{wp-admin}/**` - Matches all files in the default Wordpress admin path

### String Patterns
- `[A-Za-z0-9_-]*` - Matches alphanumeric strings with underscores and hyphens
- `[A-Z][a-z]*` - Matches words starting with uppercase letter (e.g., 'Hello', 'World')
- `[!a-z]*.php` - Matches PHP files not starting with a letter

### Date / Number Patterns
- `[0-9][0-9][0-9].txt` - Matches three digits followed by .txt (e.g., '001.txt')
- `202[0-9]-[01][0-9]-[0-3][0-9]` - Matches dates in YYYY-MM-DD format (e.g., '2023-12-31')
- `[0-9]{3}-[0-9]{2}-[0-9]{4}` - Matches US Social Security Numbers (e.g., '123-45-6789')

### Development Patterns
- `*.{test,spec}.{js,ts}` - Matches test files (e.g., 'app.test.js', 'utils.spec.ts')
- `*.min.{js,css}` - Matches minified JavaScript or CSS files
- `*.{dev,staging,prod}.{env,config}` - Matches environment configuration files

### Identifiers / Code Patterns
- `[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}` - Matches UUIDs
- `[A-Z]{2,4}[0-9]{4,6}` - Matches alphanumeric codes (e.g., 'ABC1234')
- `#?[0-9a-fA-F]{3,6}` - Matches hex color codes (with optional #)

## Using Pattern Aliases

SimpleRegex provides several predefined pattern aliases that you can use for common validation tasks. These aliases start with `@` and can be used directly in your patterns.

### Available Pattern Aliases

#### Email and Web
- `@email` - Standard email address format
- `@url` - Web URL (http/https)
- `@ipv4` - IPv4 address
- `@ipv6` - IPv6 address

#### Date and Time
- `@date` - ISO 8601 date (YYYY-MM-DD)
- `@time` - 24-hour time format (HH:MM:SS)
- `@datetime` - ISO 8601 datetime (YYYY-MM-DD HH:MM:SS)

#### Files and Paths
- `@filename` - Valid filename (alphanumeric with some special chars)
- `@fileext` - File extension (alphanumeric, 1-10 chars)
- `@filepath` - File path (supports both Unix and Windows formats)

#### Image Patterns
- `@image` - Common image filenames with dimensions/retina
- `@image_ext` - Common image file extensions
- `@image_dim` - Image filenames with dimensions (e.g., image-100x100.jpg)
- `@image_retina` - Retina image filenames (e.g., image@2x.jpg)
- `@image_num` - Image filenames with numbers (e.g., image1.jpg)

#### Code and Identifiers
- `@uuid` - UUID (version 4)
- `@hexcolor` - Hex color code (with optional #)
- `@alnum` - Alphanumeric characters
- `@alnum_` - Alphanumeric with underscores
- `@alnum-` - Alphanumeric with hyphens
- `@alnum_-` - Alphanumeric with underscores and hyphens

#### Numbers and Codes
- `@int` - Integer number
- `@float` - Floating point number
- `@phone_us` - US/Canada phone number
- `@zipcode_us` - US ZIP code (5 or 9 digits)
- `@ssn` - US Social Security Number (###-##-####)

### Pattern Alias Usage

```php
use ThePerfectWill\PhpLib\SimpleRegex;

// Validate an email address
if (SimpleRegex::matches('@email', 'user@example.com')) {
    echo 'Valid email address';
}

// Match a URL with a specific path
if (SimpleRegex::matches('@url/path/to/page', 'https://example.com/path/to/page')) {
    echo 'Valid URL with path';
}

// Match a date in YYYY-MM-DD format
if (SimpleRegex::matches('@date', '2023-12-31')) {
    echo 'Valid date';
}

// Match an image filename with dimensions
if (SimpleRegex::matches('@image_dim', 'banner-1200x600.jpg')) {
    echo 'Valid image with dimensions';
}

// Match a US phone number with various formats
$phoneNumbers = [
    '123-456-7890',
    '(123) 456-7890',
    '123.456.7890',
    '1234567890',
    '+1 (123) 456-7890'
];

foreach ($phoneNumbers as $number) {
    if (SimpleRegex::matches('@phone_us', $number)) {
        echo "Valid phone number: $number\n";
    }
}

// Using with custom patterns
$pattern = 'user-@alnum_/profile-@int';
if (SimpleRegex::matches($pattern, 'user-john_doe/profile-42')) {
    echo 'Valid user profile path';
}
```

### Combining Aliases with Custom Patterns

You can combine aliases with custom patterns for more complex validations:

```php
// Match product URLs with numeric IDs
$productPattern = '@url/products/@int';
SimpleRegex::matches($productPattern, 'https://example.com/products/123'); // true

// Match image filenames with specific dimensions
$imagePattern = 'banner-@image_dim';
SimpleRegex::matches($imagePattern, 'banner-800x600.jpg'); // true

// Match API endpoints with version and resource ID
$apiPattern = '@url/api/v@int/resource/@uuid';
SimpleRegex::matches($apiPattern, 'https://api.example.com/api/v1/resource/550e8400-e29b-41d4-a716-446655440000'); // true
```

### Getting All Available Aliases

To see all available pattern aliases at runtime:

```php
$aliases = SimpleRegex::getAvailablePatterns();
print_r($aliases);
```

> **Note:** The `toRegex()` method populates the passed reference with the generated regex pattern, while `getPattern()` returns the compiled regex string and throws an exception for invalid patterns.