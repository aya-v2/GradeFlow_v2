<img src="assets/logo-o.png" alt="GradeFlow Logo" width="200">
<br/> <br/>

![PHP](https://badgen.net/badge/PHP/8.2/777BB4)
![Symfony](https://badgen.net/badge/Symfony/7.0/000000)
![MySQL](https://badgen.net/badge/MySQL/8.0/005C84)
![Tailwind CSS](https://badgen.net/badge/TailwindCSS/3.4/38B2AC)
![License](https://badgen.net/badge/license/MIT/blue)

GradeFlow v2 is a comprehensive school management system tailored for an engineering school. It allows for the management of students, teachers, classes, and grades.

## Prerequisites

Before setting up the project, ensure you have the following installed:

- **PHP** 8.2 or higher
- **Composer** (Dependency Manager for PHP)
- **Symfony CLI** (Optional but recommended)
- **MySQL** or **MariaDB**
- **Node.js** and **npm** (for building frontend assets)

## Installation Guide

Follow these steps to get the application running locally:

### 1. Clone the Repository

Clone this repository to your local machine:

```bash
git clone https://github.com/aya-v2/GradeFlow_v2.git
cd GradeFlow_v2
```

### 2. Install Dependencies

Install the PHP dependencies using Composer:

```bash
composer install
```

Install the JavaScript dependencies:

```bash
npm install
```

### 3. Build Frontend Assets

Compile the Tailwind CSS styles and other frontend assets:

```bash
npm run build
```

### 4. Configure Environment Variables

1.  Find the `.env` file in the root directory.
2.  Configure your database connection string in the `DATABASE_URL` variable.
    - Example for MySQL:
      ```env
      DATABASE_URL="mysql://root:@127.0.0.1:3306/gradeflow_v2"
      ```
    - _Note: Replace `root` and the password (empty in the example) with your actual database credentials._
3.  Ensure the `MAILER_DSN` is configured if you plan to test email functionalities (e.g., `null://default` for development).

### 5. Setup the Database

You can set up the database by importing the provided SQL dump, which includes the schema and initial data.

1.  Create the database (if not already created):
    ```bash
    php bin/console doctrine:database:create
    ```
2.  Import the `gradeflow_v2.sql` file into your MySQL database. You can use a GUI tool (like phpMyAdmin, HeidiSQL) or the command line:
    ```bash
    mysql -u root -p gradeflow_v2 < gradeflow_v2.sql
    ```

### 6. Serve the Application

Start the local Symfony development server:

```bash
symfony server:start
```

The application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 🔑 Access & Credentials

### Administrator Account

- **Email:** `admin@emsi.ma`
- **Password:** `123456`

### Other Accounts

You can log in as various students or teachers to explore the application's features for different roles.

- Please refer to the **[users.md](users.md)** file in the root directory for a comprehensive list of available accounts and their credentials.

---

## Troubleshooting

- **Database Connection Errors:** Double-check your `DATABASE_URL` in the `.env` file.
- **Missing Styles:** Ensure you have run `npm run build` to generate the Tailwind CSS output.
