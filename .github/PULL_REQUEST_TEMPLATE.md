# Pull Request

## Description

Please include a summary of the changes and which issue is fixed. Include relevant motivation and context.

Fixes # (issue)

## Type of Change

Please delete options that are not relevant.

- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update
- [ ] Performance improvement
- [ ] Code refactoring
- [ ] Dependency update
- [ ] Configuration change
- [ ] Other (please describe):

## Changes Made

Please describe the changes made in this PR:

- Change 1
- Change 2
- Change 3

## How Has This Been Tested?

Please describe the tests that you ran to verify your changes. Provide instructions so we can reproduce.

- [ ] Test A
- [ ] Test B

**Test Configuration**:
- WordPress version:
- PHP version:
- Browser (if applicable):
- Operating System:

## Screenshots (if applicable)

Add screenshots to help explain your changes, especially for UI changes.

| Before | After |
|--------|-------|
| ![before](url) | ![after](url) |

## Checklist

Please check off the following as you complete them:

### Code Quality
- [ ] My code follows the WordPress coding standards
- [ ] I have performed a self-review of my own code
- [ ] I have commented my code, particularly in hard-to-understand areas
- [ ] My changes generate no new warnings or errors
- [ ] I have checked for and fixed any merge conflicts

### Testing
- [ ] I have added tests that prove my fix is effective or that my feature works
- [ ] New and existing unit tests pass locally with my changes
- [ ] I have tested this on multiple PHP versions (if applicable)
- [ ] I have tested this in different browsers (if applicable)

### Documentation
- [ ] I have updated the documentation accordingly
- [ ] I have updated the README.md (if needed)
- [ ] I have updated the CHANGELOG.md
- [ ] I have added inline code comments where necessary
- [ ] I have updated the API documentation (if API changes were made)

### Security
- [ ] My code doesn't introduce security vulnerabilities
- [ ] I have properly sanitized all inputs
- [ ] I have properly escaped all outputs
- [ ] I have used WordPress nonces where appropriate
- [ ] I have checked user capabilities before performing actions

### Dependencies
- [ ] I have updated composer.json (if dependencies changed)
- [ ] I have run `composer update` and committed the lock file (if needed)
- [ ] I have checked for dependency vulnerabilities with `composer audit`

## Breaking Changes

Does this PR introduce any breaking changes? If yes, please describe:

- Breaking change 1
- Breaking change 2

## Migration Guide

If there are breaking changes, provide a migration guide:

```php
// Before
old_function_call();

// After
new_function_call();
```

## Dependencies

List any dependencies that are required for this change:

- Dependency 1
- Dependency 2

## Related Issues/PRs

Link any related issues or pull requests:

- Closes #
- Related to #
- Depends on #

## Deployment Notes

Are there any special deployment considerations?

- [ ] Database migrations required
- [ ] Configuration changes needed
- [ ] Environment variables need updating
- [ ] Cache needs clearing
- [ ] Other:

## Reviewer Notes

Any specific areas you'd like reviewers to focus on?

- Area 1
- Area 2

## Additional Context

Add any other context about the pull request here.

---

**By submitting this pull request, I confirm that:**

- [ ] I have read and agree to the [Contributing Guidelines](../CONTRIBUTING.md)
- [ ] My contribution is my own original work
- [ ] I agree to license my contribution under the project's MIT License
- [ ] I understand this will be publicly available
