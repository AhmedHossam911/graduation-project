# Fellowship Fund System - Database Design (Enhanced & Fixed)

## Global Rule
All entities include:
- created_by (FK -> users.id)
- updated_by (FK -> users.id)
- created_at
- updated_at

---

## Users
- id
- name
- email (UNIQUE)
- phone
- password
- profile_picture
- department_id (FK -> departments.id, NULLABLE)
- is_active (BOOLEAN, DEFAULT true)
- last_login_at
- created_by
- updated_by
- created_at
- updated_at
- deleted_at (Soft Delete)

---

## Roles
- id
- name
- description
- is_active (BOOLEAN, DEFAULT true)
- created_by
- updated_by
- created_at
- updated_at

---

## Permissions
- id
- name
- key (UNIQUE, e.g. "members.create")
- module (e.g. "members", "loans", "claims")
- created_by
- created_at
- updated_at

---

## Role Permissions (Pivot)
- id
- role_id (FK -> roles.id, CASCADE)
- permission_id (FK -> permissions.id, CASCADE)
- UNIQUE(role_id, permission_id)

---

## User Roles (Pivot)
- id
- user_id (FK -> users.id, CASCADE)
- role_id (FK -> roles.id, CASCADE)
- UNIQUE(user_id, role_id)

---

## Persons
- id
- user_id (FK -> users.id, NULLABLE, UNIQUE) ← Links person to system user when applicable
- first_name
- second_name
- third_name
- fourth_name
- national_id (VARCHAR(14), UNIQUE)
- date_of_birth
- gender (ENUM: male, female)
- marital_status (ENUM: single, married, divorced, widowed)
- nationality (DEFAULT 'Egyptian')
- email
- phone
- home_phone
- address
- created_by
- updated_by
- created_at
- updated_at

---

## Members
- id
- person_id (FK -> persons.id, UNIQUE)
- member_number (UNIQUE)
- status (ENUM: active, suspended, terminated, deceased)
- join_date
- termination_date (NULLABLE)
- termination_reason (NULLABLE)
- created_by
- updated_by
- created_at
- updated_at

---

## Employments
- id
- member_id (FK -> members.id)
- job_title
- employer_name
- hire_date
- department
- salary (DECIMAL 12,2)
- employment_type (ENUM: full_time, part_time, contract)
- is_current (BOOLEAN, DEFAULT true)
- created_by
- updated_by
- created_at
- updated_at

---

## Family Members
- id
- member_id (FK -> members.id)
- name
- relationship (ENUM: spouse, son, daughter, father, mother, brother, sister, other)
- national_id (VARCHAR(14), NULLABLE)
- date_of_birth (NULLABLE)
- is_beneficiary (BOOLEAN, DEFAULT false)
- created_by
- updated_by
- created_at
- updated_at

---

## Memberships
- id
- member_id (FK -> members.id)
- start_date
- end_date (NULLABLE)
- subscription_amount (DECIMAL 12,2)
- status (ENUM: active, expired, cancelled)
- created_by
- updated_by
- created_at
- updated_at

---

## Subscriptions
- id
- member_id (FK -> members.id)
- membership_id (FK -> memberships.id)
- amount (DECIMAL 12,2)
- frequency (ENUM: monthly, quarterly, annually)
- start_date
- next_due_date
- is_active (BOOLEAN, DEFAULT true)
- created_by
- updated_by
- created_at
- updated_at

---

## Payments
- id
- member_id (FK -> members.id)
- payable_type (VARCHAR — e.g. "membership", "loan", "claim", "subscription")
- payable_id (BIGINT UNSIGNED — FK to the related table row)
- amount (DECIMAL 12,2)
- payment_method (ENUM: bank_transfer, cash, cheque)
- reference_code (NULLABLE — for bank transfers)
- receipt_number (NULLABLE — for cash payments)
- bank_statement_path (NULLABLE — uploaded proof file)
- status (ENUM: pending, verified, rejected)
- verified_by (FK -> users.id, NULLABLE)
- verification_date (NULLABLE)
- notes (NULLABLE)
- paid_at
- created_by
- updated_by
- created_at
- updated_at
- INDEX(member_id, status)
- INDEX(paid_at)
- INDEX(payable_type, payable_id)

---

