# Caching in SimpleRegex

SimpleRegex includes a powerful caching system to improve performance by storing compiled regular expressions. This guide covers all aspects of the caching system and how to use it effectively.

## Table of Contents
- [How Caching Works](#how-caching-works)
- [Basic Caching](#basic-caching)
- [Cache Configuration](#cache-configuration)
- [Cache Management](#cache-management)
- [Advanced Caching Scenarios](#advanced-caching-scenarios)
- [Performance Considerations](#performance-considerations)
- [Troubleshooting](#troubleshooting)

## How Caching Works

SimpleRegex caches compiled regular expressions to avoid the overhead of recompiling the same pattern multiple times. The cache is implemented as an in-memory array with the following characteristics:

- **Automatic Key Generation**: Cache keys are automatically generated based on the pattern, exclusion characters, and matching mode
- **LRU Eviction**: When the cache reaches its maximum size, least recently used items are evicted first
- **Size Tracking**: Each cached item includes metadata about its size and usage statistics

## Basic Caching

### Enabling Caching

Caching is enabled by default. You can create a SimpleRegex instance with caching enabled like this:

```php
use ThePerfectWill\PhpLib\SimpleRegex;

// Caching is enabled by default
$regex = new SimpleRegex('your-pattern');
```

### Verifying Cache Usage

To check if a pattern was served from cache:

```php
$regex = new SimpleRegex('your-pattern');

// First use - compiles and caches the pattern
$result1 = $regex->doesMatch('test');

// Subsequent uses - uses cached version
$result2 = $regex->doesMatch('test');

// The same instance will always use the cached pattern
$result3 = $regex->doesMatch('another test');
```

## Cache Configuration

### Setting Cache Size

You can configure the maximum number of patterns to cache:

```php
$regex = new SimpleRegex('your-pattern');

// Enable cache with custom size (default is 1000)
$regex->configureCache(true, 500); // Cache up to 500 patterns
```

### Disabling Caching

In some cases, you might want to disable caching:

```php
$regex = new SimpleRegex('your-pattern');

// Disable caching
$regex->configureCache(false);
```

### Global Cache Configuration

For applications with many SimpleRegex instances, you might want to set default cache settings:

```php
// In your application bootstrap
SimpleRegex::setDefaultCacheConfig(true, 1000); // Enable cache with 1000 items limit
```

## Cache Management

### Clearing the Cache

You can clear the entire cache or shrink it:

```php
$regex = new SimpleRegex('your-pattern');

// Clear the entire cache
$regex->clearCache();

// Or shrink the cache to half its size
$regex->clearCache(true);
```

### Cache Statistics

Get information about cache usage:

```php
$regex = new SimpleRegex('your-pattern');

// Use the pattern multiple times
for ($i = 0; $i < 10; $i++) {
    $regex->doesMatch('test' . $i);
}

// Get cache statistics
if (method_exists($regex, 'getCacheStats')) {
    $stats = $regex->getCacheStats();
    echo "Cache hits: " . ($stats['hits'] ?? 0) . "\n";
    echo "Cache misses: " . ($stats['misses'] ?? 0) . "\n";
    echo "Cache size: " . ($stats['size'] ?? 0) . " items\n";
    echo "Memory usage: " . ($stats['memory'] ?? 0) . " bytes\n";
}
```

## Advanced Caching Scenarios

### Long-Running Processes

For long-running processes (like workers or daemons), you might want to periodically clear the cache to prevent memory leaks:

```php
function processItems(array $items) {
    $regex = new SimpleRegex('your-pattern');
    $processed = 0;
    
    foreach ($items as $item) {
        $regex->doesMatch($item);
        $processed++;
        
        // Clear cache every 1000 items
        if ($processed % 1000 === 0) {
            $regex->clearCache();
            gc_collect_cycles();
        }
    }
}
```

### Shared Cache Across Instances

To share cache between multiple SimpleRegex instances:

```php
// Create first instance and enable cache
$regex1 = new SimpleRegex('pattern1');
$regex1->configureCache(true, 100);

// Create second instance with same cache configuration
$regex2 = new SimpleRegex('pattern2');
$regex2->configureCache(true, 100);

// Both instances will use the same cache
```

### Cache Key Customization

By default, cache keys are generated based on the pattern, exclusion characters, and matching mode. You can customize this by extending the SimpleRegex class:

```php
class CustomRegex extends SimpleRegex {
    protected function getCacheKey(): string {
        // Custom cache key generation
        return md5($this->pattern . '_v2');
    }
}
```

## Performance Considerations

### When to Use Caching

- **Use caching when**:
  - The same pattern is used multiple times
  - Patterns are complex and expensive to compile
  - Your application makes many pattern matches

- **Consider disabling caching when**:
  - Each pattern is only used once
  - Memory usage is a concern
  - Patterns are very simple and fast to compile

### Memory Usage

Each cached pattern consumes memory. Monitor your application's memory usage, especially if:
- You have many unique patterns
- Your patterns are complex
- Your application runs for a long time

### Cache Size Tuning

The optimal cache size depends on:
- Number of unique patterns
- Available memory
- Pattern complexity

A good starting point is 1000 patterns, but adjust based on your application's needs.

## Troubleshooting

### Common Issues

1. **Memory Leaks**
   - **Symptom**: Memory usage grows over time
   - **Solution**: Periodically clear the cache in long-running processes

2. **Cache Invalidation**
   - **Symptom**: Pattern changes don't take effect
   - **Solution**: Clear the cache or use a new SimpleRegex instance

3. **High CPU Usage**
   - **Symptom**: High CPU when creating many SimpleRegex instances
   - **Solution**: Reuse instances when possible or increase cache size

### Debugging Cache Issues

To debug cache-related issues, you can enable debug logging:

```php
// Enable debug logging if available
if (method_exists('ThePerfectWill\\PhpLib\\SimpleRegex', 'setDebug')) {
    SimpleRegex::setDebug(true);
}

// Use SimpleRegex as normal
$regex = new SimpleRegex('your-pattern');
$result = $regex->doesMatch('test');
```

## Best Practices

1. **Reuse Instances**: Create SimpleRegex instances once and reuse them
2. **Monitor Cache Hit Ratio**: Aim for a high cache hit ratio (>90%)
3. **Set Appropriate Cache Size**: Not too small (causing evictions) or too large (wasting memory)
4. **Clear Cache When Needed**: In long-running processes, clear the cache periodically
5. **Profile Performance**: Use tools like Xdebug or Blackfire to profile cache performance

## Example: Complete Caching Workflow

```php
use ThePerfectWill\PhpLib\SimpleRegex;

// 1. Create and configure with caching
$regex = new SimpleRegex('your-pattern');
$regex->configureCache(true, 500); // Enable cache with 500 items limit

// 2. Use with multiple patterns
$patterns = ['@email', '@url', '@ipv4'];
$inputs = ['test@example.com', 'https://example.com', '192.168.1.1'];

foreach ($patterns as $i => $pattern) {
    $regex = new SimpleRegex($pattern);
    $regex->configureCache(true);
    
    foreach ($inputs as $input) {
        if ($regex->doesMatch($input)) {
            echo "Pattern $pattern matched $input\n";
        }
    }
}

// 3. Check cache stats
if (method_exists($regex, 'getCacheStats')) {
    print_r($regex->getCacheStats());
}

// 4. Clear cache when done
$regex->clearCache();
```

By following this guide, you can effectively use SimpleRegex's caching system to optimize the performance of your pattern matching operations.
