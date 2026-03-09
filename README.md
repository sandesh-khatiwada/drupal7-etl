# Explain This Later

> A Drupal 7–based personal productivity web application that helps users revisit concepts they don't understand yet.

Users can save confusing links or ideas, set a future reminder, receive automated email notifications, reflect on their learning, and track their progress visually.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Core Workflow](#core-workflow)
- [Architecture](#architecture)
- [Contributed Modules](#contributed-modules)
- [Custom Modules](#custom-modules)
- [APIs Used](#apis-used)
- [Key Features](#key-features)
- [Purpose](#purpose)

---

## Project Overview

Many learners encounter concepts they do not fully understand but intend to revisit later. This application provides a structured system to:

- Save confusing concepts or references
- Set a reminder date and time
- Receive automated email notifications
- Reflect on the learning experience
- Track progress visually over time

---

## Core Workflow

### 1. Create Deferred Item

Users create a custom content type called **Deferred Item** with the following fields:

| Field | Details |
|---|---|
| Title | Required |
| Description | Required |
| What confused them | Required |
| Reference URL | Optional |
| Supporting documents | Optional |
| Reminder date & time | Required |

**Initial Status:** `Pending`

### 2. Reminder Notification

The system automatically sends an email reminder at the scheduled date and time.

### 3. Concept Review

After revisiting the concept, the user marks the item as:

- `Learned`
- `Dropped`

Users may optionally add a **Reflection Log** describing their learning experience.

### 4. Reflection Logging

A custom entity called **Reflection Log** stores:

- Difficulty level (Confidence Rating: Again / Hard / Good / Easy / Very Easy)
- Learning reflection notes
- Whether another reminder is required

If the confidence rating is set to **Again (1)**, the user provides a new reminder date and time. The system then:

1. Updates the Deferred Item's reminder date
2. Queues a new reminder job via **Drupal Queue** (`etl_reminder_queue`)
3. Sends the reminder email via **cron** when the scheduled time is reached

### 5. Progress Tracking

Learning progress is visualized using interactive charts displaying:

- Completion statistics
- Learning trends
- Status distribution of items

### 6. Calendar View

A calendar interface helps users track their learning schedule by displaying:

- Upcoming reminders
- Pending reviews
- Missed reminders
- Completed items

---

## Architecture

**Drupal Version:** Drupal 7 (Core + Contributed + Custom Modules)

---

## Contributed Modules

| Module | Role |
|---|---|
| **Views** | Displays Deferred Items in structured lists with search, sorting, and filtering |
| **Token** | Provides dynamic placeholder tokens for automated email templates |
| **SMTP** | Configures authenticated mail delivery for reliable reminder emails |
| **Rules** | Automates workflows such as scheduling and sending reminder emails |
| **Pathauto** | Automatically generates clean URL patterns for content |
| **Link** | Provides a field type for storing and validating URLs on Deferred Items |
| **Entity API** | Enables structured handling of custom entity types like Reflection Logs |
| **Better Exposed Filters** | Enhances Views filters with auto-submit and improved UI widgets |
| **Devel** | Developer utility for debugging, query logging, and test data generation |
| **Admin Menu** | Provides fast, structured administrative navigation |

---

## Custom Modules

### `etl_custom_forms`

Builds, validates, and processes the custom submission form for creating Deferred Items using Drupal FormAPI.

**Responsibilities:**
- Form construction
- Client-side validation
- Server-side validation
- Submission handling

> Interacts with Drupal core nodes.

---

### `etl_calendar`

Displays user reminders and Deferred Items in an interactive calendar interface (day/week/month views) using **FullCalendar**.

**Responsibilities:**
- Calendar visualization
- Reminder scheduling display
- Status-based event highlighting

> Reads Drupal core content.

---

### `etl_progress`

Visualizes user learning progress through charts and analytics dashboards using **Chart.js**.

**Displays:**
- Completed items
- Pending reviews
- Dropped items
- Learning trends over time

> Reads Drupal core data and custom entity data.

---

### `etl_deferred_item`

Core domain module managing the full lifecycle of Deferred Items.

**Responsibilities:**
- Status transitions (`Pending` → `Learned` / `Dropped`)
- Reminder scheduling logic
- Reflection workflow handling
- Business rules enforcement

**Custom Entity Defined — Reflection Log:**
- Stores reflection notes
- Tracks difficulty level
- Manages follow-up reminders

**Testing:**
- Drupal 7 web tests
- Unit test cases for lifecycle validation

> Creates and manages a custom entity and extends Drupal core node workflows.

---

### `demo` *(Not part of the main project)*

A demonstration module created for learning purposes to understand Drupal custom module development.

Implements a basic CRUD workflow by:
- Creating a custom database table
- Populating it with sample data
- Providing interfaces to Create, Read, Update, and Delete records

> This module is intentionally retained to showcase foundational Drupal development concepts. Uses custom tables.

---

## APIs Used

| API | Purpose |
|---|---|
| **FormAPI** | Form building and validation |
| **DatabaseAPI** | Database interactions |
| **EntityAPI** | Custom entity management |
| **Drupal Mail API** | Email notifications |
| **Drupal Queue API** | Queues reminder jobs when a new reminder date is set via Reflection Log |
| **Drupal Cron** | Processes the reminder queue and dispatches emails at the scheduled time |
| **Chart.js** | Analytics visualization |
| **FullCalendar** | Scheduling interface |

---

## Key Features

- Structured deferred learning system
- Automated email reminders
- Custom entity implementation
- Lifecycle state management
- Reflection logging system
- Data visualization dashboards
- Calendar-based learning scheduler
- Advanced filtering and search
- Modular and scalable architecture

---

## Purpose

This project demonstrates:

- Advanced Drupal 7 development
- Custom entity architecture
- Workflow automation using Rules
- Integration of third-party JavaScript libraries
- Modular system design principles
- Full-stack feature implementation within Drupal
- Automated scheduling and notification systems
- Test-driven feature validation
