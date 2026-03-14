# Explain This Later — Technical Documentation

## Overview

This documentation explains the custom modules used in the project, including architecture decisions, entity structure, queue and cron workflows, chatbot logic, testing approaches, and more.

The system demonstrates several Drupal capabilities:

- Node-based content management
- Custom entity architecture
- Form API usage
- Queue-based background processing
- AJAX interfaces
- Data visualization

### Custom Modules

| Module | Purpose |
|---|---|
| `etl_custom_forms` | Deferred item creation form |
| `etl_calendar` | Interactive learning calendar |
| `etl_progress` | Learning progress dashboard |
| `etl_chatbot` | Floating assistant |
| `etl_deferred_item` | Domain module |
| `demo` | CRUD learning/demo module |

Each module contains its own routing, theming, assets, and business logic.

---

## Modules

### 1. `etl_custom_forms` — Deferred Item Creation

Implements a custom node creation form for the `deferred_item` content type using Drupal FormAPI rather than Drupal's default node form.

**Responsibilities:** Form construction, client/server validation, reminder conflict detection, file upload handling, node creation.

#### Routing

The form is exposed at `/deferred-item/add` using `hook_menu()`.

```php
$items['deferred-item/add'] = array(
  'title'            => 'Add Deferred Item',
  'page callback'    => 'drupal_get_form',
  'page arguments'   => array('etl_custom_forms_add_form'),
  'access arguments' => array('create deferred_item content'),
  'type'             => MENU_CALLBACK,
);
```

#### Form Fields

- Title
- Reference URL
- Description
- Personal Note
- Reminder Date (Date module `date_popup`)
- File attachment (PDF/image)

> The learning status is locked to "Pending" during creation using a hidden value field.

#### File Upload

Uses `managed_file` with validators:

- Allowed extensions: images, PDF
- Maximum size: 5MB

#### Client-Side Validation

jQuery Validate is used for real-time validation. Validation rules and messages are passed from PHP to JavaScript via Drupal settings. A custom `looseUrl` rule accepts URLs with or without protocol prefixes.

#### Server-Side Validation

The validate handler enforces:

- Reminder must be in the future
- Content quality rule: if no link and no note, description must be ≥ 20 characters
- Conflict detection with other reminders

Two conflict types are checked using `EntityFieldQuery`:

| Type | Logic |
|---|---|
| Exact conflict | Same reminder timestamp |
| Near conflict | Reminder within ±30 minutes |

Near conflicts trigger a two-pass form rebuild where the user confirms whether to continue.

#### Submit Logic

The submit handler:

1. Manually constructs a node object
2. Assigns field values
3. Saves the node with `node_save()`
4. Marks uploaded files as permanent
5. Registers file usage to prevent garbage collection

---

### 2. `etl_calendar` — Interactive Learning Calendar

Displays a user's reminders using FullCalendar.

**Responsibilities:** Fetch reminder data, classify events, render calendar UI.

#### Routing and Theming

Available at `/calendar`. A custom template (`etl-calendar-page.tpl.php`) renders the calendar container and status legend.

#### Data Query

Reminder data is retrieved using `db_select` with joins on:

- `field_revision_field_learning_status`
- `field_revision_field_reminder_date`

Using revision tables ensures values always reflect the node's latest revision.

#### Event Classification

Each reminder is categorized by timing (past/future) and learning status:

| Timing | Status | Class | Color |
|---|---|---|---|
| Past | Learned | `event-completed` | Green |
| Past | Pending | `event-missed` | Red |
| Past | Dropped | `event-dropped` | Gray |
| Future | Pending | `event-upcoming-pending` | Orange |
| Future | Learned | `event-upcoming-learned` | Light green |
| Future | Dropped | `event-upcoming-dropped` | Light gray |

#### JavaScript Integration

Events are passed to JavaScript through Drupal settings and consumed by FullCalendar. Drupal behaviors use a `.once()` guard to prevent duplicate initialization during AJAX behavior attachment. Each calendar event links directly to its corresponding node page.

---

### 3. `etl_progress` — Learning Progress Dashboard

Generates learning analytics visualizations using Chart.js.

**Responsibilities:** Run statistical queries, compute learning metrics, render charts.

#### Data Collection

Three database queries generate dashboard data:

1. Total items
2. Status distribution
3. Monthly learned items (last 12 months)

Status counts are normalized to always include: Pending, Learned, Dropped.

Monthly statistics group results using MySQL:

```sql
DATE_FORMAT(FROM_UNIXTIME(n.created), '%Y-%m')
```

Because missing months are not returned by SQL, a gap-filling loop inserts zero values to maintain a continuous 12-month dataset.

#### Completion Metric

```
completion = (learned / total) * 100
```

#### Visualizations

| Chart | Description |
|---|---|
| Pie chart | Status distribution with custom tooltip percentages |
| Line chart | Monthly learned trend (last 12 months) |

The progress bar width is set directly from the computed completion percentage.

---

### 4. `etl_chatbot` — Floating Assistant

Provides a persistent chatbot UI accessible on all authenticated pages.

**Responsibilities:** Global UI injection, AJAX message handling, rule-based query routing, reminder/stat retrieval.

#### UI Injection

