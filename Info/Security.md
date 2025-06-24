# Security in SimpleRegex

This document outlines the security features, best practices, and potential risks when using SimpleRegex for pattern matching in PHP applications.

## Table of Contents
- [Security Features](#security-features)
- [ReDoS Protection](#redos-protection)
- [Input Validation](#input-validation)
- [Pattern Safety](#pattern-safety)
- [Security Best Practices](#security-best-practices)
- [Common Vulnerabilities](#common-vulnerabilities)
- [Security Audit Checklist](#security-audit-checklist)
- [Reporting Security Issues](#reporting-security-issues)

## Security Features

### 1. ReDoS Protection

SimpleRegex includes built-in protection against Regular Expression Denial of Service (ReDoS) attacks:

```php
// Example of ReDoS protection in action
try {
    $regex = new SimpleRegex('(a+)+$');
    $result = $regex->doesMatch(str_repeat('a', 1000) . '!');
} catch (\RuntimeException $e) {
    // Catches potential ReDoS attempts
    echo "Potential ReDoS detected: " . $e->getMessage();
}
```

### 2. Input Validation

All input is validated before processing to prevent injection attacks:

```php
// Safe handling of potentially malicious patterns
try {
    $regex = new SimpleRegex($userSuppliedPattern);
    $result = $regex->doesMatch($userInput);
} catch (\InvalidArgumentException $e) {
    // Handle invalid patterns safely
    log_error("Invalid pattern: " . $e->getMessage());
}
```

### 3. Pattern Safety

SimpleRegex includes safety checks for potentially dangerous patterns:

```php
// Check if a pattern is safe before use
if ($regex->isSafePattern($userPattern)) {
    $regex = new SimpleRegex($userPattern);
} else {
    // Handle unsafe pattern
    throw new \RuntimeException("Pattern contains potentially dangerous constructs");
}
```

## ReDoS Protection

### How It Works

1. **Pattern Analysis**: SimpleRegex analyzes patterns for:
   - Nested quantifiers (e.g., `(a+)+`)
   - Exponential backtracking potential
   - Large character classes

2. **Execution Limits**:
   - Time limits for pattern matching
   - Maximum steps for backtracking
   - Memory usage constraints

### Configuration

```php
// Configure ReDoS protection settings
$regex = new SimpleRegex('your-pattern');

// Set maximum steps for pattern matching
$regex->setMaxSteps(100000);

// Set timeout in seconds
$regex->setTimeout(1.0);

// Enable/disable ReDoS protection (enabled by default)
$regex->setReDosProtection(true);
```

## Input Validation

### Safe Pattern Validation

```php
// Validate pattern before use
if (!SimpleRegex::isValidPattern($userPattern)) {
    throw new \InvalidArgumentException("Invalid pattern");
}

// Check pattern complexity
$complexity = $regex->getPatternComplexity($userPattern);
if ($complexity > 1000) { // Arbitrary threshold
    throw new \RuntimeException("Pattern too complex");
}
```

### Input Sanitization

```php
// Sanitize user input before pattern matching
function safeMatch($pattern, $input) {
    $input = filter_var($input, FILTER_SANITIZE_STRING, 
        FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    
    $regex = new SimpleRegex($pattern);
    return $regex->doesMatch($input);
}
```

## Pattern Safety

### Safe Pattern Practices

1. **Whitelist Allowed Characters**:
   ```php
   // Only allow alphanumeric and basic punctuation
   if (!preg_match('/^[a-zA-Z0-9 .,!?-]+$/', $userPattern)) {
       throw new \InvalidArgumentException("Invalid characters in pattern");
   }
   ```

2. **Limit Pattern Length**:
   ```php
   if (strlen($userPattern) > 1000) {
       throw new \InvalidArgumentException("Pattern too long");
   }
   ```

3. **Use Predefined Patterns** when possible:
   ```php
   // Instead of user-supplied patterns, use predefined ones
   $validators = [
       'email' => '@email',
       'url' => '@url',
       // ...
   ];
   
   if (!isset($validators[$userType])) {
       throw new \InvalidArgumentException("Invalid validator type");
   }
   
   $regex = new SimpleRegex($validators[$userType]);
   ```

## Security Best Practices

### 1. Never Trust User-Supplied Patterns

```php
// UNSAFE - Never do this
$regex = new SimpleRegex($_GET['pattern']);

// SAFE - Use predefined patterns
$allowedPatterns = [
    'email' => '@email',
    'phone' => '@phone',
    // ...
];

if (!isset($allowedPatterns[$_GET['type']])) {
    throw new \InvalidArgumentException("Invalid pattern type");
}

$regex = new SimpleRegex($allowedPatterns[$_GET['type']]);
```

### 2. Set Reasonable Limits

```php
// Set reasonable limits for pattern matching
ini_set('pcre.backtrack_limit', '1000000');
ini_set('pcre.recursion_limit', '500');

// Configure SimpleRegex with safe defaults
$regex = new SimpleRegex('your-pattern');
$regex->setMaxSteps(100000);
$regex->setTimeout(1.0);
```

### 3. Use Prepared Patterns

```php
// Instead of string concatenation, use parameterized patterns
function matchWithParams($pattern, $params) {
    // Escape all parameters
    $params = array_map('preg_quote', $params);
    
    // Replace placeholders with escaped values
    $pattern = str_replace(
        array_map(fn($k) => '{' . $k . '}', array_keys($params)),
        array_values($params),
        $pattern
    );
    
    return (new SimpleRegex($pattern))->doesMatch($input);
}
```

## Common Vulnerabilities

### 1. Catastrophic Backtracking

**Vulnerable Pattern**: `^(a+)*$` with input `'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa!'`

**Safe Alternative**: Use atomic groups or possessive quantifiers: `^(?>(a+))*$`

### 2. ReDoS Attacks

**Vulnerable Pattern**: `(a|a?)+b` with input `'a' * 1000 + 'c'`

**Mitigation**:
```php
$regex = new SimpleRegex('(a|a?)+b');
$regex->setMaxSteps(10000); // Prevent excessive backtracking
```

### 3. Pattern Injection

**Vulnerable Code**:
```php
// UNSAFE - User can inject regex patterns
$userPattern = $_GET['pattern'];
$regex = new SimpleRegex("^$userPattern$");
```

**Secure Code**:
```php
// Only allow predefined patterns
$allowedPatterns = [
    'email' => '@email',
    'phone' => '@phone',
];

if (!isset($allowedPatterns[$_GET['type']])) {
    throw new \InvalidArgumentException("Invalid pattern type");
}

$regex = new SimpleRegex($allowedPatterns[$_GET['type']]);
```

## Security Audit Checklist

When auditing code that uses SimpleRegex, check for:

1. [ ] Direct use of user input in patterns
2. [ ] Lack of pattern validation
3. [ ] Missing ReDoS protection
4. [ ] Unbounded pattern matching
5. [ ] Insufficient input validation
6. [ ] Missing error handling
7. [ ] Excessive backtracking potential
8. [ ] Lack of timeout settings

## Reporting Security Issues

If you discover a security vulnerability in SimpleRegex, please report it responsibly:

1. **Do not** create a public GitHub issue
2. Email security@example.com with details of the vulnerability
3. Include steps to reproduce the issue
4. Provide any relevant code examples
5. We will respond within 48 hours to acknowledge your report

### Security Updates

- Subscribe to security announcements at [security.example.com](https://security.example.com)
- Follow [@ExampleSec](https://twitter.com/ExampleSec) for security updates

## Additional Resources

- [OWASP Regular Expression Denial of Service](https://owasp.org/www-community/attacks/Regular_expression_Denial_of_Service_-_ReDoS)
- [CWE-400: Uncontrolled Resource Consumption](https://cwe.mitre.org/data/definitions/400.html)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)

## Conclusion

SimpleRegex provides several security features to help prevent common vulnerabilities, but it's important to follow security best practices when using it in your applications. Always validate and sanitize input, use predefined patterns when possible, and set appropriate limits on pattern matching operations.
