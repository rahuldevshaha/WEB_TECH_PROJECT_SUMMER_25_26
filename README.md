# Mess Manager

A PHP & MySQL based web application for managing shared mess operations such as members, meals, deposits, expenses, bazar assignments, monthly হিসাব, and mess settings.

## Overview

Mess Manager helps a group of people manage their shared mess in one place. Users can create or join a mess, record daily meals, manage deposits and expenses, assign bazar responsibilities, view current-month হিসাব, and maintain monthly history.

The application uses role-based access control, where the **Manager** has additional permissions for sensitive mess operations.

## Main Features

- User registration and login
- Email-based login and social login support
- Forgot/reset password
- User profile management
- Create and manage a mess
- Automatic Manager assignment for the mess creator
- Add existing/new members
- Remove members
- Search members
- Transfer Manager role to another member
- Manager-only mess settings
- Manager-only mess deletion with confirmation
- Morning, Lunch and Dinner meal recording
- Duplicate daily meal prevention
- Deposit/fund management
- Expense management
- Automatic fund entry for supported expense flow
- Bazar assignment with multiple dates
- Current-month হিসাব/dashboard
- Member-wise meal, cost, deposit and balance calculation
- Monthly history and all-month details
- Session-based mess context
- Input validation and protected requests

## Technology Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Server:** Apache (XAMPP)
- **Database Encoding:** utf8mb4
- **AJAX:** XMLHttpRequest (XHR)
- **Architecture:** MVC-style separation of View, Controller, Model/DB Access and Utilities

## Project Structure

```text
app/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── controller/
│   ├── components/
│   └── login/
├── model/
│   └── dbAccess.php
├── utils/
│   ├── securityValidation.php
│   └── utils.php
└── view/
    ├── components/
    └── layout/
```

## Important Modules

| Module | Main Files |
|---|---|
| Authentication | `registration.php`, `login/emailLogin.php`, `login/socialLogin.php`, `forgotPassword.php`, `resetPassword.php`, `logout.php` |
| Dashboard | `home.php`, `components/dashboard.php` |
| Profile | `profile.php` |
| Mess Setup | `components/createMess.php`, `components/messSetting.php`, `components/deleteMess.php` |
| Members | `components/messMember.php`, `components/checkMemberEmail.php`, `components/ChangeMessManager.php` |
| Meals | `components/addMeal.php`, `components/activeMonthDetails.php` |
| Finance | `components/addDeposit.php`, `components/addCost.php`, `components/activeMonthDetails.php` |
| Bazar | `components/messMember.php` and `AssignBazar` data |
| History | `components/history.php`, `components/allMonthDetails.php` |
| Shared Layout | `layout/navbar.php`, `layout/sidebar.php`, `layout/footer.php` |

## Database

Database name:

```text
messManagerDB
```

| Table | Purpose |
|---|---|
| `Users` | Stores user accounts and profile information |
| `Messes` | Stores mess information and settings |
| `Member` | Connects users with messes and stores their roles |
| `Expenses` | Stores mess expenses |
| `MealRecord` | Stores Morning, Lunch and Dinner records |
| `Funds` | Stores deposits/funds |
| `AssignBazar` | Stores bazar assignments and assigned dates |
| `History` | Stores monthly summary information |

Important constraints include a unique user email, a composite key for `Member`, and a unique `(messId, userId, mealDate)` combination for daily meal records.

## User Roles

### Member

A normal member can view mess information, record/view meals according to the application flow, add deposits, view হিসাব, view bazar assignments, and manage their profile.

### Manager

The Manager has all normal member capabilities plus:

- Add/remove members
- Assign bazar
- Change mess settings
- Transfer Manager role
- Delete the mess after confirmation

Manager-only operations verify the user's role before execution.

## Core Workflows

### Create Mess

```text
User Login → Create Mess → Messes record created
                         → Creator added to Member
                         → Creator Role = Manager
```

### Add Member

```text
Manager → Search/Enter Member Email → Check User → Add User to Member
```

### Daily Meal

