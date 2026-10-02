# PRD --- Quiz & Assessment Management System

**Project:** Quiz & Assessment Management System\
**Type:** Monolithic Web Application\
**Framework:** Laravel 11+\
**PHP:** 8.2+\
**Database:** MySQL / MariaDB\
**Frontend:** Livewire + Blade\
**Repository:** Git\
**Deadline:** 04 Oktober 2026, 23:59 WIB

------------------------------------------------------------------------

## 1. Product Overview

Aplikasi digunakan untuk:

1.  Administrator membuat dan mengelola quiz/assessment.
2.  Administrator membuat pertanyaan dan pilihan jawaban.
3.  User mengakses quiz melalui public frontend.
4.  User mengerjakan quiz.
5.  Sistem memproses submission.
6.  User melihat hasil assessment.

Aplikasi harus dibangun sebagai monolithic application dengan clean
architecture dan maintainable code.

------------------------------------------------------------------------

## 2. Goals

### Primary Goals

-   Admin dapat membuat quiz.
-   Admin dapat membuat question.
-   Admin dapat menentukan pilihan jawaban.
-   User dapat mengerjakan quiz.
-   Sistem dapat menyimpan jawaban.
-   Sistem dapat menghitung hasil.
-   User dapat melihat hasil.
-   UI responsive.
-   Code memiliki struktur yang mudah dikembangkan.

### Secondary Goals / Bonus

-   User Authentication
-   Psychological Assessment
-   REST API
-   Service Layer / Repository Pattern
-   Responsive & Modern UI
-   Performance Optimization
-   Security Best Practices
-   Unit / Feature Testing
-   Docker Support
-   AI-assisted Result Interpretation
-   Dashboard & Analytics
-   Export PDF / Excel
-   Role & Permission Management
-   Deployment to Public Hosting
-   Well-written Documentation

------------------------------------------------------------------------

## 3. Scope

### MVP

``` text
Admin
 ├── Login
 ├── Dashboard
 ├── Quiz Management
 └── Question Management

Public
 ├── Quiz List
 ├── Quiz Detail
 ├── Start Quiz
 ├── Answer Questions
 ├── Submit Quiz
 └── Result
```

### Out of Scope MVP

-   AI interpretation
-   Advanced analytics
-   PDF/Excel export
-   Complex RBAC
-   REST API penuh
-   Multi-tenant
-   Payment
-   Notification
-   Advanced psychological scoring

Fokus utama adalah core feature, clean architecture, maintainability,
dan kualitas implementasi.

------------------------------------------------------------------------

## 4. User Roles

### Admin

Admin dapat:

-   Login
-   Melihat dashboard
-   CRUD quiz
-   CRUD question
-   Mengatur pilihan jawaban
-   Mengatur status quiz
-   Melihat submission
-   Melihat hasil user

### User

User public dapat:

-   Melihat quiz yang tersedia
-   Membuka detail quiz
-   Mengerjakan quiz
-   Submit jawaban
-   Melihat hasil

------------------------------------------------------------------------

## 5. Core Business Flow

``` text
ADMIN
  │
  ├── Create Quiz
  │
  ├── Add Questions
  │       └── Add Answer Options
  │
  └── Publish Quiz
          │
          ▼
       PUBLIC
          │
          ├── View Quiz
          │
          ├── Start
          │
          ├── Answer Questions
          │
          ├── Submit
          │
          ▼
       Evaluation
          │
          ▼
        Result
```

------------------------------------------------------------------------

# 6. Domain Model

## Entity Relationship

``` text
User
 │
 └── QuizAttempt
        │
        ├── Quiz
        │
        └── QuizAnswer
               │
               ├── Question
               │
               └── AnswerOption


Quiz
 │
 └── Question
        │
        └── AnswerOption
```

## User

``` text
users
- id
- name
- email
- password
- timestamps
```

## Quiz

``` text
quizzes
- id
- title
- slug
- description
- status
- published_at
- timestamps
```

Status:

