# Web-Based ERP Platform

---

## Group working on the project

* Mariam Neffeti GL2/1
* Meriam Cherif GL2/1
* Ilef Ben Rahma GL2/1
* Yasmine Bouziri GL2/1
* Marwa Boubakri GL2/3

---
```text
WEB_PROJECT/
│
├── config/
│   ├── database.php
│   ├── database_connection.php
│   ├── session_check.php
│   └── session.php
│
├── database/
│   └── schema.sql
│
├── api/
│   ├── sales.php
│   ├── get_articles.php
│   ├── get_offers.php
│   ├── invoices.php
│   ├── products.php
│   ├── services.php
│   └── clients.php
│
├── fonts/
│   └── fonts
├── palette/
│   └── palette.txt
├── uploads/
│
└── web pages/
    │
    ├── home/
    │   ├── home.php
    │   └── style.css
    │
    ├── login/
    │   ├── login.php
    │   └── style.css
    │
    ├── logout/
    │    └── logout.php
    │
    ├── register/
    │   ├── register.php
    │   └── style.css
    │
    ├── alljob/
    │   ├── alljob.php
    │   ├── alljob.js
    │   └── alljob.css
    |
    ├── articles/
    │   ├── articles.php
    │   ├── add_articles.php
    │   ├── articles.js
    │   ├── delete_article.php
    │   ├── edit_article.php
    │   ├── update_article.php
    │   ├── view_article.php
    │   └── style.css
    │
    ├── clienthome/
    │   ├── clienthome.html
    │   ├── clienthome.js
    │   └── clienthome.css
    │
    ├── image/
    |
    ├── home admin/
    │   ├── home.php
    │   └── style.css
    |
    ├── clients viewE/
    │   ├── clientsE.html
    │   └── clientsE.js
    │
    ├── clients admin/
    │   ├── clients.php
    │   ├── handle_clients.php
    │   ├── style.css
    │   └── clients.js
    │
    ├── cv/
    │   ├── cv.php
    │   ├── cv.css
    │   └── cvv.php
    │
    ├── offre/
    │   ├── offre.php
    │   ├── offre.js
    │   ├── stat.php
    │   └── offre.css
    │
    ├── recruitement/
    │   ├── recruitement.php
    │   ├── style.css
    │   ├── handle_candidates.php
    │   ├── posts_actions.php
    │   └── recruitement.js
    │
    ├── finance/
    │   ├── finance.php
    │   ├── style.css
    │   ├── add_transaction.php
    │   ├── get_finance_chart.php
    │   ├── get_finance_kpis.php
    │   └── finance.js
    │
    ├── stock admin/
    │   ├── stock.php
    │   ├── products.php
    │   ├── stock.js
    │   └── style.css
    │
    ├── rh/
    │   ├── rh.php
    │   ├── add_employee.php
    │   ├── delete_employee.php
    │   ├── edit_employee.php
    │   ├── update_employee.php
    │   ├── view_employee.php
    │   ├── rh.js
    │   └── style.css
    │
    ├── sales/
    │   ├── sales.html
    │   ├── churn_risk.js
    │   ├── style.css
    │   └── sales.js
    │
    ├── sales company/
    │   ├── salesC.php
    │   ├── add_sale.php
    │   ├── add_service_sale.php
    │   ├── api.php
    │   ├── generate_invoice.php
    │   ├── style.css
    │   └── salesC.js
    │
    ├── service admin/
    │   ├── service_admin.php
    │   ├── style.css
    │   ├── management_api.php
    │   └── service_admin.js
    │
    ├── service employee/
    │    ├── service_employee.html
    │    └── employee-dashboard.js
    │
    ├── profil/
    │    ├── edit_profil.php
    │    ├── style.css
    │    └── profil.php
    │
    ├── squelettes entreprise/
    │    ├── footer.php
    │    └── header.php
    │
    ├── squelleteuser/
    │    ├── footeruser.php
    │    └── header.php
    │
    └── stock_employee/
         ├── stock.html
         └── stock.js
    
```
## Project Description

This project is a **web-based ERP-like platform** designed to connect **companies**, **employees**, and **normal users (clients/applicants)** in a single structured system.

The main goal of the project is to **practice and apply web development concepts** learned during the semester (HTML, CSS, JavaScript, PHP, databases) by building a realistic and modular web application.

The platform focuses primarily on **core web functionalities** such as authentication, role-based access, data management, forms, dashboards, and structured navigation.
Some features mentioning AI are considered **optional future enhancements** and will only be implemented if time and skills allow.

---

## Project Objectives

* Build a complete web application with multiple user roles
* Practice front-end structure and design (HTML & CSS)
* Implement authentication and role-based navigation
* Manage structured data (users, companies, sales, applications, etc.)
* Design a scalable and well-organized project architecture
* (Optional) Explore simple AI-assisted features if time permits

---

## User Roles

The platform supports **three types of users**:

1. **Normal User (Client / Applicant)**
2. **Employee**
3. **Company**

Each role has access to different pages and functionalities.

---

## Pages Overview

### 1. Public Pages

#### Home Page (`home.html`)

* Presentation of the platform
* Description of available features
* Contact information
* Buttons to **Login** or **Register**

---

### 2. Authentication Pages

#### Login Page (`login.html`)

* Login using email and password
* Redirects users based on their role

#### Registration Page (`registration.html`)

Users must choose their role during registration:

* **Normal User**: email and password
* **Employee**: email, password, and selection of their company from a list
* **Company**: email, password, and company name

---

## Normal User Features

After login, a normal user can:

### Articles Feed

* View articles related to companies using the platform
* *(Optional future feature)* Article filtering using AI

### Company Offers

* Browse offers posted by companies
* Apply to offers directly

### CV Management

* Upload multiple CV versions
* Select which CV to attach when applying
* Automatically attach the selected CV to an application
* View uploaded CVs and pending applications

---

## Employee Features

Employees have access to company-related management tools:

### Clients Management

* View and manage clients
* Add new clients
* *(Optional future feature)* Suggestions based on client behavior

### Sales Management

* View sales list
* Add or delete sales
* Automatically generate an invoice for each sale

### Services

* View scheduled meetings using a calendar
* Organize services and appointments
* *(Optional future feature)* AI assistant for support

---

## Company Features

Companies have full control over their data:

### Clients & Employees

* View and manage clients
* View and manage employees

### Sales

* Full access to sales data and invoices

### Recruitment

* Post offers
* View applicants
* Accept or reject candidates
* Manage posted offers

### Finance

* View financial charts
* Revenue and performance overview

### Services

* Schedule meetings
* Manage appointments
* *(Optional future feature)* AI assistant

### Articles

* View articles related to:

  * Market trends
  * Competitors
  * Finance topics
* *(Optional future feature)* AI-based article filtering

---

## AI Features (Optional / Future Work)

Any feature involving **AI** (article filtering, suggestions, assistants) is considered **secondary** and will only be implemented if time allows.

The **main focus of the project** remains:

* Web architecture
* User roles
* Data management
* Interfaces and workflows

AI features are **not required for the project to be complete**.

---

## Technologies

### Current Focus

* HTML
* CSS

### Later in the Semester

* JavaScript
* PHP
* Database

### Optional

* Simple AI integration (if feasible)

---

## Project Scope

This project is designed to be:

* Realistic
* Modular
* Scalable
* Achievable within one semester


