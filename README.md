# LEARNIFY Documentation

By: **Team Quebec**

---

# 1. Application Overview

The system is a Study and Learning Management System named **LEARNIFY**, designed for students to manage tasks, courses, study sessions, quizzes, and analytics.

It is a web-based universal learning productivity platform designed to help students manage study sessions, quizzes, tasks, schedules, and period-based learning goals through a semi-gamified motivational system using XP and streaks. It also includes a personalized, rule-based Smart Coach that helps students maintain their study habits and reduce attrition through meaningful insights. The system combines OLTP functionality for daily operations with OLAP capabilities for analytics and decision support.

## Purpose

Learnify is designed to help students organize their academic tasks, improve time management, and enhance learning productivity through an all-in-one digital platform that supports studying, tracking progress, and building effective study habits.

### Objectives

* To help students organize academic tasks efficiently
* To support structured study sessions with timers and tracking
* To enhance learning through quizzes and performance analytics
* To promote productivity and better academic outcomes

## Target Users

* Student learners
* Self-learners
* Academic groups

## Key Features

* Task Management
* Study Timer
* Quizzes
* Analytics Dashboard
* Smart Coach

---

# 2. System Functionality

The system includes multiple modules, such as:

* Authentication System (Login/Register)
* CRUD-Based Task Management
* Course Management
* Study Session Tracker
* Quiz Generator and Evaluator
* Analytics Dashboard Using Charts
* Notification System
* Smart Coaching Feature

---

# 3. System Flow

1. User logs in using credentials.
2. User accesses the dashboard.
3. User manages tasks and courses.
4. User starts a study session using the timer.
5. The system logs progress and analytics.
6. User takes quizzes for evaluation.
7. The system displays performance results.

---

# Pages

## Main Pages (User Side)

### Landing Page

The page where potential users can view an overview of the website's features.

### Authentication Page

The page where users register or log in to their accounts.

### Welcome Wizard Page

The page where newly registered users set up their profiles.

### Dashboard Page

Considered the heart of the system, this page serves as a visual interface where students can view their class schedule, key data metrics (such as today's study progress, current streak, and pending tasks), and shortcuts to study sessions and the Smart Coach page.

### Study Sessions Page

A dedicated page where students can create, initialize, and review past study sessions, as well as view corresponding quiz results.

### Study Session Timer Page

The page containing the timer interface and study session notes field while a study session is in progress.

### Study Session Quiz Page

The page where students who opted for a post-study-session quiz answer a multiple-choice assessment.

### Tasks Page

The page where students manage and view their tasks, with filtering options for faster browsing. Users can also initiate a study session linked to a specific task from this page.

### Courses Page

The page where students manage and view their enrolled courses.

### Analytics Page

The page where students can monitor their study performance in detail through interactive charts, gaining insights into their study habits, productivity, academic performance, and gamification achievements.

### Smart Coach Page

Considered the brain of the website, this page acts as a knowledge-based or semi-decision-support system that offers personalized and actionable insights regarding a student's productivity and study behavior.

---

## Admin Pages (Admin Side)

### Admin Dashboard Page

The interface where administrators monitor key data metrics and view recent student activities.

### User Management Page

The page where administrators can view users’ key progress indicators and individual analytics, edit profile information, and soft-delete users.

### User-Wide System Analytics Page

The page where administrators can view key user-wide analytics and request on-demand Excel-formatted reports.

### Reports Page

The page where users and administrators can view descriptive reports and insights generated from analytics.

---

# 4. Technologies Used

## Frontend

* HTML
* CSS
* JavaScript

## Backend

* PHP

## Database

* MySQL

## Package Managers

* NPM
* Composer

## API

* Gemini API (for on-demand quiz generation)

## Libraries and Dependencies

* Chart.js (Analytics Visualization)
* Tailwind CSS (Visual Styling)
* Font Awesome (Icons)
* Boxicons v2 (Icons)
* SweetAlert2 (Pop-up Modals)
* Smalot/PDFParser (PDF Content Extraction)
* Dotenv (Environment Variables)
* PHPSpreadsheet (Excel Report Generation)

## Collaborative Tool

* GitHub (Version Control)

---

# 5. UI/UX Design

## Design Prototype Tool Used

* Figma

The system uses a clean dashboard-based interface with sidebar navigation, modal forms, and a responsive layout designed for ease of use and productivity.

The overall visual theme follows an academic, productivity-oriented, minimalist, data-driven, and professional design. Learnify adopts a modern academic productivity theme, utilizing a clean slate-based color palette, intuitive dashboard layouts, and data-driven visualizations to help students effectively manage their academic responsibilities and learning progress.

## Learnify Color Palette

| Color Role     | Hex Code  | Tailwind Equivalent |
| -------------- | --------- | ------------------- |
| Primary Dark   | `#0F172A` | `slate-900`         |
| Secondary Dark | `#1E293B` | `slate-800`         |
| Background     | `#F1F5F9` | `slate-100`         |
| Surface/Card   | `#FFFFFF` | `white`             |
| Text Primary   | `#0F172A` | `slate-900`         |
| Text Secondary | `#475569` | `slate-600`         |

## Typography

**Inter**

---

# 6. Collaboration

The project was developed by a team using GitHub for collaboration. Members were assigned roles in frontend development, backend development, database management, and documentation.

---

# User Installation and Manual

## Requirements

Before installing the system, ensure that the following software and tools are installed on your machine:

1. PHP 8+
2. MySQL 8+
3. Apache
4. XAMPP
5. Node.js (for NPM-based dependencies)
6. Composer (for PHP-based dependencies)
7. Git Bash

---

# Installation Manual

> **Note:** Before performing the procedures below, make sure all required software and tools are installed on your machine.

### Step 1

Place the main project folder named **study-app** in:

```text
C:\xampp\htdocs
```

### Step 2

Create an empty database named:

```text
studyapp
```

in phpMyAdmin.

### Step 3

In your browser, run:

```text
localhost/study-app/config/initdb.php
```

to initialize the database tables.

### Step 4

Locate the `php.ini` file in:

```text
C:\xampp\php
```

### Step 5

Inside `php.ini`, search for the following lines:

```ini
;extension=zip
;extension=gd
```

Remove the semicolons (`;`) before them to enable the required PHP extensions.

### Step 6

Temporarily disable antivirus software on your machine to avoid dependency installation interruptions during the succeeding procedures.

### Step 7

Open a terminal inside the project directory and run:

```bash
npm install
```

to install all NPM-based dependencies.

Then run:

```bash
composer install
```

to install all PHP-based dependencies.

### Step 8

Create a `.env` file in the root directory of the project folder and add:

```env
GEMINI_API_KEY=your_gemini_api_key
```

Replace `your_gemini_api_key` with your actual Gemini API key.

### Step 9

Once everything is set up, run:

```text
localhost/study-app/landing_page.php
```

to access the landing page of the system.

**Happy Learning!**
