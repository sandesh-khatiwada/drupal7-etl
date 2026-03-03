# Explain This Later

**Explain This Later** is a Drupal 7–based personal productivity web application that helps users revisit concepts they don’t understand yet.

It allows users to save confusing links or ideas, set a future reminder, receive automated email notifications, reflect on their learning, and track their progress visually.

---

## 🚀 Project Overview

Many learners encounter concepts they do not fully understand but intend to revisit later. This application provides a structured system to:

- Save confusing concepts or references
- Set a reminder date and time
- Receive automated email notifications
- Reflect on the learning experience
- Track progress visually over time

---

## 🔄 Core Workflow

1. **Create Deferred Item**
   - User adds a custom content type: **Deferred Item**
   - Fields include:
     - Title
     - Description
     - What confused them
     - Reference URL (if any)
     - Documents (if any)
     - Reminder date & time
   - Initial state: `Pending`

2. **Reminder Notification**
   - System sends an automated email on the scheduled reminder date/time.

3. **Concept Review**
   - User revisits the concept.
   - Marks it as:
     - `Learned`
     - `Dropped`
   - Optionally adds a **Reflection Log** (custom entity).

4. **Reflection Logging**
   - Custom entity: `Reflection Log`
   - Stores:
     - Difficulty level
     - Learning reflection
     - Whether another reminder is needed

5. **Progress Tracking**
   - Visualized using **Chart.js**
   - Displays learning statistics and trends.

6. **Calendar View**
   - Implemented using **FullCalendar**
   - Shows:
     - Upcoming items
     - Pending items
     - Missed reminders
     - Completed items

---

## 🧩 Architecture

### Drupal Version
- Drupal 7 (Core + Contributed + Custom Modules)

---

## 📦 Contributed Modules Used

- Views
- Token
- SMTP
- Rules
- Pathauto
- Link
- Entity
- Better Exposed Filters

---

## 🛠 Custom Modules

### 1. Custom Form Module
- Form creation using Drupal FormAPI
- Client-side and server-side validation
- Submission handling

### 2. Calendar Module
- Integrates FullCalendar
- Displays deferred items by status and reminder date

### 3. Progress Visualization Module
- Integrates Chart.js
- Generates learning analytics and progress charts

### 4. Lifecycle & Reflection Module
- Manages state transitions:
  - Pending → Learned / Dropped
- Defines custom entity type: `Reflection Log`
- Handles reminder logic and item lifecycle

---

## 🔧 APIs Used

- FormAPI
- DatabaseAPI
- EntityAPI
- JavaScript API (for Chart.js & FullCalendar integration)

---

## 📊 Key Features

- Structured deferred learning system
- Automated email reminders
- Custom entity implementation
- Lifecycle state management
- Data visualization
- Calendar-based task tracking
- Clean modular architecture

---

## 🎯 Purpose

This project demonstrates:

- Advanced Drupal 7 development
- Custom entity creation
- Workflow management using Rules
- Integration of third-party JS libraries
- Modular system design
- Full-stack feature implementation inside Drupal

