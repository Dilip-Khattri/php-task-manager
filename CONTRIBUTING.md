# Contributing to PHP Task Manager

Thank you for considering contributing to PHP Task Manager! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue on GitHub with:

- A clear, descriptive title
- Steps to reproduce the bug
- Expected behavior
- Actual behavior
- Screenshots (if applicable)
- Your environment (PHP version, MySQL version, browser, OS)

### Suggesting Enhancements

Feature requests are welcome! Please create an issue with:

- A clear description of the feature
- Use cases and benefits
- Any relevant examples or mockups

### Pull Requests

1. Fork the repository
2. Create a new branch (`git checkout -b feature/your-feature-name`)
3. Make your changes
4. Test your changes thoroughly
5. Commit your changes (`git commit -am 'Add new feature'`)
6. Push to the branch (`git push origin feature/your-feature-name`)
7. Create a Pull Request

## Development Guidelines

### Code Style

- **PHP**: Follow PSR-12 coding standards
- **JavaScript**: Use ES6+ features, maintain consistent style
- **CSS**: Use CSS custom properties, maintain BEM-like naming
- **Comments**: Write clear, concise comments for complex logic

### Security

- Always use prepared statements for database queries
- Sanitize all user input
- Escape all output with `htmlspecialchars()`
- Use `password_hash()` for passwords
- Validate file uploads
- Implement CSRF protection for state-changing operations

### Testing

- Test all CRUD operations
- Verify responsive design on multiple devices
- Check cross-browser compatibility
- Test with different user permissions
- Validate form inputs

### Database

- Use foreign keys for relationships
- Add indexes for frequently queried columns
- Use appropriate data types
- Follow naming conventions (snake_case)

## Project Structure

```
php-task-manager/
├── ajax/              # AJAX request handlers
├── assets/            # Static assets (images, etc.)
├── config/            # Configuration files
├── css/               # Stylesheets
├── database/          # Database schema and migrations
├── includes/          # Reusable PHP components
└── js/                # JavaScript files
```

## Commit Messages

Write clear commit messages:

- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- Keep first line under 72 characters
- Reference issues and pull requests when relevant

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Questions?

Feel free to open an issue for any questions or concerns.
