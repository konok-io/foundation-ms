# Foundation Management System - Database Schema

## Database: foundation_management_system

### Tables Overview

| Table Name | Description |
|------------|-------------|
| users | System users for admin/management |
| roles | User roles with permissions |
| permissions | Individual permissions |
| role_has_permissions | Many-to-many for roles and permissions |
| model_has_roles | Many-to-many for users and roles |
| model_has_permissions | Direct permissions for users |
| general_settings | System configuration settings |
| activity_logs | System activity logging |

---

## Table: users

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(255) | NOT NULL | Full name |
| username | VARCHAR(50) | UNIQUE, NULLABLE | Username for login |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| phone | VARCHAR(20) | NULLABLE | Phone number |
| avatar | VARCHAR(255) | NULLABLE | Avatar image path |
| status | ENUM | DEFAULT 'active' | active/inactive/suspended |
| last_login | TIMESTAMP | NULLABLE | Last login timestamp |
| last_login_ip | VARCHAR(45) | NULLABLE | Last login IP |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | | Creation timestamp |
| updated_at | TIMESTAMP | | Update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX (email)
- UNIQUE INDEX (username)
- INDEX (status, email)

---

## Table: roles

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Role name |
| guard_name | VARCHAR(255) | DEFAULT 'web' | Guard name |
| description | TEXT | NULLABLE | Role description |
| created_at | TIMESTAMP | | Creation timestamp |
| updated_at | TIMESTAMP | | Update timestamp |

---

## Table: permissions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Permission name |
| guard_name | VARCHAR(255) | DEFAULT 'web' | Guard name |
| description | TEXT | NULLABLE | Permission description |
| group_name | VARCHAR(255) | NULLABLE | Permission group |
| created_at | TIMESTAMP | | Creation timestamp |
| updated_at | TIMESTAMP | | Update timestamp |

---

## Table: role_has_permissions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| permission_id | BIGINT UNSIGNED | PK, FK | Permission reference |
| role_id | BIGINT UNSIGNED | PK, FK | Role reference |

**Foreign Keys:**
- permission_id → permissions(id) ON DELETE CASCADE
- role_id → roles(id) ON DELETE CASCADE

---

## Table: model_has_roles

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| role_id | BIGINT UNSIGNED | PK, FK | Role reference |
| model_type | VARCHAR(255) | PK | Model class name |
| model_id | BIGINT UNSIGNED | PK | Model instance ID |

**Foreign Keys:**
- role_id → roles(id) ON DELETE CASCADE

---

## Table: model_has_permissions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| permission_id | BIGINT UNSIGNED | PK, FK | Permission reference |
| model_type | VARCHAR(255) | PK | Model class name |
| model_id | BIGINT UNSIGNED | PK | Model instance ID |

**Foreign Keys:**
- permission_id → permissions(id) ON DELETE CASCADE

---

## Table: general_settings

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| key | VARCHAR(255) | NOT NULL, UNIQUE | Setting key |
| value | LONGTEXT | NULLABLE | Setting value |
| type | ENUM | DEFAULT 'text' | text/textarea/email/url/number/file/color/select |
| group | VARCHAR(255) | DEFAULT 'general' | Settings group |
| created_at | TIMESTAMP | | Creation timestamp |
| updated_at | TIMESTAMP | | Update timestamp |

---

## Table: activity_logs

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| log_name | VARCHAR(255) | NULLABLE | Log category name |
| description | TEXT | NOT NULL | Activity description |
| subject_type | VARCHAR(255) | NULLABLE | Subject model class |
| subject_id | BIGINT UNSIGNED | NULLABLE | Subject instance ID |
| causer_type | VARCHAR(255) | NULLABLE | Causer model class |
| causer_id | BIGINT UNSIGNED | NULLABLE | Causer instance ID |
| properties | LONGTEXT | NULLABLE | Additional properties (JSON) |
| ip_address | VARCHAR(45) | NULLABLE | Client IP address |
| user_agent | TEXT | NULLABLE | User agent string |
| created_at | TIMESTAMP | NOT NULL | Activity timestamp |

**Indexes:**
- INDEX (log_name, subject_type, subject_id)
- INDEX (causer_type, causer_id)

---

## Default Roles

| Role Name | Description |
|-----------|-------------|
| Super Admin | Full system access with all permissions |
| Admin | Administrative access with most permissions |
| Accountant | Financial management and reporting access |
| Executive Member | Executive level access for decision making |
| General Member | Basic member access |
| Volunteer | Volunteer access for event management |
| Donor | Donor access for donation history |

---

## Default Permission Groups

- User Management
- Role Management
- Member Management
- Contributions
- Emergency
- Payments
- Donations
- Accounting
- Reports
- Settings
- Blood Donors
- Events
- Notices
- Documents
- Gallery
- Activities
- Dashboard

---

## Entity Relationship Diagram

```
users ─────┬───── model_has_roles ─────┬───── roles ─────┬───── role_has_permissions ─────┬───── permissions
           │                          │                 │                                │
           └───── model_has_permissions              │                                │
                                                       │                                │
                                              general_settings                          │
                                                       │                                │
                                                    activity_logs ──────────────────────┘
```

---

## Future Tables (Phase 2+)

Additional tables will be created in subsequent phases:

- members (Member profiles)
- monthly_contributions (Monthly dues)
- emergency_collections (Emergency funds)
- payments (Payment records)
- receipts (Payment receipts)
- donations (Public donations)
- income_categories (Income types)
- expense_categories (Expense types)
- vouchers (Accounting vouchers)
- ledgers (Account ledger entries)
- blood_donors (Blood donor registry)
- events (Event management)
- event_registrations (Event attendees)
- notices (Notice management)
- documents (File storage)
- galleries (Photo/video galleries)
- activities (Foundation activities)
- cms_pages (CMS content)