## Loans
- id
- member_id (FK -> members.id)
- principal_amount (DECIMAL 12,2)
- interest_rate (DECIMAL 5,2)
- duration_months
- monthly_installment (DECIMAL 12,2)
- status (ENUM: pending, approved, active, completed, defaulted, rejected)
- purpose (NULLABLE)
- approved_by (FK -> users.id, NULLABLE)
- approval_date (NULLABLE)
- rejection_reason (NULLABLE)
- start_date (NULLABLE)
- end_date (NULLABLE)
- remaining_balance (DECIMAL 12,2 — denormalized for performance)
- guarantor_id (FK -> members.id, NULLABLE)
- created_by
- updated_by
- created_at
- updated_at
- INDEX(member_id, status)

> **Business Rule:** Only 1 active loan per member — enforced at application level.

---

## Installments
- id
- loan_id (FK -> loans.id, CASCADE)
- payment_id (FK -> payments.id, NULLABLE) ← Links to actual payment record
- installment_number
- due_date
- amount (DECIMAL 12,2)
- status (ENUM: pending, paid, overdue, partially_paid)
- paid_at (NULLABLE)
- created_by
- updated_by
- created_at
- updated_at
- INDEX(loan_id, due_date)
- INDEX(status, due_date)

---

## Claims
- id
- member_id (FK -> members.id)
- type (ENUM: death, disability, retirement, marriage, other)
- requested_amount (DECIMAL 12,2)
- approved_amount (DECIMAL 12,2, NULLABLE)
- status (ENUM: pending, approved, rejected, ready, delivered)
- request_date
- approved_by (FK -> users.id, NULLABLE)
- approval_date (NULLABLE)
- rejection_reason (NULLABLE)
- delivered_by (FK -> users.id, NULLABLE)
- delivery_date (NULLABLE)
- created_by
- updated_by
- created_at
- updated_at
- INDEX(member_id, status)

---

## Documents (Polymorphic)
- id
- documentable_type (VARCHAR — e.g. "member", "claim", "loan", "legal_case")
- documentable_id (BIGINT UNSIGNED)
- type (e.g. "national_id", "bank_statement", "medical_report", "contract")
- file_path (VARCHAR 500)
- original_name
- file_size (BIGINT, NULLABLE — in bytes)
- uploaded_by (FK -> users.id)
- created_at
- updated_at
- INDEX(documentable_type, documentable_id)

---

## Notifications
- id
- notifiable_type (VARCHAR — e.g. "user")
- notifiable_id (BIGINT UNSIGNED)
- type (VARCHAR — notification class name)
- title
- body
- data (JSON, NULLABLE — extra payload)
- is_read (BOOLEAN, DEFAULT false)
- read_at (NULLABLE)
- created_at
- INDEX(notifiable_type, notifiable_id, is_read)

---

## Audit Logs
- id
- user_id (FK -> users.id, NULLABLE)
- action (e.g. "created", "updated", "deleted", "login", "impersonation")
- entity_type (VARCHAR)
- entity_id (BIGINT UNSIGNED, NULLABLE)
- old_values (JSON, NULLABLE)
- new_values (JSON, NULLABLE)
- ip_address (VARCHAR 45)
- user_agent (VARCHAR 500, NULLABLE)
- created_at
- INDEX(entity_type, entity_id)
- INDEX(user_id, created_at)

---

## Impersonations
- id
- admin_id (FK -> users.id)
- target_user_id (FK -> users.id)
- reason (NULLABLE)
- started_at
- ended_at (NULLABLE)
- created_at

---

## OTP Codes
- id
- user_id (FK -> users.id)
- type (ENUM: password_reset, email_verification, login_verification)
- code (VARCHAR 6)
- expires_at
- is_used (BOOLEAN, DEFAULT false)
- used_at (NULLABLE)
- created_at

---

## Departments
- id
- name
- description (NULLABLE)
- is_active (BOOLEAN, DEFAULT true)
- created_by
- updated_by
- created_at
- updated_at

---

## Divisions
- id
- department_id (FK -> departments.id, CASCADE)
- name
- description (NULLABLE)
- is_active (BOOLEAN, DEFAULT true)
- created_by
- updated_by
- created_at
- updated_at

---

## Member Divisions (Pivot)
- id
- member_id (FK -> members.id, CASCADE)
- division_id (FK -> divisions.id, CASCADE)
- UNIQUE(member_id, division_id)

---

