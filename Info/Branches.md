# Simple Regex - Branches

[Back to ReadMe](../ReadMe.md) | [Roadmap](Info/Roadmap.md) | [Get Support](https://github.com/theperfectwill/php-lib-simple-regex/issues) | [Fork](https://github.com/theperfectwill/php-lib-simple-regex/forks)

---

### Workflow:
`development-alpha` → `development-beta` → `production-candidate` → `production-deploy`

---

### Production Branch (`production-deploy`)
- **Purpose**: Stable, production-ready code
- **Release Phase**: Final public release
- **Stability**: Highest (production-grade)
- **Merged from**: `production-candidate`

### Pre-Production Branch (`production-candidate`)
- **Purpose**: Pre-release testing
- **Release Phase**: Public testing phase
- **Stability**: High (pre-release)
- **Merged from**: `development-beta`

### Development Branch (`development-beta`)
- **Purpose**: Code ready for testing
- **Release Phase**: Internal testing
- **Stability**: Medium (test-ready)
- **Merged from**: `development-alpha`

### Pre-Development Branch (`development-alpha`)
- **Purpose**: Initial development and untested code
- **Release Phase**: Early development
- **Stability**: Low (experimental)

---

Each branch likely will have personally tested or inherit tested code (from our other code bases or other publicly available code). Each merge ideally represents an increase in code stability and readiness for production.
