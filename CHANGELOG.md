# Changelog

All notable changes to the Global Parity Engine project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial project structure and repository setup
- Two-plugin architecture:
  - **Global Parity Data Plugin**: Core data management and REST API
  - **Google Earth Integration Plugin**: Geographic visualization with Google Earth
- Comprehensive documentation:
  - README.md with installation and usage instructions
  - CONTRIBUTING.md with contribution guidelines
  - ARCHITECTURE.md detailing system design
  - API.md with REST API reference
  - DEPLOYMENT.md with deployment instructions
- Development infrastructure:
  - Composer configuration with WordPress coding standards
  - PHPUnit testing framework setup
  - EditorConfig for consistent coding styles
  - Environment variable templates (.env.example)
- GitHub Actions CI/CD:
  - Automated PHP linting and testing
  - WordPress coding standards checks
  - CodeQL security scanning
- GitHub templates:
  - Issue templates for bug reports and feature requests
  - Pull request template
- Comprehensive .gitignore for WordPress/PHP projects
- MIT License

### Features

#### Global Parity Data Plugin
- Custom post type for parity data entries
- REST API endpoints for data management
- Admin interface for data entry and management
- Data validation and sanitization
- Query and filtering capabilities

#### Google Earth Integration Plugin
- Google Maps/Earth visualization
- Geographic marker placement
- Interactive map interface
- Integration with Global Parity Data
- Shortcode support for embedding maps

### Security
- Environment variable configuration for sensitive data
- Input validation and sanitization
- CodeQL security scanning in CI/CD
- WordPress nonce verification

### Developer Experience
- PSR-4 autoloading
- WordPress coding standards compliance
- Automated testing suite
- Local development setup documentation
- Clear contribution guidelines

## [1.0.0] - TBD

Initial release (planned)

### Release Highlights
- Production-ready WordPress plugin suite
- Full REST API for parity data management
- Google Earth/Maps visualization
- Comprehensive documentation
- Professional development workflow

---

## Version History

- **Unreleased**: Active development
- **1.0.0**: Planned initial release

## Links

- [Repository](https://github.com/rongabby/global-parity-engine)
- [Issues](https://github.com/rongabby/global-parity-engine/issues)
- [Pull Requests](https://github.com/rongabby/global-parity-engine/pulls)
