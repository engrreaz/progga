# AI-Powered LMS Project

This project is a PHP & JavaScript based web application for personalized learning, automated assessment, and feedback.

## Directory Structure
- includes/: Configuration and template files
- auth/: Login and logout functionality
- assets/: CSS and JS files
- dashboard.php: Main dashboard after login
- index.php: Redirect to login/dashboard

## Setup
1. Copy files to your subdomain root.
2. Update includes/config.php with database credentials.
3. Ensure usersapp table has `username`, `password_hash`, `role` fields.
4. Create new tables using `sql/new_tables.sql`.
5. Navigate to /auth/login.php to access.

## New Tables
Check `sql/new_tables.sql` for CREATE TABLE statements for:
- diagnosis_results
- learning_paths
- syllabus_topics
- syllabus_progress
- feedback_logs
