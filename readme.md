# Vendata
Everything a retailer needs.

## Console commands
- `php artisan migrate:tenants` - Migrate all tenant databases
- `php artisan superuser:update permissions` - Update the super user with all permissions

## Installation
- Install the Vendata website first, and use the database name "vendata" since a migration in My Vendata references this database
- Copy /resources/assets/js/config.example.js to config.js
- Run `php artisan passport:install` and copy the ID and secret of the "Password grant client" to config.js
- Register through the Vendata website and click the link in the email
- Run `php artisan superuser:update permissions` to update the super user role with all permissions
- Set the user_role_id of the newly created user to the super user  