# Global Parity Engine

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![CI Status](https://github.com/rongabby/global-parity-engine/workflows/CI/badge.svg)](https://github.com/rongabby/global-parity-engine/actions)

> A professional WordPress plugin suite for global parity data tracking and visualization with Google Earth integration.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Architecture](#architecture)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Development](#development)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## 🌍 Overview

The Global Parity Engine is a comprehensive WordPress plugin suite designed to manage and visualize global parity data. It consists of two complementary plugins that work independently or together to provide powerful data tracking and geographic visualization capabilities.

### Purpose

- Track and manage parity data across different geographic regions
- Visualize data on interactive maps using Google Earth/Maps
- Provide REST API access for data integration
- Enable data-driven decision making with geographic context

## ✨ Features

### Global Parity Data Plugin
- 📊 **Custom Post Type**: Dedicated parity data entries with custom fields
- 🔌 **REST API**: Full CRUD operations via WordPress REST API
- 🎯 **Data Management**: Intuitive admin interface for data entry
- 🔍 **Query & Filter**: Advanced filtering and search capabilities
- ✅ **Validation**: Input validation and data sanitization
- 🔐 **Security**: WordPress nonce verification and capability checks

### Google Earth Integration Plugin
- 🗺️ **Interactive Maps**: Google Maps/Earth visualization
- 📍 **Geographic Markers**: Place and manage location markers
- 🎨 **Customizable**: Configurable map styles and marker icons
- 🔗 **Data Integration**: Links with Global Parity Data plugin
- 📝 **Shortcodes**: Easy embedding with WordPress shortcodes
- 📱 **Responsive**: Mobile-friendly map interface

## 🏗️ Architecture

The Global Parity Engine uses a **two-plugin architecture** for maximum flexibility:

```
Global Parity Engine
├── Global Parity Data (Core)
│   ├── Custom Post Types
│   ├── REST API Endpoints
│   └── Admin Interface
│
└── Google Earth Integration (Visualization)
    ├── Map Rendering
    ├── Geographic Markers
    └── Shortcode System
```

### How They Work Together

1. **Standalone Mode**: Each plugin can function independently
2. **Integrated Mode**: When both are active, they share data seamlessly
3. **API-First**: Communication via WordPress REST API
4. **Extensible**: Easy to add more plugins or integrations

### Technical Stack

- **Backend**: PHP 7.4+, WordPress 5.8+
- **API**: WordPress REST API
- **Maps**: Google Maps JavaScript API
- **Standards**: WordPress Coding Standards, PSR-4 Autoloading
- **Testing**: PHPUnit, WordPress Test Suite
- **CI/CD**: GitHub Actions

## 📦 Installation

### Prerequisites

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Google Maps API key (for map visualization)

### Step 1: Clone the Repository

```bash
git clone https://github.com/rongabby/global-parity-engine.git
cd global-parity-engine
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Setup

```bash
# Copy environment template
cp .env.example .env

# Edit .env with your settings
nano .env
```

Configure your `.env` file with:
- Database credentials
- Google Maps API key
- WordPress settings
- Debug options

### Step 4: Copy Plugins to WordPress

```bash
# Copy to your WordPress installation
cp -r plugins/global-parity-data /path/to/wordpress/wp-content/plugins/
cp -r plugins/google-earth-integration /path/to/wordpress/wp-content/plugins/
```

### Step 5: Activate Plugins

1. Log in to WordPress admin panel
2. Navigate to **Plugins** → **Installed Plugins**
3. Activate **Global Parity Data**
4. Activate **Google Earth Integration** (optional)

## ⚙️ Configuration

### Google Maps API Setup

1. **Get API Key**:
   - Visit [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project or select existing one
   - Enable **Maps JavaScript API**
   - Create credentials (API Key)
   - Restrict API key to your domain

2. **Configure Plugin**:
   - In WordPress admin, go to **Settings** → **Google Earth Integration**
   - Enter your Google Maps API key
   - Configure default map settings (center, zoom, style)

### WordPress Settings

```php
// In your wp-config.php or .env file
define('GOOGLE_MAPS_API_KEY', 'your-api-key-here');
define('GLOBAL_PARITY_MAX_RESULTS', 100);
define('GLOBAL_PARITY_CACHE_TTL', 3600);
```

### Custom Post Type Setup

The Global Parity Data plugin automatically registers a custom post type:

- **Post Type**: `parity_data`
- **Supports**: Title, Editor, Custom Fields, Thumbnail
- **Taxonomies**: Categories, Tags
- **Capabilities**: Standard WordPress capabilities

## 🚀 Usage

### Adding Parity Data

#### Via Admin Interface

1. Navigate to **Parity Data** → **Add New**
2. Enter title and description
3. Add custom fields:
   - Location (latitude/longitude)
   - Data values
   - Timestamps
4. Publish or save as draft

#### Via REST API

```bash
# Create new parity data entry
curl -X POST https://yoursite.com/wp-json/wp/v2/parity_data \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "title": "New Data Entry",
    "content": "Description here",
    "meta": {
      "latitude": "40.7128",
      "longitude": "-74.0060",
      "value": "100"
    }
  }'
```

### Displaying Maps with Shortcodes

```php
// Basic map
[parity_map]

