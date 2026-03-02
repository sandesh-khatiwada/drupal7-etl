# Drupal 7 Custom Module Demo

A simple **Drupal 7** demonstration module that:

- Creates a custom database table during installation (`demo_items`)
- Populates the table with initial sample data
- Provides a page (`/demo-data`) showing all records in a HTML table
- Allows adding a new item, updating existing items, and deleting them
- Uses a custom theme hook + `.tpl.php` template for rendering
- Demonstrates usage of `db_select()`, `db_insert()`, `db_update()`, `db_delete()`, `drupal_get_form()`, `theme('table')`, `hook_menu()`, `hook_theme()`, form validation, and custom template theming for a complete CRUD workflow.


## Requirements

- Drupal 7.x
- PHP 5.3+ (as required by Drupal 7)

## Installation

1. Download or clone this repository
2. Place the folder in:
   - `sites/all/modules/custom`  

3. Enable the module at `/admin/modules`
4. Visit `/your-drupal-site-name/demo-data`