``` text
draft
published
archived
```

## Question

``` text
questions
- id
- quiz_id
- question
- type
- order
- points
- timestamps
```

MVP:

``` text
type = single_choice
```

## AnswerOption

``` text
answer_options
- id
- question_id
- option_text
- is_correct
- order
- timestamps
```

## QuizAttempt

``` text
quiz_attempts
- id
- quiz_id
- user_id nullable
- started_at
- submitted_at nullable
- score nullable
- status
- timestamps
```

Status:

``` text
in_progress
submitted
```

## QuizAnswer

``` text
quiz_answers
- id
- quiz_attempt_id
- question_id
- answer_option_id
- is_correct
- points
- timestamps
```

------------------------------------------------------------------------

# 7. Functional Requirements

## FR-01 Authentication

Admin dapat login ke CMS.

### Acceptance Criteria

-   [ ] Admin dapat login.
-   [ ] Email/password divalidasi.
-   [ ] Password tidak disimpan plain text.
-   [ ] User yang belum authenticated tidak dapat mengakses CMS.
-   [ ] Logout tersedia.

------------------------------------------------------------------------

## FR-02 Quiz Management

Admin dapat membuat, membaca, mengubah dan menghapus quiz.

### Fields

``` text
Title
Description
Status
```

System otomatis membuat:

``` text
slug
created_at
updated_at
```

### Acceptance Criteria

-   [ ] Admin dapat membuat quiz.
-   [ ] Title wajib diisi.
-   [ ] Slug unique.
-   [ ] Quiz dapat diedit.
-   [ ] Quiz dapat dihapus.
-   [ ] Quiz dapat dipublish.
-   [ ] Quiz published dapat ditampilkan di public frontend.

------------------------------------------------------------------------

## FR-03 Question Management

Admin dapat menambahkan question ke quiz.

### Fields

``` text
Question
Type
Points
Order
```

### Acceptance Criteria

-   [ ] Question harus mempunyai quiz.
-   [ ] Question mempunyai urutan.
-   [ ] Question dapat diedit.
-   [ ] Question dapat dihapus.
-   [ ] Question memiliki minimal satu answer option.
-   [ ] Single choice memiliki maksimal satu correct answer.

------------------------------------------------------------------------

## FR-04 Answer Option Management

Admin dapat membuat pilihan jawaban.

Contoh:

``` text
Question:
Apa warna langit?

A. Merah
B. Biru ✓
C. Hijau
D. Kuning
```

### Acceptance Criteria

-   [ ] Option mempunyai text.
-   [ ] Option memiliki order.
-   [ ] Option dapat ditandai sebagai correct.
-   [ ] Sistem mencegah lebih dari satu correct answer untuk
    `single_choice`.

------------------------------------------------------------------------

## FR-05 Public Quiz List

User dapat melihat quiz yang sudah published.

### Acceptance Criteria

-   [ ] Draft tidak muncul.
-   [ ] Archived tidak muncul.
-   [ ] Published quiz muncul.
-   [ ] Responsive mobile/desktop.

------------------------------------------------------------------------

## FR-06 Quiz Detail

User dapat melihat informasi quiz sebelum memulai.

### Informasi

``` text
Title
Description
Number of Questions
Estimated Duration (optional)
Start Button
```

------------------------------------------------------------------------

## FR-07 Quiz Attempt

User dapat memulai quiz.

### Flow

``` text
Start
  ↓
Create QuizAttempt
  ↓
Display Question
  ↓
Select Answer
  ↓
Next
  ↓
Submit
```

------------------------------------------------------------------------

## FR-08 Answer Question

User dapat memilih jawaban.

### Acceptance Criteria

-   [ ] Setiap question menampilkan options.
-   [ ] User dapat memilih satu option.
-   [ ] User dapat berpindah antar question.
-   [ ] Jawaban tidak hilang selama attempt berlangsung.
-   [ ] User tidak dapat submit tanpa menyelesaikan required questions.

------------------------------------------------------------------------