// Map with specific location
[parity_map lat="40.7128" lng="-74.0060" zoom="10"]

// Map with data filtering
[parity_map category="region-1" limit="50"]

// Custom styled map
[parity_map style="dark" height="600px"]
```

### REST API Endpoints

```
GET    /wp-json/wp/v2/parity_data          # List all entries
GET    /wp-json/wp/v2/parity_data/{id}     # Get single entry
POST   /wp-json/wp/v2/parity_data          # Create new entry
PUT    /wp-json/wp/v2/parity_data/{id}     # Update entry
DELETE /wp-json/wp/v2/parity_data/{id}     # Delete entry
```

For complete API documentation, see [docs/API.md](docs/API.md).

## 💻 Development

### Local Setup

```bash
# Install dependencies
composer install

# Set up coding standards
composer setup-standards

# Copy plugins to local WordPress
ln -s $(pwd)/plugins/global-parity-data /path/to/wordpress/wp-content/plugins/
ln -s $(pwd)/plugins/google-earth-integration /path/to/wordpress/wp-content/plugins/
```

### Running Tests

```bash
# Run all tests
composer test

# Run specific test suite
./vendor/bin/phpunit --testsuite="Global Parity Data Plugin Tests"

# Generate coverage report
composer test:coverage
```

### Linting & Code Standards

```bash
# Check coding standards
composer lint

# Auto-fix coding standards
composer lint:fix

# Check PHP compatibility
./vendor/bin/phpcs -p plugins/ --standard=PHPCompatibilityWP --runtime-set testVersion 7.4-
```

### Development Workflow

1. Create a feature branch: `git checkout -b feature/my-feature`
2. Make your changes following [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
3. Write tests for new functionality
4. Run tests and linting: `composer test && composer lint`
5. Commit with clear message: `git commit -m "feat: add new feature"`
6. Push and create pull request

### File Structure

```
plugins/
├── global-parity-data/
│   ├── src/                 # Source files (PSR-4)
│   ├── includes/            # WordPress-specific includes
│   ├── admin/               # Admin interface
│   ├── public/              # Public-facing functionality
│   └── global-parity-data.php  # Main plugin file
│
└── google-earth-integration/
    ├── src/                 # Source files (PSR-4)
    ├── includes/            # WordPress-specific includes
    ├── assets/              # CSS, JS, images
    └── google-earth-integration.php  # Main plugin file
```

## 📚 API Documentation

### Quick Reference

```php
// Get all parity data
$response = wp_remote_get('https://yoursite.com/wp-json/wp/v2/parity_data');

// Filter by location
$args = array(
    'meta_key' => 'latitude',
    'meta_value' => '40.7128',
    'meta_compare' => 'LIKE'
);
$query = new WP_Query($args);

// Register custom endpoint
add_action('rest_api_init', function() {
    register_rest_route('parity/v1', '/summary', array(
        'methods' => 'GET',
        'callback' => 'get_parity_summary',
    ));
});
```

For complete API documentation, see [docs/API.md](docs/API.md).

## 🧪 Testing

### Test Coverage

- Unit tests for all core functionality
- Integration tests for plugin interactions
- REST API endpoint tests
- WordPress coding standards compliance

### Continuous Integration

The project uses GitHub Actions for:
- ✅ Automated testing on multiple PHP versions (7.4, 8.0, 8.1, 8.2)
- ✅ Coding standards verification (PHPCS)
- ✅ Security scanning (CodeQL)
- ✅ Dependency vulnerability checks

## 🤝 Contributing

We welcome contributions from the community! Please read our [Contributing Guidelines](CONTRIBUTING.md) for details on:

- Code of conduct
- Development setup
- Coding standards
- Pull request process
- Testing requirements

### Quick Start for Contributors

```bash
# Fork and clone
git clone https://github.com/YOUR_USERNAME/global-parity-engine.git

# Create feature branch
git checkout -b feature/amazing-feature

# Make changes and test
composer install
composer test
composer lint

# Commit and push
git commit -m "feat: add amazing feature"
git push origin feature/amazing-feature
```

Then open a pull request with a clear description of your changes.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- WordPress community for excellent documentation
- Google Maps Platform for visualization capabilities
- All contributors who help improve this project

## 📞 Support

- 📧 **Issues**: [GitHub Issues](https://github.com/rongabby/global-parity-engine/issues)
- 📖 **Documentation**: [docs/](docs/)
- 💬 **Discussions**: [GitHub Discussions](https://github.com/rongabby/global-parity-engine/discussions)

## 🗺️ Roadmap

### Version 1.0.0
- [x] Core plugin architecture
- [x] REST API implementation
- [x] Google Earth integration
- [ ] Comprehensive testing suite
- [ ] Production deployment

### Version 1.1.0 (Planned)
- [ ] Advanced data analytics
- [ ] Export/import functionality
- [ ] Multi-language support
- [ ] Performance optimizations

### Version 2.0.0 (Future)
- [ ] Real-time data updates
- [ ] Advanced visualization options
- [ ] Machine learning integration
- [ ] Mobile app support

---

**Made with ❤️ by the Global Parity Engine Team**

For more information, visit our [documentation](docs/) or [contribute](CONTRIBUTING.md) to the project!
