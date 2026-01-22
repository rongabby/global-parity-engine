# Contributing to Global Parity Engine

First off, thank you for considering contributing to Global Parity Engine! It's people like you that make this project better for everyone.

## Code of Conduct

This project and everyone participating in it is governed by our commitment to providing a welcoming and inspiring community for all. By participating, you are expected to uphold this commitment. Please report unacceptable behavior to the project maintainers.

### Our Standards

- Using welcoming and inclusive language
- Being respectful of differing viewpoints and experiences
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues as you might find out that you don't need to create one. When you are creating a bug report, please include as many details as possible:

- **Use a clear and descriptive title** for the issue
- **Describe the exact steps to reproduce the problem** with as many details as possible
- **Provide specific examples** to demonstrate the steps
- **Describe the behavior you observed** after following the steps
- **Explain which behavior you expected to see instead** and why
- **Include screenshots** if relevant
- **Include your environment details**:
  - WordPress version
  - PHP version
  - Browser and version (if frontend issue)
  - Plugin version
  - Other relevant plugins installed

### Suggesting Features

Feature suggestions are tracked as GitHub issues. When creating a feature suggestion, please include:

- **Use a clear and descriptive title**
- **Provide a detailed description** of the suggested feature
- **Explain why this feature would be useful** to most users
- **Provide examples** of how the feature would be used
- **List any alternatives** you've considered

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Follow the coding standards** (see below)
3. **Write tests** for your changes when applicable
4. **Update documentation** if you're changing functionality
5. **Ensure the test suite passes**
6. **Make sure your code lints** without errors
7. **Write a clear commit message** (see below)
8. **Submit your pull request**

## Development Setup

### Prerequisites

- PHP 7.4 or higher
- Composer
- WordPress 5.8 or higher
- Node.js and npm (optional, for frontend builds)

### Local Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/rongabby/global-parity-engine.git
   cd global-parity-engine
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Configure environment**:
   ```bash
   cp .env.example .env
   # Edit .env with your local WordPress settings
   ```

4. **Set up WordPress coding standards**:
   ```bash
   composer setup-standards
   ```

5. **Copy plugins to WordPress**:
   ```bash
   # Copy plugins to your WordPress installation
   cp -r plugins/global-parity-data /path/to/wordpress/wp-content/plugins/
   cp -r plugins/google-earth-integration /path/to/wordpress/wp-content/plugins/
   ```

6. **Activate plugins** in WordPress admin panel

## Coding Standards

This project follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).

### PHP Standards

- Follow WordPress PHP Coding Standards
- Use tabs for indentation (not spaces)
- Use proper WordPress functions (wp_enqueue_script, wp_localize_script, etc.)
- Sanitize all input and escape all output
- Add proper documentation blocks for all functions and classes
- Follow PSR-4 autoloading conventions for namespaced code

### Running Code Standards Check

```bash
# Check for coding standards violations
composer lint

# Automatically fix coding standards issues
composer lint:fix
```

### JavaScript Standards

- Follow WordPress JavaScript Coding Standards
- Use modern ES6+ syntax
- Document functions with JSDoc comments

### CSS Standards

- Follow WordPress CSS Coding Standards
- Use consistent naming conventions
- Write mobile-first responsive styles

## Testing

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test:coverage
```

### Writing Tests

- Write PHPUnit tests for all new PHP functionality
- Follow existing test structure and naming conventions
- Aim for high code coverage
- Test both success and failure cases
- Mock WordPress functions when necessary

### Test File Structure

```
tests/
└── phpunit/
    ├── bootstrap.php
    ├── ParityData/
    │   └── ExampleTest.php
    └── GoogleEarth/
        └── ExampleTest.php
```

## Commit Message Conventions

Write clear, concise commit messages that describe what changed and why:

```
Short (50 chars or less) summary

More detailed explanatory text, if necessary. Wrap it to about 72
characters. The blank line separating the summary from the body is
critical.

- Bullet points are okay, too
- Use a hyphen or asterisk preceded by a single space

Fixes #123
```

### Commit Message Format

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters or less
- Reference issues and pull requests liberally after the first line

### Commit Types

- **feat**: A new feature
- **fix**: A bug fix
- **docs**: Documentation only changes
- **style**: Changes that don't affect code meaning (formatting, etc.)
- **refactor**: Code change that neither fixes a bug nor adds a feature
- **test**: Adding missing tests or correcting existing tests
- **chore**: Changes to build process or auxiliary tools

## Documentation

- Update the README.md if you change functionality
- Update API.md for API changes
- Add inline code comments for complex logic
- Write clear docblocks for all functions and classes
- Update CHANGELOG.md following Keep a Changelog format

## Pull Request Process

1. **Update documentation** with details of changes to the interface
2. **Update the CHANGELOG.md** with notes on your changes
3. **Ensure all tests pass** and coding standards are met
4. **The PR will be merged** once you have the sign-off of at least one maintainer

## Review Process

- Maintainers will review your PR as soon as possible
- You may be asked to make changes before merging
- Be open to feedback and discussion
- Once approved, a maintainer will merge your PR

## Community

- Ask questions in GitHub Issues
- Discuss ideas and proposals before implementing large features
- Be patient and respectful in all interactions
- Help others when you can

## Recognition

Contributors will be recognized in:
- README.md contributors section
- Release notes
- Project documentation

## Questions?

Don't hesitate to ask questions by opening an issue with the question label.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to Global Parity Engine! 🚀
