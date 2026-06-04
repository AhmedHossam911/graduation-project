<p align="center">
  <img src="public/IMGs/Hu Logo 1.png" alt="Helwan University Logo" width="160"/>
</p>

<h1 align="center">Fellowship Fund Management System</h1>
<p align="center">
  <strong>A comprehensive digital platform for university fellowship fund operations</strong><br/>
  Built with Laravel 12 · MySQL · Blade · Vite
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"/>
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/Vite-Frontend-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite"/>
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License"/>
</p>

---



## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [System Architecture](#-system-architecture)
- [User Roles](#-user-roles)
- [Core Modules](#-core-modules)
- [Database Schema](#-database-schema)
- [Getting Started](#-getting-started)
- [Project Structure](#-project-structure)
- [Security](#-security)
- [Screenshots](#-screenshots)
- [Future Roadmap](#-future-roadmap)

---

## 🎯 Overview

The **Fellowship Fund Management System** is a full-stack web application developed as a graduation project for **Helwan University**. It digitizes and automates all operations of a university fellowship fund — replacing traditional paper-based workflows with a **secure, scalable, and role-based digital platform**.

The system covers the full lifecycle of fellowship fund operations: from member onboarding and subscription management, to loan issuance, installment tracking, claims processing, and financial reporting — all under a unified, auditable interface.

---

## ✨ Features

### 🔐 Authentication & Security
- Secure login with session management
- OTP-based password recovery via email
- Role-Based Access Control (RBAC) with fine-grained permissions
- Account activation / restriction by admins
- Full audit trail of every action in the system

### 👨‍💼 Admin Panel
- System-wide analytics dashboard (members, loans, claims, revenue)
- User management: create, edit, soft-delete, restrict accounts
- Role & permission management (RBAC engine)
- Department management with status control
- Dynamic system settings (maintenance mode, business rules)
- Complete audit log viewer with filtering
- Reports & Excel export

### 👷 Employee Panel
- Member registration with multi-step digital form
- Advanced member search & filtering
- Subscription management with recurring payment generation
- Payment verification (bank reference / cash receipt)
- Loan lifecycle management (create → approve → track installments → close)
- Claims processing with document uploads and status workflow
- Finance dashboard with revenue/expense overview

### 📊 Reporting
- Income statements
- Membership statistics
- Loan performance & overdue tracking
- Exportable reports (Excel via Maatwebsite/Excel)

### 🔔 Notifications
- In-app notification system
- Payment reminders
- Membership expiration alerts
- Loan installment due alerts

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | Laravel 12 (PHP 8.2+) |
| **Frontend** | Blade Templates + Vanilla CSS + JavaScript |
| **Build Tool** | Vite |
| **Database** | MySQL (SQLite for development) |
| **Authentication** | Laravel Session Auth |
| **File Storage** | Laravel File Storage |
| **Excel Export** | Maatwebsite Excel 3.1 |
| **Queue** | Laravel Queue (Redis-ready) |
| **Testing** | PHPUnit 11 |
| **Code Quality** | Laravel Pint |

---

## 🏗 System Architecture

```
fellowship-fund/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin-scoped controllers
│   │   │   ├── Employee/       # Employee-scoped controllers
│   │   │   │   ├── Membership/ # Member, Membership, Subscription
│   │   │   │   ├── Loans/      # Loan lifecycle
│   │   │   │   ├── Claims/     # Claims processing
│   │   │   │   └── Finance/    # Financial overview
│   │   │   └── Auth/           # Authentication
│   │   ├── Middleware/
│   │   │   ├── EnsureAdmin.php
│   │   │   ├── EnsureEmployee.php
│   │   │   └── CheckPermission.php
│   │   └── Requests/           # Form Request validation
│   ├── Models/
│   │   ├── Auth/               # User, Role, Permission
│   │   ├── Financial/          # Loan, Installment, Transaction
│   │   ├── Membership/         # Member, EmploymentInfo, FamilyInfo, Attachment
│   │   ├── Services/           # Service layer
│   │   └── System/             # AuditLog, Department, SystemSetting
│   ├── Services/
│   │   └── MemberService.php   # Business logic for member operations
│   ├── Exports/                # Excel export classes
│   ├── Mail/                   # Email notification classes
│   └── Notifications/          # Laravel notification classes
├── resources/views/
│   ├── admin/                  # Admin Blade views
│   ├── employee/               # Employee Blade views
│   ├── auth/                   # Login, OTP, password reset
│   └── layouts/                # Shared layout templates
├── database/
│   ├── migrations/             # 23 migration files
│   ├── seeders/
│   └── factories/
└── routes/
    └── web.php                 # All application routes
```

---

## 👥 User Roles

### 1. 🛡️ General Admin (Super Admin)
> Full system control with unrestricted access

- Dashboard with real-time system analytics
- Create / edit / soft-delete system users
- Assign roles and fine-grained permissions per user
- Manage departments and their activation status
- Configure system settings and business rules engine
- View and export full audit logs
- Access all reports across all modules

### 2. 📋 Membership Employee
> Handles member registration and subscription lifecycle

- Register new members with a structured digital form (Personal, Employment, Family, Documents)
- Advanced search and filtering across the member directory
- Generate and manage subscription payment schedules
- Verify payments via bank reference numbers or cash receipts
- Process claims: submit → upload documents → track status (Pending → Approved → Ready → Delivered)

### 3. 💳 Loan Employee
> Manages the full loan lifecycle

- Create loan applications (one active loan enforced per member)
- Approve loans following board decisions
- Automatically generate installment schedules
- Record installment payments with payment proofs
- Handle prepayment requests (eligible after 6 months)

### 4. 👤 General Users
> Authenticated system users with base access

- View in-app notifications
- Manage personal profile
- Reset password securely via OTP

---

## 🧩 Core Modules

### Membership Module
Digitizes the full member lifecycle from registration to termination. Each member record captures:
- Personal details (national ID, marital status, nationality, etc.)
- Employment information (job title, employer, salary, hire date)
- Family members & beneficiaries
- Uploaded documents (national ID, certificates, etc.)
- Membership status (active / suspended / terminated / deceased)

### Financial Module
Tracks all fund financial flows:
- **Subscriptions** — recurring payment definitions with frequency configuration
- **Payments** — polymorphic payment records supporting bank transfer, cash, and cheque
- **Transactions** — categorized revenue and expense tracking
- **Revenues** — subscription income, donations, interest, penalties
- **Expenses** — claims payouts, operations, salaries, maintenance

### Loan Module
Full loan lifecycle management:
- Loan application with principal amount, duration, interest rate
- Auto-calculation of monthly installments
- Status workflow: `pending → approved → active → completed / defaulted / rejected`
- Installment generation and payment recording
- Guarantor assignment
- Early settlement support

### Claims Module
Structured claims processing workflow:
- Claim types: death, disability, retirement, marriage, other
- Document upload and management
- Multi-stage approval: `pending → approved → ready → delivered`
- Amount approval and delivery confirmation

### Reporting Module
- Financial income statements
- Membership statistics and growth
- Loan portfolio performance
- Overdue installment reports
- Export to Excel with one click

### Audit & Security Module
Every meaningful action in the system is recorded:
- User ID, action type, entity affected
- Old vs new values (JSON diff)
- IP address and user agent
- Timestamp-indexed for fast lookup

---

## 🗃 Database Schema

The system comprises **23 database tables** covering all business domains:

| Domain | Tables |
|---|---|
| **Auth** | `users`, `roles`, `permissions`, `role_permissions`, `user_roles` |
| **Membership** | `members`, `employment_info`, `family_info`, `attachments`, `memberships` |
| **Financial** | `subscriptions`, `loans`, `installments`, `transactions` |
| **Claims** | `claims` |
| **System** | `departments`, `system_settings`, `audit_logs`, `notifications`, `otp_codes` |

Key design decisions:
- **Polymorphic relationships** on payments and documents for maximum flexibility
- **Soft deletes** on users to preserve data integrity
- **Composite indexes** on frequently queried fields (`member_id + status`, `entity_type + entity_id`)
- **Business rule enforcement** at the application layer (one active loan per member)
- **JSON columns** for dynamic rule definitions and audit change sets

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL (or use the bundled SQLite for development)
- XAMPP / Laravel Herd / any PHP environment

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/AhmedHossam911/graduation-project.git
cd graduation-project

# 2. Install PHP dependencies
composer install

# 3. Set up environment
cp .env.example .env
php artisan key:generate

# 4. Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fellowship_fund
DB_USERNAME=root
DB_PASSWORD=

# 5. Run database migrations
php artisan migrate

# 6. (Optional) Seed demo data
php artisan db:seed

# 7. Install frontend dependencies and build assets
npm install
npm run build

# 8. Start the development server
php artisan serve
```

### One-Command Setup (via Composer script)

```bash
composer run setup
```

### Development Mode (all services concurrently)

```bash
composer run dev
```

This starts the PHP server, queue worker, log viewer (Pail), and Vite dev server simultaneously.

---

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/      # Feature controllers by role
│   ├── Http/Middleware/       # Role & permission guards
│   ├── Http/Requests/         # Form validation
│   ├── Models/                # Eloquent models (grouped by domain)
│   ├── Services/              # Business logic layer
│   ├── Exports/               # Excel export definitions
│   ├── Mail/                  # Mailable classes
│   └── Notifications/         # Laravel notifications
├── database/
│   ├── migrations/            # Schema migrations (23 files)
│   ├── seeders/               # Database seeders
│   └── factories/             # Model factories for testing
├── public/
│   ├── IMGs/                  # Application images & logos
│   ├── css/                   # Compiled CSS
│   └── JS/                    # Compiled JavaScript
├── resources/
│   ├── css/                   # Source CSS
│   ├── js/                    # Source JavaScript
│   └── views/                 # Blade templates (admin, employee, auth)
├── routes/
│   └── web.php                # All application routes
├── storage/                   # File uploads, logs, cache
└── tests/                     # PHPUnit test suite
```

---

## 🔐 Security

| Feature | Implementation |
|---|---|
| Authentication | Laravel Session-based Auth |
| Authorization | RBAC — Roles + Permissions via middleware (`CheckPermission`, `EnsureAdmin`, `EnsureEmployee`) |
| Password Recovery | OTP-based (6-digit code, time-limited, single-use) |
| Input Validation | Laravel Form Requests with strict rules |
| Audit Logging | Every create / update / delete / login action is logged with old/new values |
| Soft Deletes | User records are never hard-deleted — data integrity preserved |
| Account Restriction | Admins can deactivate accounts without deleting them |
| File Uploads | Stored via Laravel File Storage with controlled access paths |

---

## 📸 Screenshots

> Application screenshots from the live system.

| Feature | Preview |
|---|---|
| Dashboard | `public/IMGs/Dashboard-no-members.png` |
| Loans View | `public/IMGs/loans.png` |
| Empty State | `public/IMGs/No-results.png` |

---

## 🚀 Future Roadmap

- [ ] **Payment Gateway Integration** — Fawry & Visa payment processing
- [ ] **SMS Notifications** — Real-time SMS alerts for critical events
- [ ] **AI Fraud Detection** — Anomaly detection on financial transactions
- [ ] **Advanced Analytics Dashboard** — Charts, trends, and KPI tracking
- [ ] **Mobile Application** — React Native companion app
- [ ] **API Layer** — RESTful API with Sanctum for external integrations
- [ ] **Two-Factor Authentication (2FA)**
- [ ] **Docker Deployment** — Production-ready containerization

---

## 👨‍💻 Authors

Developed by **Team 24** as a **Graduation Project** at **BIS Helwan**.

---

## 📄 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

<p align="center">
  Built with ❤️ by Team 24 at Helwan University
</p>
