# NutroPlan  Nutritionist Management System
 Cmpe 492 Final Project  Hasan Hüseyin Kalay, Alp Yıldırım, Muratcan Önder


## Overview

NutroPlan is a comprehensive management system designed for dietitians and nutritionists to manage their clients, appointments, diet plans, and track progress. This project aims to streamline the workflow of dietitians by providing a user-friendly interface and robust backend support.

## Features

- **User Management**: Create and manage dietitian and patient profiles.
- **Appointment Scheduling**: Schedule, update, and manage appointments between dietitians and patients.
- **Diet Programs**: Create and assign personalized diet programs to patients.
- **Reports and Analysis**: Generate reports and analyze patient progress over time.
- **Educational Information**: Access and share educational resources with patients.
- **Patient Metrics Tracking**: Track body metrics such as weight, height, fat percentage, etc.

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Development Tools**: Visual Studio Code

## Installation

### Prerequisites

- PHP >= 7.4
- MySQL
- Apache Server
- Composer (for managing PHP dependencies)

### Setup Instructions

1. **Clone the Repository**:
    ```sh
    git clone https://github.com/HasannKalay/NutroPlan--Nutritionist-Management-System.git
    cd NutroPlan--Nutritionist-Management-System
    ```

2. **Configure the Database**:
    - Create a MySQL database named `nutroplan`.
    - Import the database schema from the `database/nutroplan.sql` file.
    - Update the database connection settings in the `config/db.php` file with your MySQL credentials.

3. **Install Dependencies**:
    ```sh
    composer install
    ```

4. **Start the Server**:
    - Ensure your Apache server is running.
    - Open the project in your browser:
      ```sh
      http://localhost/NutroPlan--Nutritionist-Management-System
      ```

## Usage

1. **User Registration**:
    - Navigate to the registration page to create a new dietitian account.
    - Log in with your credentials.

2. **Dashboard**:
    - Access your personalized dashboard to manage clients, appointments, and diet plans.

3. **Patient Management**:
    - Add new patient profiles and update existing ones.
    - Assign appointments and diet plans to patients.

4. **Metrics Tracking**:
    - Record and track patient metrics over time.
    - Generate and view reports to analyze progress.