## FR-09 Quiz Submission

User dapat submit quiz.

### Flow

``` text
Validate Answers
      ↓
Evaluate Answers
      ↓
Calculate Score
      ↓
Save QuizAnswer
      ↓
Update QuizAttempt
      ↓
Generate Result
```

Submission harus diproses secara atomic menggunakan database
transaction.

------------------------------------------------------------------------

## FR-10 Score Calculation

MVP menggunakan formula:

``` text
score = total_points_obtained / total_points * 100
```

Contoh:

``` text
Correct Questions = 8
Total Questions = 10

Score = 80
```

Result:

``` text
Score: 80 / 100
Correct: 8
Wrong: 2
```

------------------------------------------------------------------------

## FR-11 Result Display

Setelah submit:

``` text
Assessment Complete

Your Score

80 / 100

Correct Answers
8

Incorrect Answers
2
```

Optional:

``` text
Question Review

1. Question A
   Your answer: B
   Correct answer: B

2. Question B
   Your answer: C
   Correct answer: A
```

------------------------------------------------------------------------

# 8. Admin Dashboard

MVP dashboard sederhana:

``` text
Dashboard

┌────────────┐
│ Total Quiz │
│     10     │
└────────────┘

┌────────────┐
│ Questions  │
│     80     │
└────────────┘

┌────────────┐
│ Attempts   │
│    120     │
└────────────┘
```

Advanced analytics berada di luar MVP.

------------------------------------------------------------------------

# 9. Database Relationship

``` text
users
  │
  │ 1:N
  ▼
quiz_attempts
  │
  │ N:1
  ▼
quizzes
  │
  │ 1:N
  ▼
questions
  │
  │ 1:N
  ▼
answer_options


quiz_attempts
  │
  │ 1:N
  ▼
quiz_answers
  │
  ├── question
  └── answer_option
```

------------------------------------------------------------------------

# 10. Architecture

Recommended structure:

``` text
app/
├── Domain/
│   ├── Quiz/
│   ├── Question/
│   └── Assessment/
│
├── Actions/
│   ├── Quiz/
│   └── Assessment/
│
├── Services/
│   └── AssessmentService.php
│
├── Livewire/
│   ├── Admin/
│   └── Public/
│
├── Models/
│
├── Policies/
│
└── Support/
```

Untuk technical test, architecture dapat dibuat feature-oriented tanpa
over-engineering.

------------------------------------------------------------------------

# 11. Task Breakdown

## EPIC 01 --- Project Setup

### TASK-001 --- Initialize Laravel

-   [ ] Laravel 11+
-   [ ] PHP 8.2+
-   [ ] MySQL/MariaDB
-   [ ] Git repository
-   [ ] Livewire

**Acceptance Criteria**

``` text
php artisan serve
```

berhasil menjalankan aplikasi.

### TASK-002 --- Configure Environment

-   [ ] Setup `.env`
-   [ ] Setup database
-   [ ] Setup `APP_KEY`
-   [ ] Setup `APP_URL`
-   [ ] Buat `.env.example`

### TASK-003 --- Setup Git

Branch strategy:

``` text
main
develop
feature/*
```

Tambahkan:

``` text
.gitignore
README.md
```

------------------------------------------------------------------------

# EPIC 02 --- Database & Domain

### TASK-004 --- Create User Migration

-   [ ] users table
-   [ ] authentication fields

### TASK-005 --- Create Quiz Migration

Fields:

``` text
id
title
slug
description
status
published_at
timestamps
```

### TASK-006 --- Create Question Migration

Fields:

``` text
id
quiz_id
question
type
points
order
timestamps
```

### TASK-007 --- Create Answer Option Migration

Fields:

``` text
id
question_id
option_text
is_correct
order
timestamps
```

### TASK-008 --- Create Quiz Attempt Migration

### TASK-009 --- Create Quiz Answer Migration

### TASK-010 --- Setup Model Relationships

