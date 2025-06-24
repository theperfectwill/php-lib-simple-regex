# SimpleRegex Performance Guide

This document provides in-depth information about optimizing performance when using SimpleRegex. It covers caching strategies, pattern optimization, and best practices for high-performance usage.

## Table of Contents
- [Caching Patterns](#caching-patterns)
- [Pattern Optimization](#pattern-optimization)
- [Handling Large Inputs](#handling-large-inputs)
- [Performance Comparison](#performance-comparison)
- [Memory Management](#memory-management)
- [Benchmarking](#benchmarking)

## Caching Patterns

### Enabling and Configuring Cache
```php
use ThePerfectWill\PhpLib\SimpleRegex;

// Enable cache with default size (1000 patterns)
$regex = new SimpleRegex('your-pattern');
$regex->configureCache(true);

// Customize cache size
$regex->configureCache(true, 2000); // Cache up to 2000 patterns
```

### Cache Hit/Miss Monitoring
```php
$regex = new SimpleRegex('your-pattern');
$regex->configureCache(true);

// First run - compiles and caches the pattern
$result1 = $regex->doesMatch('test');

// Second run - uses cached pattern
$result2 = $regex->doesMatch('test');

// Check cache stats (if available in your version)
if (method_exists($regex, 'getCacheStats')) {
    $stats = $regex->getCacheStats();
    echo "Cache hits: " . ($stats['hits'] ?? 'N/A') . "\n";
    echo "Cache misses: " . ($stats['misses'] ?? 'N/A') . "\n";
}
```

## Pattern Optimization

### Use Specific Patterns
```php
// Less efficient
$regex = new SimpleRegex('.*@.*\\..*');

// More efficient - uses built-in email pattern
$regex = new SimpleRegex('@email');
```

### Avoid Nested Quantifiers
```php
// Potentially catastrophic backtracking
$regex = new SimpleRegex('(a+)+$');

// Better - linear performance
$regex = new SimpleRegex('a+$');
```

### Use Non-Capturing Groups
```php
// Capturing group (slightly slower)
$regex = new SimpleRegex('(foo|bar)');

// Non-capturing group (faster)
$regex = new SimpleRegex('(?:foo|bar)');
```

## Handling Large Inputs

### Streaming Large Files
```php
function processLargeFile($filePath, SimpleRegex $regex) {
    $handle = fopen($filePath, 'r');
    if (!$handle) {
        throw new \RuntimeException("Could not open file");
    }

    $lineNumber = 0;
    while (($line = fgets($handle)) !== false) {
        $lineNumber++;
        if ($regex->doesMatch($line)) {
            // Process matching line
        }
        
        // Clear memory every 1000 lines
        if ($lineNumber % 1000 === 0) {
            gc_collect_cycles();
        }
    }
    
    fclose($handle);
}
```

## Performance Comparison

### SimpleRegex vs Native preg_*
```php
// SimpleRegex with caching (after warm-up)
$start = microtime(true);
$regex = new SimpleRegex('^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$');
$regex->configureCache(true);

// Warm up cache
$regex->doesMatch('test@example.com');

// Test
for ($i = 0; $i < 10000; $i++) {
    $regex->doesMatch('test@example.com');
}
$simpleRegexTime = microtime(true) - $start;

// Native preg_match
$start = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$/', 'test@example.com');
}
$nativeTime = microtime(true) - $start;

echo "SimpleRegex: " . $simpleRegexTime . "s\n";
echo "Native preg_match: " . $nativeTime . "s\n";
```

## Memory Management

### Clearing Cache
```php
$regex = new SimpleRegex('pattern');

// Clear entire cache
$regex->clearCache();

// Or shrink cache to half its size
$regex->clearCache(true);
```

### Memory-Efficient Batch Processing
```php
function processBatch(array $inputs, array $patterns) {
    $results = [];
    $regexCache = [];
    
    foreach ($patterns as $pattern) {
        $regex = new SimpleRegex($pattern);
        $regex->configureCache(true);
        $regexCache[] = $regex;
    }
    
    foreach ($inputs as $input) {
        foreach ($regexCache as $regex) {
            if ($regex->doesMatch($input)) {
                $results[] = $input;
                break;
            }
        }
        
        // Clear memory every 1000 iterations
        if (count($results) % 1000 === 0) {
            gc_collect_cycles();
        }
    }
    
    return $results;
}
```

## Benchmarking

### Basic Benchmarking Script
```php
function benchmarkPattern($pattern, $iterations = 1000) {
    $start = microtime(true);
    $regex = new SimpleRegex($pattern);
    $regex->configureCache(true);
    
    // Warm up
    $regex->doesMatch('test@example.com');
    
    // Test
    $startTime = microtime(true);
    for ($i = 0; $i < $iterations; $i++) {
        $regex->doesMatch('test@example.com');
    }
    $endTime = microtime(true);
    
    return [
        'total_time' => $endTime - $startTime,
        'avg_time' => ($endTime - $startTime) / $iterations,
        'memory_usage' => memory_get_peak_usage(true) / 1024 / 1024 . ' MB'
    ];
}

// Example usage
$results = [];
$patterns = [
    '@email',
    '^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$',
    '.*@.*\\..*'
];

foreach ($patterns as $pattern) {
    $results[$pattern] = benchmarkPattern($pattern);
}

print_r($results);
```

## Performance Tips

1. **Reuse Instances**: Always reuse SimpleRegex instances when possible to benefit from caching.
2. **Use Built-in Patterns**: Utilize predefined patterns like `@email` for better performance.
3. **Monitor Cache Hit Rate**: Keep an eye on cache hit/miss ratios to optimize cache size.
4. **Avoid Complex Patterns**: Simplify patterns where possible to reduce compilation time.
5. **Batch Process**: When processing multiple patterns, group them to minimize instance creation overhead.
6. **Profile Regularly**: Use Xdebug or Blackfire to identify performance bottlenecks.
7. **Consider Memory**: For long-running processes, periodically clear the cache to manage memory usage.

Remember that the optimal configuration depends on your specific use case, so always profile with realistic data.