Two hooks insert the chatbot without modifying theme templates:

- `hook_init()` — Loads JavaScript, CSS, and passes the AJAX endpoint URL
- `hook_page_alter()` — Injects chatbot markup into `$page['page_bottom']`

#### AJAX Endpoint

Route: `/etl-chatbot/ajax`

Workflow:

1. Sanitize user input (`filter_xss`)
2. Process query
3. Return response via `drupal_json_output()`

#### Query Routing

Queries are handled by keyword detection:

| Input | Handler |
|---|---|
| `hi` / `hello` | Greeting |
| `today` | Reminders for today |
| `upcoming` / `next` | Upcoming reminders |
| `YYYY-MM-DD` | Reminders by date |
| `progress` / `stats` | Learning statistics |
| (otherwise) | Fallback response |

#### Performance Strategy

Reminder queries use a fetch-6-display-5 pattern:

- Retrieves 6 records, displays only 5
- If 6 exist → shows "and more..."

This avoids expensive `COUNT(*)` queries.

#### Frontend Behaviour

- Typing animation
- Dynamic response insertion
- Button click rebinding to prevent duplicate handlers

---

### 5. `etl_deferred_item` — Domain Module

The central module managing the entire Deferred Item lifecycle.

**Responsibilities:** Status transitions, ownership access control, custom entity definition, reflection workflow, queue-based reminder emails, automated testing.

#### Routing and Access

Lifecycle routes:

- `mark-learned`
- `drop`

Routes use `%node` wildcards so Drupal automatically loads the node. Access control ensures only the node owner can perform actions.

#### Status Transitions

Two transitions are supported:

- Pending → Learned
- Pending → Dropped

A shared helper `_etl_status_update()` updates the node field and saves the node.

#### Custom Entity: `reflection_log`

A custom entity type records learning reflections.

| Column | Description |
|---|---|
| `id` | Primary key |
| `uid` | Author |
| `deferred_item_id` | Related node |
| `created` / `changed` | Timestamps |
| `reflection_note` | Text |
| `confidence_rating` | 1–5 scale |

The entity controller updates the `changed` timestamp automatically on save.

#### Reflection Form

Users can log reflections after reviewing a concept. Drupal `#states` API conditionally displays a new reminder date when the confidence rating is *Again* (1). Conflict detection reuses the same exact / ±30 minute logic used in the creation form.

#### Queue-Based Reminder System

When a reflection indicates *Again*, a reminder job is added to a `DrupalQueue`:

```php
DrupalQueue::get('etl_reminder_queue')->createItem(...)
```

Queue configuration is defined via `hook_cron_queue_info()`.

**Worker logic:**

1. **Future requeue** — if reminder time is far in the future, the job is requeued
2. **Email dispatch** — reminder emails are sent via `drupal_mail()`
3. **Retry logic** — failed sends are retried up to 5 times, after which an error is logged

#### Automated Testing

**Unit test** — uses `DrupalUnitTestCase` to verify the reflection access callback.

**Web test** — uses `DrupalWebTestCase` for full integration tests:

- Mark item as Learned
- Drop item
- Submit reflection

Each test verifies HTTP response, success messages, and persisted database state.

---

### 6. `demo` — CRUD Learning Module

A practice module used to explore Drupal module development. Implements a full CRUD workflow using Drupal's Database API rather than nodes or entities.

#### Database Schema

Custom table `demo_items`:

| Field | Type |
|---|---|
| `id` | Serial (PK) |
| `title` | Varchar |
| `description` | Text |
| `weight` | Integer |
| `created` | Timestamp |

Sample data is inserted during `hook_install()` and removed during `hook_uninstall()`.

#### CRUD Routes

| Route | Description |
|---|---|
| `list` | View all items |
| `add` | Add new item |
| `edit` | Edit existing item |
| `delete` | Confirm and delete item |

Add and edit operations share a single form builder (`demo_item_form`). Deletion uses Drupal's `confirm_form()`.

---

## Cross-Cutting Design Patterns

Several implementation patterns are consistently used across modules.

### Drupal JS Settings Bridge

PHP values are passed to JavaScript via:

```php
drupal_add_js($data, 'setting')
```

Used for: chatbot AJAX URL, chart data, calendar events, and validation messages.

### Drupal Behaviors + `.once()`

Ensures JavaScript initialization runs only once even when `Drupal.attachBehaviors()` executes multiple times.

### Two-Pass Conflict Detection

Forms rebuild when a near reminder conflict is detected, allowing the user to confirm the scheduling decision.

### Query Reuse via `clone`

A base `EntityFieldQuery` is cloned for multiple related queries to avoid duplicated conditions.

### Output Sanitization

All rendered output uses `check_plain()` to prevent XSS.

### Revision Table Queries

Queries join `field_revision_*` tables rather than `field_data_*` to guarantee the latest revision values are used.

---

## Conclusion

Together, the five domain modules form a complete learning-deferral workflow: items are created with scheduling constraints, visualized on a calendar, tracked through a progress dashboard, managed via a floating assistant, and finally closed out through a reflection system that can reschedule itself automatically. The architecture is designed to be extended — new status transitions, additional chatbot handlers, or extra chart types can be added to their respective modules without affecting the others.