``` text
Quiz
    -> questions()

Question
    -> quiz()
    -> answerOptions()

QuizAttempt
    -> quiz()
    -> user()
    -> answers()

QuizAnswer
    -> attempt()
    -> question()
    -> answerOption()
```

------------------------------------------------------------------------

# EPIC 03 --- Authentication

### TASK-011 --- Admin Authentication

Implement:

``` text
Login
Logout
Protected CMS
```

### TASK-012 --- Admin Authorization

Minimal:

``` text
authenticated admin
```

Optional:

``` text
admin
user
```

------------------------------------------------------------------------

# EPIC 04 --- Quiz Management

### TASK-013 --- Quiz List

Route:

``` text
/admin/quizzes
```

Display:

``` text
Title
Status
Question Count
Created At
Actions
```

### TASK-014 --- Create Quiz

Form:

``` text
Title
Description
Status
```

### TASK-015 --- Edit Quiz

Admin dapat mengubah quiz.

### TASK-016 --- Delete Quiz

Implement:

``` text
Delete
Confirmation
Validation
```

Perhatikan quiz yang sudah memiliki question/attempt.

### TASK-017 --- Publish Quiz

Status transition:

``` text
draft → published
published → archived
```

------------------------------------------------------------------------

# EPIC 05 --- Question Management

### TASK-018 --- Question List

Per quiz:

``` text
Question
Type
Points
Order
Options
Actions
```

### TASK-019 --- Create Question

Form:

``` text
Question
Type
Points
Order
```

### TASK-020 --- Create Answer Options

Dynamic form:

``` text
Option A
Option B
Option C
Option D

Correct Answer
```

### TASK-021 --- Edit Question

### TASK-022 --- Delete Question

### TASK-023 --- Reorder Questions

Optional:

``` text
1. Question A
2. Question B
3. Question C
```

------------------------------------------------------------------------

# EPIC 06 --- Public Frontend

### TASK-024 --- Public Layout

Buat:

``` text
Header
Content
Footer
Responsive layout
```

### TASK-025 --- Quiz Listing

Route:

``` text
/quizzes
```

### TASK-026 --- Quiz Detail

Route:

``` text
/quizzes/{slug}
```

### TASK-027 --- Start Quiz

Ketika user klik:

``` text
Start Assessment
```

Sistem membuat:

``` text
QuizAttempt
```

------------------------------------------------------------------------

# EPIC 07 --- Quiz Engine

### TASK-028 --- Question Navigation

Implement:

``` text
Previous
Next
Question number
```

### TASK-029 --- Answer Selection

User dapat memilih answer.

### TASK-030 --- Persist Answer

Contoh:

``` text
Attempt #001

Question 1 → Option B
Question 2 → Option A
Question 3 → Option D
```

### TASK-031 --- Submit Confirmation

``` text
You have answered 8 of 10 questions.

[Cancel] [Submit]
```

### TASK-032 --- Submit Assessment

Gunakan transaction:

``` php
DB::transaction(function () {
    // save answers
    // calculate score
    // update attempt
});
```

------------------------------------------------------------------------

# EPIC 08 --- Assessment Engine

### TASK-033 --- Implement Answer Evaluation

``` text
selected_option
       ↓
correct_option
       ↓
is_correct
```

### TASK-034 --- Calculate Score

``` text
total points
correct points
percentage
```

### TASK-035 --- Save Result

Result disimpan pada attempt.

### TASK-036 --- Result Page

Route:

``` text
/attempts/{attempt}/result
```

Display:

``` text
Score
Correct
Incorrect
Total
```

------------------------------------------------------------------------

# EPIC 09 --- Security

### TASK-037 --- Request Validation

Gunakan FormRequest untuk validation.

Validasi minimal:

``` text
title required
question required
options required
```

### TASK-038 --- Authorization

Gunakan:

``` text
Policy
Middleware
```

### TASK-039 --- CSRF Protection

Pastikan semua form protected.

### TASK-040 --- Input Sanitization

Jangan mempercayai input client.

