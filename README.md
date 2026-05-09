# Form Builder

A powerful, flexible form builder application built with Laravel 12. Create, customize, and manage forms with an intuitive admin interface, and collect submissions through a clean public interface.

## Features

### Form Management
- **Admin Dashboard**: Create and manage forms through a user-friendly admin interface
- **Dynamic Fields**: Support for 12+ field types including text, email, number, phone, textarea, dropdown, checkbox, radio, date, file upload, URL, and password
- **Field Customization**: Configure validation rules, styling, and settings for each field
- **Form Settings**: Set success messages, framework preferences, and other form-level configurations
- **Status Management**: Publish or draft forms as needed

### Field Types Supported
- Text Input
- Email
- Number
- Phone
- Long Text (Textarea)
- Dropdown (Select)
- Checkbox
- Radio Group
- Date Picker
- File Upload
- URL
- Password

### Validation & Styling
- **Built-in Validation**: Required fields, min/max length, regex patterns, and type-specific validation
- **Custom Styling**: Tailwind CSS support with customizable field styles
- **Flexible Settings**: Placeholder text, default values, and field-specific options

### Submission Management
- **Data Collection**: Store form submissions with IP address and user agent tracking
- **Admin Viewer**: View all submissions for each form in the admin dashboard
- **JSON Storage**: Submissions stored as JSON for flexible data handling

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Frontend**: Vite, Tailwind CSS 4.0
- **JavaScript**: Axios for API calls
- **Testing**: PHPUnit
- **Development Tools**: Laravel Sail, Pint (code style), Pail (logs)

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- SQLite (or your preferred database)

### Quick Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd form-builder
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

### Using the Setup Script

The project includes a convenient setup script that handles the installation process:

```bash
composer run setup
```

This will:
- Install PHP dependencies
- Copy `.env.example` to `.env`
- Generate application key
- Run database migrations
- Install Node dependencies
- Build frontend assets

## Usage

### Development Server

Start the development environment with all services:

```bash
composer run dev
```

This starts:
- Laravel server (http://localhost:8000)
- Queue worker
- Log viewer (Pail)
- Vite dev server

### Admin Interface

Access the admin dashboard at `http://localhost:8000/admin`

- **Forms**: Create, edit, and manage forms
- **Submissions**: View form submissions with detailed data

### Public Forms

Published forms are accessible at `http://localhost:8000/form/{slug}`

Example: `http://localhost:8000/form/contact-us`

## Sample Data

The seeder creates sample forms with data:

### Contact Us Form
- Full Name (text, required)
- Email (email, required)
- Phone (phone, optional)
- Subject (dropdown, required)
- Message (textarea, required)

### Job Application Form
- Personal information fields
- Resume upload
- Position selection
- Cover letter

## API Endpoints

### Admin Routes
- `GET /admin/forms` - List all forms
- `POST /admin/forms` - Create new form
- `PUT /admin/forms/{id}` - Update form
- `DELETE /admin/forms/{id}` - Delete form
- `GET /admin/forms/{id}/submissions` - View form submissions

### Public Routes
- `GET /form/{slug}` - Display form
- `POST /form/{slug}/submit` - Submit form data

## Database Schema

### Tables
- `forms` - Form definitions
- `form_fields` - Field configurations
- `field_types` - Available field types
- `form_submissions` - Submission data

### Key Relationships
- Form → hasMany → FormFields
- Form → hasMany → FormSubmissions
- FormField → belongsTo → Form, FieldType

## Testing

Run the test suite:

```bash
composer run test
```

Or directly:

```bash
php artisan test
```

## Code Quality

Format code with Pint:

```bash
./vendor/bin/pint
```

## Deployment

1. Set up your production environment
2. Configure `.env` with production values
3. Run migrations: `php artisan migrate --force`
4. Build assets: `npm run build`
5. Set appropriate permissions for storage and bootstrap/cache

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and code formatting
5. Submit a pull request

## License

This project is licensed under the MIT License.

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

---

**Copyright © 2026** - Made by codeiwthkunal404

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