```text
Select Member + Date → Morning / Lunch / Dinner → MealRecord → Daily Meal Total
```

The database prevents duplicate records for the same mess, member and date.

### Deposit

```text
Member → Amount + Date + Details → Funds → Deposit included in হিসাব
```

### Expense

```text
Amount + Cost Type + Date + Cost By → Expenses → হিসাব calculation
```

The current expense flow can also create an automatic `Funds` entry according to the application's transfer logic.

### Change Manager

```text
Current Manager → Role = Member → Selected Member → Role = Manager
```

The source logic also includes rollback handling if the promotion step fails.

### Monthly হিসাব

```text
Total Meal + Total Meal Cost + Total Deposit
                 ↓
Meal Rate = Total Meal Cost / Total Meal
                 ↓
Member-wise Cost
                 ↓
Member-wise Balance
```

## Security & Validation

The project includes protected request checks, session-based `userId` and `messId`, Manager role verification, input validation helpers, password hashing for reset, deletion confirmation, and foreign-key constraints for referential integrity.

For production deployment, prepared statements should be used consistently, modern password hashing/verification should be applied throughout authentication, and transactions/audit logs/tests should be added for critical operations.

## Installation & Setup

### Requirements

- XAMPP
- Apache
- MySQL
- PHP
- Modern web browser

### Steps

1. Start **Apache** and **MySQL** from XAMPP.
2. Copy the project folder into:

```text
C:\xampp\htdocs\
```

3. Make sure the application is available at:

```text
http://localhost/app/
```

4. Open **phpMyAdmin**.
5. Create/import the database:

```text
messManagerDB
```

6. Import the supplied database schema/SQL file.
7. Check database connection settings in `app/model/dbAccess.php`.
8. Open the application in a browser.
9. Register/login and create a mess to start using the system.

## Database Relationships

```text
Users
  │
  ├── creates ──> Messes
  │
  └── joins ────> Member <──── Messes
                       │
                       ├──> MealRecord
                       ├──> Expenses
                       ├──> Funds
                       ├──> AssignBazar
                       └──> History
```

Operational records also reference `Users` to identify the person responsible for an action.

## Typical Usage

1. Register an account.
2. Login.
3. Create a mess.
4. The creator becomes Manager.
5. Add mess members.
6. Assign bazar dates.
7. Record daily meals.
8. Add deposits.
9. Add expenses.
10. Open the dashboard/current-month details to check meal rate, cost, deposit and balance.
11. Review monthly history when required.

## Testing Checklist

| Test | Expected Result |
|---|---|
| Registration | Unique email creates a user |
| Duplicate Registration | Existing email is rejected |
| Login | Valid credentials open protected application |
| Create Mess | New mess is created and creator becomes Manager |
| Add Member | User is inserted into `Member` |
| Remove Member | Selected member is removed |
| Meal Upsert | Same member/date updates instead of duplicating |
| Deposit | Fund record is inserted correctly |
| Expense | Expense record is inserted correctly |
| Dashboard | Current-month totals are correct |
| Manager Transfer | Old Manager becomes Member; selected member becomes Manager |
| Settings | Only Manager can change mess settings |
| Delete Mess | Manager confirmation is required |
| Bazar | Assignments are saved, updated and removed correctly |

## Project Notes

- Database: `messManagerDB`
- Database character set: `utf8mb4`
- Database collation: `utf8mb4_unicode_ci`
- Daily meal uniqueness: `(messId, userId, mealDate)`
- Member uniqueness: `(messId, userId)`
- Bazar dates are stored as JSON in `AssignBazar.bazarDates`

## Future Improvements

- Automated monthly closing/history generation
- Better reports and charts
- Detailed financial reports
- Audit trail for sensitive actions
- Automated unit/integration testing
- Prepared-statement based database layer
- More granular permissions
- Mobile-responsive improvements
- Notification/reminder system

## License

This project is intended for academic/project use. Add an appropriate open-source license if the project is later distributed publicly.

## Author

**Mess Manager Project**

Built as a PHP/MySQL software project for managing shared mess operations.
