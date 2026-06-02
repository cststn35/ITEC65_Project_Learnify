# ITEC65_Project
Work-in-Progress project of Team Quebec for ITEC 65/DCIT 55

ITEC65_Project-main – Project Documentation
Overview
This project is a PHP/MySQL web application called Learnify, developed as an ITEC65/DCIT55 team project. It is a study management platform that helps students organize courses, tasks, study sessions, quizzes, progress tracking, analytics, and personalized coaching.
1. Purpose of the System
Learnify is designed to improve study habits through scheduling, task management, progress monitoring, gamification, analytics, and a Smart Coach recommendation feature.
2. Technology Stack
- Backend: PHP
- Database: MySQL (PDO connection)
- Frontend: HTML, JavaScript, Tailwind CSS
- Package Manager: Composer
- Libraries: smalot/pdfparser, vlucas/phpdotenv
3. Main Project Structure
actions/ – Server-side operations (authentication, tasks, courses, sessions, analytics)
pages/ – Main user interface pages
components/ – Reusable UI components
config/ – Database configuration and ETL scripts
assets/ – Static resources
database/ – Database-related files
4. Core Features
• User Registration and Login
• Dashboard with class schedules
• Course Management
• Task Management
• Study Sessions and Timers
• Quiz Generation and Assessment
• Daily Progress Tracking
• XP and Gamification System
• Notifications
• Analytics Dashboard
• Smart Coach Recommendations
5. Important Files
config/db.php – MySQL database connection.
actions/login-process.php – User authentication.
pages/dashboard.php – Main dashboard interface.
pages/tasks.php – Task management page.
pages/smart-coach.php – Personalized coaching page.
config/runETL.php – Loads data into analytics dimensions and fact tables.
6. Authentication Flow
Users log in using email and password. Credentials are validated against the users table. Passwords are verified using PHP password hashing functions and session variables are created after successful login.
7. Analytics and Smart Coach
The system contains an ETL process that populates dimensional and fact tables. The Smart Coach module calculates study health metrics using consistency, session completion, and quiz performance to generate recommendations.
8. Conclusion
Learnify is a comprehensive academic productivity platform that combines study planning, progress tracking, analytics, and gamification to help students improve learning outcomes.
