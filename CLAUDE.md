# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Bagisto** e-commerce platform - a Laravel-based open-source framework for building online stores. Built with Laravel 10, PHP 8.2+, and Vue.js.

## Key Architecture

### Modular Package System

Bagisto uses **Konekt Concord** for package management. All core functionality is organized into modular packages under `packages/Webkul/`. Each package follows a consistent structure:

- `src/Models/` - Eloquent models
- `src/Repositories/` - Repository pattern for data access
- `src/Http/Controllers/` - HTTP controllers
- `src/Providers/` - Service providers (ModuleServiceProvider, EventServiceProvider, etc.)
- `src/Resources/` - Views, language files, and assets
- `src/Database/Migrations/` - Database migrations
- `src/Contracts/` - Interfaces/contracts

Core packages include:
- **Core** - Base functionality, channels, locales, currencies
- **Product** - Product management, types (simple, configurable, etc.)
- **Category** - Category hierarchy (uses nested set)
- **Attribute** - Attribute families and product attributes
- **Inventory** - Stock management
- **Customer** - Customer accounts and groups
- **Sales** - Orders, invoices, shipments, refunds
- **Checkout** - Cart and checkout flows
- **Admin** - Admin panel functionality
- **Shop** - Storefront functionality
- **Payment/Paypal** - Payment methods
- **Shipping** - Shipping methods
- **CartRule/CatalogRule** - Pricing rules
- **DataGrid** - Grid component system
- **Theme** - Theme management

### Product Architecture

Products use a type-based inheritance system:
- Each product has a `type` (simple, configurable, bundle, etc.)
- Type instances (`AbstractType` subclasses) handle type-specific behavior
- Products use attribute families for dynamic attributes
- Product flats (`product_flats` table) cache product data per locale/channel

### Multi-Channel & Multi-Locale

The platform is built around channels and locales:
- Channels represent different storefronts
- Each channel has associated locales and currencies
- Product data, inventory, and pricing can vary by channel

### Repository Pattern

Repositories extend from a base Repository class and handle all data operations. Always use repositories rather than direct model access.

## Development Commands

### Installation & Setup
```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Install Bagisto (runs migrations, seeders, publishes assets)
php artisan bagisto:install

# Publish assets from packages
php artisan bagisto:publish
```

### Database
```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Generate fake data for testing
php artisan bagisto:fake
```

### Development Server
```bash
# Start development server
php artisan serve

# Compile frontend assets
npm run dev

# Build for production
npm run build
```

### Testing
```bash
# Run all tests
php artisan test
# or
./vendor/bin/phpunit

# Run specific test suite
php artisan test --testsuite="Core Unit Test"
php artisan test --testsuite="Admin Feature Test"
php artisan test --testsuite="Shop Feature Test"
php artisan test --testsuite="DataGrid Unit Test"

# Run specific test file
php artisan test packages/Webkul/Core/tests/Unit/SomeTest.php
```

### Code Quality
```bash
# Run Laravel Pint (code formatter)
./vendor/bin/pint

# Format specific files
./vendor/bin/pint app/
./vendor/bin/pint packages/Webkul/Product/
```

### Maintenance
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check Bagisto version
php artisan bagisto:version
```

## Configuration

### Environment Variables

Key environment variables in `.env`:
- `APP_URL` - Application URL
- `APP_ADMIN_URL` - Admin panel path (default: `admin`)
- `APP_TIMEZONE` - Default timezone
- `APP_LOCALE` - Default locale
- `APP_CURRENCY` - Default currency
- Database configuration: `DB_*`
- Response cache: `RESPONSE_CACHE_ENABLED`
- Elasticsearch settings (if using)
- Social login credentials: `FACEBOOK_*`, `GOOGLE_*`, etc.
- PayPal credentials
- Mail configuration: `MAIL_*`

### Module Registration

Modules are registered in `config/concord.php`. When creating new packages, add their `ModuleServiceProvider` to the modules array.

## Themes

Themes are located in `themes/` directory:
- `themes/admin/` - Admin panel theme
- `themes/shop/` - Storefront theme
- `themes/installer/` - Installation wizard theme

Themes contain Blade templates, Vue components, and assets.

## API

REST API endpoints are defined in `routes/api.php`. The API uses Laravel Sanctum for authentication.

## Important Patterns

### Creating New Packages

1. Create package directory under `packages/Webkul/YourPackage/`
2. Create `src/Providers/ModuleServiceProvider.php`
3. Register in `config/concord.php`
4. Add PSR-4 autoload mapping in `composer.json`
5. Run `composer dump-autoload`

### Working with Products

- Use `Webkul\Product\Repositories\ProductRepository` for product operations
- Access product type instance: `$product->getTypeInstance()`
- Product flats are automatically managed through observers

### Multi-tenant Considerations

- Always use `core()->getCurrentChannel()` to get current channel context
- Filter queries by channel when needed
- Use `core()->getCurrentLocale()` for locale-specific operations

## File Structure

- `app/` - Standard Laravel application directory (minimal, most logic in packages)
- `packages/Webkul/` - Core business logic organized by domain
- `themes/` - Blade templates and frontend assets
- `routes/` - Route definitions (web, api, console, channels, breadcrumbs)
- `config/` - Configuration files
- `database/migrations/` - Base migrations (package migrations in their respective packages)
- `public/` - Public assets and entry point
- `storage/` - Logs, cache, uploaded files
- `resources/` - Additional resources
