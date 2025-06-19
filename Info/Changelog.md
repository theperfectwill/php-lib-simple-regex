# Simple Regex - Change Log

[Back to ReadMe](../ReadMe.md) | [Roadmap](Info/Roadmap.md) | [Get Support](https://github.com/theperfectwill/php-lib-simple-regex/issues)

---

All notable changes to this project will be documented in this file.

    Version Numbering: {major.minor.fix-stage.level}
        Example: 1.0.0-production.deploy.x1
        Example: 1.0.0-production.candidate.x1
        Example: 1.0.0-development.beta.x1
        Example: 1.0.0-development.alpha.x1
        
        * {major} Major structural code changes and/or incompatible API changes (ie. breaking changes).
        * {minor} New functionality was added or improved in a backwards-compatible manner.
        * {fix} Backwards-compatible bug fixes or small improvements.
        * {stage} production|(candidate|deploy)
        * {stage} development|(alpha|beta)
        * x{numerical value of updates today, in case 2 or more updates made within same day}

---
### 👊 2025-06-17 | 1.0.0-production.deploy.x1 💪
---

### Added 🎁: Regex Presets - Common regex character classes and patterns. Check [Usage.md](Usage.md) for examples.

  * #### Email and Web
    * `@email` - Standard email addresses
    * `@url` - Web URLs with protocol and domain
    * `@ipv4` - IPv4 addresses
    * `@ipv6` - IPv6 addresses (full and compressed formats)

  * #### Date and Time
    * `@date` - ISO 8601 date format (YYYY-MM-DD)
    * `@time` - 24-hour time format (HH:MM:SS.mmm)
    * `@datetime` - ISO 8601 datetime with timezone support

  * #### Files and Paths
    * `@filename` - Valid filenames (without path)
    * `@fileext` - File extensions (including multiple extensions)
    * `@filepath` - File paths with proper directory separators

  * #### Image Patterns
    * `@image` - Common image filenames (jpg, png, gif, etc.)
    * `@image_ext` - Image file extensions
    * `@image_dim` - Images with dimensions in filename (e.g., image-100x100.jpg)
    * `@image_retina` - Retina/HiDPI images (e.g., image@2x.png)
    * `@image_num` - Images with numbers in filename (e.g., image01.jpg)

  * #### Code and Identifiers
    * `@uuid` - UUID/GUID format
    * `@hexcolor` - Hex color codes (3 or 6 digits, optional #)
    * `@alnum` - Alphanumeric characters
    * `@alnum_` - Alphanumeric + underscores
    * `@alnum-` - Alphanumeric + hyphens
    * `@alnum_-` - Alphanumeric + underscores + hyphens

  * #### Numbers and Codes
    * `@int` - Positive/negative integers
    * `@float` - Floating point numbers
    * `@posint` - Positive integers only
    * `@negint` - Negative integers only
    * `@phone` - US phone number formats
    * `@zipcode` - US ZIP codes (5+4 optional)
    * `@ssn` - Social Security Numbers (XXX-XX-XXXX)

  * #### Development
    * `@semver` - Semantic versioning (SemVer 2.0.0)
    * `@githash` - Git commit hashes (7-40 hex chars)

### Added 🎁: Utility Methods:

  * `SimpleRegex::isPatternAlias()` - Check if a string is a valid pattern alias
  * `SimpleRegex::getAvailablePatterns()` - Get list of all available pattern aliases
  * All pattern aliases are also available as class constants (e.g., `SimpleRegex::PRESET_EMAIL`)

### Security 🔐: Input Validations

* Added comprehensive input validation to prevent potential security issues
    * Input validation for empty strings
    * Validation for `$exclusion_char` to ensure it's a single character
    * Input sanitization for better security
    * Enhanced pattern validation for regular expressions
    * Improved protection against regular expression injection attacks
    * Added input length restrictions to mitigate potential DoS attacks

### Security 🔐: Documented Implications

* Documented security implications of certain patterns (by comments in the code)

### Performance 🚀: Caching

* Added pattern caching to avoid recompiling the same patterns repeatedly
* Combined consecutive pattern replacements into single operations
* Optimized wildcard and question mark processing
* Added cache management methods:
    * `setCacheEnabled()` - Enable/disable the pattern cache
    * `clearCache()` - Clear all cached patterns
    * `getCacheSize()` - Get the number of cached patterns

### Performance 🚀: String Operations

* Optimized string operations throughout the codebase:
    * Replaced multiple `preg_replace` calls with more efficient string operations where possible
    * Combined consecutive string replacements into single operations
    * Improved token restoration with more efficient string replacement
    * Optimized anchor processing with a single string replacement
    * Reorganized pattern processing order for better performance
    * Added early returns for empty token lists to avoid unnecessary processing

---
### 👊 2025-06-17 | 1.0.0-production.deploy.x1 💪
---

### Initial Release.

<!--
    <KEEP>
        Below is the date, version, and quanity of our changelog update.
        It always precedes our changelog information.
        
        ### 👊 YYYY-MONTH-DAY | 0.0.0-STAGE.LEVEL.x1 💪
        Example: 1.0.0-production.deploy.x1
        Example: 1.0.0-production.candidate.x1
        Example: 1.0.0-development.beta.x1
        Example: 1.0.0-development.alpha.x1
        
        Note: Below are changelog categories. You copy a category as needed to for changelog updates, but DO NOT remove them.
        
        ### Forked🍴:
        ### Announcement 📣:
        ### UI 🎨:
        ### Added 🎁:
        ### Changed 🚩:
        ### Depreciated 🚧:
        ### Removed 💣:
        ### Fixed 🔦🔧:
        ### Performance 🚀:
        ### Security 🔐:
        ### Documention📓:
        ### Testing💡:
    </KEEP>
-->