## Revenues
- id
- amount (DECIMAL 12,2)
- source (VARCHAR)
- category (ENUM: subscriptions, donations, interest, penalties, other)
- description (NULLABLE)
- reference_type (VARCHAR, NULLABLE — e.g. "payment", "subscription")
- reference_id (BIGINT UNSIGNED, NULLABLE)
- date
- created_by
- updated_by
- created_at
- updated_at

---

## Expenses
- id
- amount (DECIMAL 12,2)
- type (VARCHAR)
- category (ENUM: claims, operations, salaries, maintenance, other)
- description (NULLABLE)
- related_claim_id (FK -> claims.id, NULLABLE)
- approved_by (FK -> users.id, NULLABLE)
- date
- created_by
- updated_by
- created_at
- updated_at

---

## Bank Accounts
- id
- bank_name
- account_number (UNIQUE)
- account_type (ENUM: current, savings)
- current_balance (DECIMAL 12,2)
- is_active (BOOLEAN, DEFAULT true)
- created_by
- updated_by
- created_at
- updated_at

---

## Rules (Business Rules Engine)
- id
- name
- description (NULLABLE)
- type (ENUM: membership_eligibility, loan_eligibility, payment_constraint, claim_eligibility)
- condition (JSON — dynamic rule definition)
- value
- error_message (NULLABLE — shown when rule fails)
- is_active (BOOLEAN, DEFAULT true)
- created_by
- updated_by
- created_at
- updated_at

---

## Settings
- id
- key (UNIQUE)
- value (TEXT)
- type (ENUM: string, boolean, integer, json)
- group (VARCHAR, NULLABLE — e.g. "general", "financial", "notifications")
- description (NULLABLE)
- updated_by (NULLABLE)
- updated_at

---

## Legal Cases
- id
- member_id (FK -> members.id)
- title
- description (TEXT)
- status (ENUM: open, in_progress, resolved, closed)
- start_date
- end_date (NULLABLE)
- created_by
- updated_by
- created_at
- updated_at

---

## Case Notes
- id
- case_id (FK -> legal_cases.id, CASCADE)
- note (TEXT)
- status_before (VARCHAR, NULLABLE)
- status_after (VARCHAR, NULLABLE)
- created_by
- created_at

---

## Summary of Changes

| # | Fix Applied | Tables Affected |
|---|---|---|
| 1 | Added `user_id` FK to Persons (links person ↔ system user) | Persons |
| 2 | Added `department_id` FK to Users | Users |
| 3 | Made Documents **polymorphic** (`documentable_type/id`) | Documents |
| 4 | Fixed Payments to use **polymorphic** (`payable_type/id`) | Payments |
| 5 | Added `verified_by`, `verification_date`, `bank_statement_path`, `notes` to Payments | Payments |
| 6 | Added **Employments** table (employment info from membership form) | NEW |
| 7 | Added **Family Members** table (family info from membership form) | NEW |
| 8 | Added **Subscriptions** table (recurring payment definitions) | NEW |
| 9 | Added `approved_by`, `rejection_reason`, `purpose`, `guarantor_id` to Loans | Loans |
| 10 | Added `approved_by`, `delivered_by`, `rejection_reason` to Claims | Claims |
| 11 | Added `payment_id`, `installment_number` to Installments | Installments |
| 12 | Added `termination_date`, `termination_reason` to Members | Members |
| 13 | Fixed Revenues — added `created_at`, `category`, `description`, polymorphic reference | Revenues |
| 14 | Fixed Expenses — added `created_at`, `category`, `description`, `approved_by` | Expenses |
| 15 | Fixed Bank Accounts — added `is_active`, `created_at`, `created_by` | Bank Accounts |
| 16 | Improved Notifications — Laravel polymorphic format + `read_at`, `data` JSON | Notifications |
| 17 | Improved OTP Codes — added `type`, `used_at` | OTP Codes |
| 18 | Improved Rules — added `description`, `error_message` | Rules |
| 19 | Improved Settings — added `type`, `group`, `description` | Settings |
| 20 | Added data types, ENUM values, and indexes throughout | All tables |
| 21 | Added `nationality` to Persons | Persons |
| 22 | Added `user_agent` and `reason` to Audit Logs and Impersonations | Audit Logs, Impersonations |
| 23 | Marked all UNIQUE constraints and composite indexes | All tables |
