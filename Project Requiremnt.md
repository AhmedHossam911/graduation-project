# 🎓 Fellowship Fund Management System

## Graduation Project Documentation

---

# 📌 Project Overview

The **Fellowship Fund Management System** is a full-stack web application designed to digitize and manage all operations of a university fellowship fund.

The system handles:

- Membership management
- Financial operations (subscriptions, loans, claims)
- Administrative control
- Reporting & analytics

It replaces traditional paper-based workflows with a **secure, scalable, and role-based digital system**.

---

# 🎯 Project Objectives

- Digitize membership processes
- Automate financial transactions
- Provide full administrative control
- Ensure transparency through audit logs
- Improve reporting and decision-making
- Enforce business rules dynamically

---

# 🧠 System Architecture

- **Backend:** Laravel (MVC Architecture)
- **Frontend:** React.js / Blade
- **Database:** MySQL
- **Storage:** Laravel File Storage
- **Authentication:** Laravel Sanctum / JWT
- **Queue System:** Redis (optional)

---

# 👥 User Roles

## 1. General Admin (Super Admin)

### Responsibilities:

- Full system control
- User and permission management
- Financial and operational reporting

### Features:

- Dashboard with system analytics
- User management:
    - Create / Edit / Soft Delete
    - Restrict accounts

- Role-Based Access Control (RBAC)
- System settings:
    - Maintenance mode
    - Dynamic rules engine

- Department & division management
- Full reporting system (Excel export)

### Special Capabilities:

- User impersonation
- Full audit logging of all actions

---

## 2. Membership Employee

### Responsibilities:

- Manage memberships
- Handle subscriptions
- Process claims

### Features:

#### Membership Management:

- Create / Edit / View memberships
- Advanced search & filtering

#### Digital Membership Form:

- Personal Information
- Employment Information
- Family Information
- Attachments upload
- Declaration & approval

#### Subscription Management:

- Automatic payment generation
- Recurring subscriptions
- Payment verification:
    - Bank (reference + statement)
    - Cash (receipt)

#### Claims Management:

- Create claim requests
- Upload required documents
- Status lifecycle:
    - Pending → Approved → Ready → Delivered

---

## 3. Loan Employee

### Responsibilities:

- Manage loan lifecycle

### Features:

- Create loan (1 active per member)
- Approve loans after board decision
- Track installments
- Record payments
- Prepayment after 6 months

---

## 4. General Users

### Features:

- In-app notifications
- Profile management
- Secure password reset (OTP-based)

---

# 🧩 Core Modules

## 1. Membership Module

- Member registration
- Membership lifecycle tracking
- Document management

---

## 2. Financial Module

### Includes:

- Subscriptions
- Loans
- Claims
- Revenues
- Expenses

---

## 3. Loan Module

- Loan issuance
- Installment tracking
- Early settlement

---

## 4. Claims Module

- Request submission
- Approval workflow
- Delivery confirmation

---

## 5. Reporting Module

### Reports:

- Financial reports (Income Statement)
- Membership statistics
- Loans & overdue payments

### Features:

- Drill-down reports
- Export to Excel

---

## 6. Notification System

- Real-time alerts
- Payment reminders
- Membership expiration alerts

---

## 7. Audit & Security Module

### Includes:

- Audit logs (track all actions)
- Suspicious activity detection
- Impersonation tracking

---

# 🗂️ Database Design (Core Entities)

- Users
- Roles & Permissions
- Members
- Memberships
- Payments
- Loans
- Installments
- Claims
- Documents
- Departments / Divisions
- Audit Logs
- Notifications
- OTP Codes

---

# 🔐 Security Features

- Role-Based Access Control (RBAC)
- Secure authentication
- OTP-based password recovery
- Action logging (Audit Logs)
- Data validation & access restrictions

---

# ⚙️ Business Rules Engine

Dynamic rules managed by admin:

- Membership eligibility (age, conditions)
- Loan eligibility rules
- Payment constraints

---

# 📊 Key Features

- Fully role-based system
- Dynamic configuration
- Financial tracking
- Document management
- Advanced reporting
- Secure and scalable design

---

# 🚀 Future Enhancements

- Payment gateway integration (Fawry / Visa)
- SMS notifications
- AI-based fraud detection
- Advanced analytics dashboard

---

# 🏁 Conclusion

This system provides a **complete digital transformation** for managing fellowship funds, ensuring:

- Accuracy
- Transparency
- Efficiency
- Scalability

It is designed with **real-world enterprise standards** and is suitable for deployment in university environments.

---