### TASK-041 --- Prevent Quiz Manipulation

Server harus menentukan:

``` text
correct answer
points
score
```

Bukan frontend.

Flow:

``` text
Client
  ↓
Selected Answer
  ↓
Laravel
  ↓
Database
  ↓
Correct Answer
  ↓
Calculate Score
```

------------------------------------------------------------------------

# EPIC 10 --- Testing

### TASK-042 --- Quiz Model Tests

Test:

``` text
Quiz relationship
Question relationship
```

### TASK-043 --- Quiz CRUD Feature Test

Test:

``` text
create
update
delete
publish
```

### TASK-044 --- Assessment Calculation Test

Contoh:

``` text
10 questions
8 correct

Expected:
80
```

### TASK-045 --- Submission Feature Test

Test:

``` text
start
answer
submit
result
```

------------------------------------------------------------------------

# EPIC 11 --- Responsive UI

### TASK-046 --- Admin Responsive UI

Target:

``` text
Desktop
Tablet
Mobile
```

### TASK-047 --- Public Responsive UI

Prioritas:

``` text
Mobile
Tablet
Desktop
```

### TASK-048 --- Loading & Error States

Implement:

``` text
loading
validation error
empty state
success state
error state
```

------------------------------------------------------------------------

# EPIC 12 --- Performance

### TASK-049 --- Database Optimization

Gunakan eager loading untuk relationship.

Contoh:

``` php
Quiz::with('questions.answerOptions')
```

### TASK-050 --- Add Database Index

Index:

``` text
quiz_id
question_id
quiz_attempt_id
user_id
slug
status
```

------------------------------------------------------------------------

# EPIC 13 --- Docker

### TASK-051 --- Dockerfile

### TASK-052 --- Docker Compose

Minimal services:

``` text
app
mysql
```

Optional:

``` text
nginx
```

------------------------------------------------------------------------

# EPIC 14 --- Documentation

### TASK-053 --- README

Isi:

``` text
Project Overview
Requirements
Installation
Environment
Database
Running Application
Testing
Architecture
```

### TASK-054 --- Database Documentation

-   [ ] ERD
-   [ ] Relationship explanation
-   [ ] Database setup

### TASK-055 --- API Documentation

Jika REST API dibuat.

### TASK-056 --- Deployment Documentation

``` text
Build
Deploy
Environment
Migration
```

------------------------------------------------------------------------

# EPIC 15 --- Deployment

### TASK-057 --- Prepare Production Environment

### TASK-058 --- Deploy Application

### TASK-059 --- Configure Database

### TASK-060 --- Run Migration & Seeder

### TASK-061 --- Verify Live URL

Checklist:

``` text
Login
Quiz
Question
Submission
Result
Mobile
```

------------------------------------------------------------------------

# 12. Bonus Backlog

Kerjakan setelah MVP stabil.

``` text
B-01 Authentication
B-02 Service Layer
B-03 Unit/Feature Testing
B-04 Security
B-05 Responsive Modern UI
B-06 Docker
B-07 REST API
B-08 Dashboard
B-09 Psychological Assessment
B-10 Export PDF/Excel
B-11 AI Result Interpretation
B-12 Deployment
```

------------------------------------------------------------------------

# 13. Psychological Assessment Extension

Jika ingin menyesuaikan aplikasi dengan domain perusahaan, assessment
dapat dikembangkan menjadi:

``` text
Assessment
   │
   ├── Stress
   ├── Anxiety
   ├── MBTI
   ├── DISC
   └── Big Five
```

Namun fitur ini tidak menjadi blocker MVP.

Gunakan generic assessment engine:

``` text
Quiz
Question
Option
ScoringRule
Result
```

Sehingga:

``` text
General Quiz
        │
        ▼
   Score Engine

Psychological Assessment
        │
        ▼
   Score Engine
```

Hindari business logic seperti:

``` php
if ($quiz->type === 'stress') {
    // ...
}
```

Gunakan scoring strategy/rule agar engine dapat dikembangkan.

