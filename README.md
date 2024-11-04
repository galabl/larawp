# LaraWp

**LaraWp** is a WordPress plugin template designed to simplify and accelerate the development of custom WordPress plugins with a structured and modular approach. It provides a streamlined setup using `larawp-framework` and allows developers to quickly scaffold plugins with customizable namespaces, file structures, and essential boilerplate.

## Features

- Laravel-inspired WordPress plugin structure
- Namespace, file, and dependency scaffolding
- Includes **LaraWp Framework** for core functionality
- Easy to customize for any project

## Installation

To create a new WordPress plugin project using **LaraWp**, simply run:

```bash
composer create-project galabl/larawp <plugin-name>
```

Replace <plugin-name> with the desired name for your plugin (e.g., user-roles). This will set up a new directory with the specified name, creating a WordPress plugin template that includes LaraWp Framework.
Usage

    Project Setup: After running composer create-project, the installer will scaffold the necessary files and update the namespace based on the provided plugin name.
    Development: Customize the generated files according to your plugin’s functionality. The initial structure includes directories for controllers, models, views, and other essential parts.
    Namespace Replacement: The installer script replaces placeholders (LaraWp, lara_wp, lara-wp) with the namespace, underscore, and hyphen names derived from your project name.

Directory Structure

The plugin follows a standard structure similar to Laravel’s, but tailored for WordPress:

```bash

plugin-name/
├── app/                # Core application files
├── includes/           # Additional functionality and helper functions
├── database/           # Database migrations or seeders if necessary
├── plugin-name.php     # Main plugin file
└── composer.json       # Composer dependencies and autoloading
```

## Requirements

    PHP 8.1+
    WordPress 6.1+
    Composer

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss potential changes or improvements.