------------------------------------------------------------------------

# 14. Recommended MVP Backlog

Jika waktu sangat terbatas:

``` text
P0 — MUST HAVE

01 Project Setup
02 Database
03 Authentication
04 Quiz CRUD
05 Question CRUD
06 Answer Option CRUD
07 Public Quiz List
08 Quiz Detail
09 Quiz Attempt
10 Answer Selection
11 Submission
12 Score Calculation
13 Result
14 Validation
15 Security
16 Responsive UI
17 Testing
18 Documentation
19 Deployment
```

Jangan mengerjakan bonus sebelum P0 selesai.

------------------------------------------------------------------------

# 15. Development Sequence

``` text
Phase 1
Project Setup
     ↓
Phase 2
Database + Model
     ↓
Phase 3
Authentication
     ↓
Phase 4
Quiz CRUD
     ↓
Phase 5
Question CRUD
     ↓
Phase 6
Public Quiz
     ↓
Phase 7
Quiz Engine
     ↓
Phase 8
Scoring Engine
     ↓
Phase 9
Result
     ↓
Phase 10
Testing
     ↓
Phase 11
Security + Performance
     ↓
Phase 12
Responsive UI
     ↓
Phase 13
Documentation
     ↓
Phase 14
Deployment
```

------------------------------------------------------------------------

# 16. Definition of Done

Sebuah task dianggap selesai apabila:

-   [ ] Feature berjalan.
-   [ ] Validation tersedia.
-   [ ] Authorization tersedia jika diperlukan.
-   [ ] Error handling tersedia.
-   [ ] Responsive.
-   [ ] Business logic penting tidak berada di Blade/Livewire component.
-   [ ] Database relationship benar.
-   [ ] Tidak ada N+1 query yang jelas.
-   [ ] Test dibuat untuk business-critical logic.
-   [ ] Code sudah di-review.
-   [ ] Tidak ada debug code.
-   [ ] README diperbarui jika diperlukan.

------------------------------------------------------------------------

# 17. End-to-End MVP Flow

``` text
ADMIN LOGIN
    │
    ▼
DASHBOARD
    │
    ▼
CREATE QUIZ
    │
    ├── Title
    ├── Description
    └── Publish
          │
          ▼
    CREATE QUESTIONS
          │
          ├── Question 1
          │     ├── A
          │     ├── B ✓
          │     ├── C
          │     └── D
          │
          ├── Question 2
          │
          └── Question N
                 │
                 ▼
              PUBLISH
                 │
                 ▼
════════════════════════════════
        PUBLIC FRONTEND
════════════════════════════════
                 │
                 ▼
             QUIZ LIST
                 │
                 ▼
            QUIZ DETAIL
                 │
                 ▼
              START
                 │
                 ▼
           QUIZ ATTEMPT
                 │
                 ▼
           ANSWER QUESTIONS
                 │
                 ▼
               SUBMIT
                 │
                 ▼
          SERVER EVALUATION
                 │
                 ▼
          SCORE CALCULATION
                 │
                 ▼
              RESULT
```

------------------------------------------------------------------------

# 18. Final Priority

Urutan implementasi yang direkomendasikan:

``` text
Database
   ↓
Model & Relationship
   ↓
Authentication
   ↓
Quiz CRUD
   ↓
Question CRUD
   ↓
Answer Option CRUD
   ↓
Public Quiz
   ↓
Quiz Attempt
   ↓
Submission
   ↓
Scoring
   ↓
Result
   ↓
Testing
   ↓
Security
   ↓
Performance
   ↓
Responsive UI
   ↓
Documentation
   ↓
Deployment
```

## Technical Test Success Criteria

Prioritas kualitas:

1.  **Code Quality**
2.  **Laravel Best Practices**
3.  **Software Architecture**
4.  **Database Design**
5.  **Frontend Implementation**
6.  **Problem Solving**
7.  **User Experience**
8.  **Documentation**

Core MVP harus solid sebelum menambahkan bonus feature.
