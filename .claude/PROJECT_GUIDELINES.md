# Quiz & Assessment Management System
## Project Development Guidelines

> Technical guideline untuk implementasi berdasarkan PRD Quiz & Assessment Management System.

---

# 1. Project Context

## Product

**Quiz & Assessment Management System**

## Application Type

Monolithic Web Application.

## Main Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11+ |
| PHP | PHP 8.2+ |
| Frontend | Livewire + Blade |
| CSS | Tailwind CSS |
| Database | MySQL / MariaDB |
| Repository | Git |
| Testing | PHPUnit / Pest |
| Container | Docker |

Deadline PRD:

**04 Oktober 2026, 23:59 WIB**.

---

# 2. Product Scope

Aplikasi memiliki dua area utama.

## Admin

```text
Admin
├── Login
├── Dashboard
├── Quiz Management
├── Question Management
├── Answer Option Management
├── Publish Quiz
├── View Submission
└── View Result
```

## Public

```text
Public
├── Quiz List
├── Quiz Detail
├── Start Quiz
├── Answer Questions
├── Submit Quiz
└── Result
```

Flow tersebut merupakan core MVP dari PRD.

---

# 3. Development Priority

Jangan mengerjakan semua bonus sebelum MVP selesai.

## P0 — Core MVP

```text
01 Project Setup
02 Database
03 Models & Relationships
04 Authentication
05 Quiz CRUD
06 Question CRUD
07 Answer Option CRUD
08 Quiz Publishing
09 Public Quiz List
10 Quiz Detail
11 Quiz Attempt
12 Answer Selection
13 Answer Persistence
14 Quiz Submission
15 Score Calculation
16 Result
17 Validation
18 Security
19 Responsive UI
20 Testing
21 Documentation
22 Deployment
```

## P1 — Architecture & Quality

```text
23 Service Layer
24 Repository Pattern
25 Better Test Coverage
26 Performance Optimization
27 Docker
```

## P2 — Bonus

```text
28 REST API
29 Dashboard & Analytics
30 Psychological Assessment
31 Role & Permission Management
32 PDF Export
33 Excel Export
34 AI-assisted Result Interpretation
35 Advanced Deployment
```

PRD secara eksplisit menempatkan AI, advanced analytics, PDF/Excel, complex RBAC, full REST API, dan advanced psychological scoring di luar MVP.

---

# 4. Architecture Principle

Gunakan architecture sederhana:

```text
┌────────────────────────────┐
│        Livewire / API      │
│       Presentation Layer   │
└──────────────┬─────────────┘
               │
               ▼
┌────────────────────────────┐
│          Service           │
│       Business Logic       │
└──────────────┬─────────────┘
               │
               ▼
┌────────────────────────────┐
│        Repository          │
│        Data Access         │
└──────────────┬─────────────┘
               │
               ▼
┌────────────────────────────┐
│          Eloquent          │
│           Model            │
└──────────────┬─────────────┘
               │
               ▼
┌────────────────────────────┐
│       MySQL / MariaDB      │
└────────────────────────────┘
```

## Rule

**Livewire tidak boleh menjadi tempat business logic.**

Livewire bertanggung jawab terhadap:

- UI state
- user interaction
- validation
- memanggil service
- redirect
- UI feedback

Service bertanggung jawab terhadap:

- business rules
- workflow
- transaction
- scoring
- orchestration

Repository bertanggung jawab terhadap:

- database query
- persistence
- data retrieval

---

# 5. Recommended Folder Structure

Gunakan struktur feature-oriented tetapi tetap sederhana untuk technical test.

```text
app/
├── Enums/
│   ├── QuizStatus.php
│   ├── QuizAttemptStatus.php
│   └── QuestionType.php
│
├── DTOs/
│   ├── Quiz/
│   │   ├── CreateQuizData.php
│   │   └── UpdateQuizData.php
│   │
│   └── Assessment/
│       └── SubmitQuizData.php
│
├── Models/
│   ├── User.php
│   ├── Quiz.php
│   ├── Question.php
│   ├── AnswerOption.php
│   ├── QuizAttempt.php
│   └── QuizAnswer.php
│
├── Repositories/
│   ├── Contracts/
│   │   ├── QuizRepositoryInterface.php
│   │   ├── QuestionRepositoryInterface.php
│   │   └── QuizAttemptRepositoryInterface.php
│   │
│   ├── QuizRepository.php
│   ├── QuestionRepository.php
│   └── QuizAttemptRepository.php
│
├── Services/
│   ├── Quiz/
│   │   ├── QuizService.php
│   │   └── QuizPublishingService.php
│   │
│   ├── Question/
│   │   └── QuestionService.php
│   │
│   └── Assessment/
│       ├── QuizAttemptService.php
│       └── ScoringService.php
│
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── V1/
│   │
│   ├── Requests/
│   │   ├── Quiz/
│   │   ├── Question/
│   │   └── Assessment/
│   │
│   └── Resources/
│       └── Api/
│
├── Livewire/
│   ├── Admin/
│   │   ├── Dashboard/
│   │   ├── Quiz/
│   │   └── Question/
│   │
│   └── Public/
│       ├── Quiz/
│       └── Attempt/
│
├── Policies/
│   ├── QuizPolicy.php
│   └── QuestionPolicy.php
│
└── Support/
    ├── Exceptions/
    └── Helpers/
```

---

# 6. Domain Model

Model mengikuti PRD.

```text
User
 │
 └── QuizAttempt
       │
       ├── Quiz
       │    │
       │    └── Question
       │           │
       │           └── AnswerOption
       │
       └── QuizAnswer
              │
              ├── Question
              └── AnswerOption
```

Entity utama:

```text
User
Quiz
Question
AnswerOption
QuizAttempt
QuizAnswer
```

PRD mendefinisikan struktur dan relasi tersebut sebagai domain model utama.

---

# 7. Database Rules

## users

```text
id
name
email
password
timestamps
```

## quizzes

```text
id
title
slug
description
status
published_at
timestamps
```

Status:

```text
draft
published
archived
```

## questions

```text
id
quiz_id
question
type
order
points
timestamps
```

MVP:

```text
type = single_choice
```

## answer_options

```text
id
question_id
option_text
is_correct
order
timestamps
```

## quiz_attempts

```text
id
quiz_id
user_id nullable
started_at
submitted_at nullable
score nullable
status
timestamps
```

Status:

```text
in_progress
submitted
```

## quiz_answers

```text
id
quiz_attempt_id
question_id
answer_option_id
is_correct
points
timestamps
```

Struktur database ini mengikuti entity yang ditentukan dalam PRD.

---

# 8. Enum

Jangan menggunakan magic string di business logic.

## QuizStatus

```php
enum QuizStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
```

## QuizAttemptStatus

```php
enum QuizAttemptStatus: string
{
    case InProgress = 'in_progress';
    case Submitted = 'submitted';
}
```

## QuestionType

```php
enum QuestionType: string
{
    case SingleChoice = 'single_choice';
}
```

---

# 9. Service Layer

## QuizService

Business logic untuk:

```text
Create Quiz
Update Quiz
Delete Quiz
Get Quiz
List Quiz
```

Contoh:

```php
final class QuizService
{
    public function __construct(
        private readonly QuizRepositoryInterface $repository,
    ) {
    }

    public function create(CreateQuizData $data): Quiz
    {
        return $this->repository->create([
            'title' => $data->title,
            'slug' => $data->slug,
            'description' => $data->description,
            'status' => QuizStatus::Draft,
        ]);
    }
}
```

---

# 10. Quiz Publishing Service

Publishing merupakan business operation sehingga jangan dilakukan langsung di Livewire.

```php
final class QuizPublishingService
{
    public function publish(Quiz $quiz): Quiz
    {
        // Validate business requirements.

        // Change status.

        // Set published_at.

        // Persist.

        return $quiz;
    }
}
```

Business rule:

```text
draft
  ↓
published
  ↓
archived
```

PRD mendefinisikan status dan publishing sebagai bagian dari Quiz Management.

---

# 11. Question Service

`QuestionService` bertanggung jawab terhadap:

```text
Create Question
Update Question
Delete Question
Reorder Question
Manage Answer Options
```

Business rule:

```text
Question
    ↓
minimum 1 Answer Option
```

Untuk `single_choice`:

```text
maximum 1 correct answer
```

Rule tersebut berasal dari acceptance criteria PRD.

---

# 12. Quiz Attempt Service

Semua proses pengerjaan quiz berada di service.

```text
Start Quiz
    ↓
Create QuizAttempt
    ↓
Save Answer
    ↓
Update Answer
    ↓
Submit Quiz
    ↓
Calculate Score
    ↓
Complete Attempt
```

Contoh API internal:

```php
$attemptService->start($quiz);

$attemptService->answer(
    $attempt,
    $question,
    $answerOption
);

$attemptService->submit($attempt);
```

---

# 13. Scoring Service

Scoring tidak boleh berada di Blade atau Livewire.

```text
QuizAttemptService
        │
        ▼
   ScoringService
        │
        ├── Evaluate Answers
        ├── Calculate Points
        ├── Calculate Percentage
        └── Generate Result
```

Formula MVP:

```text
score =
total_points_obtained
/
total_points
× 100
```

Contoh:

```text
Total Questions = 10
Correct = 8

Score = 80 / 100
```

Formula tersebut mengikuti PRD.

---

# 14. Atomic Submission

Submission harus menggunakan transaction.

```php
DB::transaction(function () use ($attempt): void {
    // Evaluate answers.
    // Save quiz answers.
    // Calculate score.
    // Update attempt.
});
```

Flow:

```text
Submit
  ↓
Validate
  ↓
Evaluate
  ↓
Save Answers
  ↓
Calculate Score
  ↓
Update Attempt
  ↓
Commit
```

Jika salah satu proses gagal:

```text
ROLLBACK
```

PRD secara eksplisit mensyaratkan submission diproses secara atomic menggunakan database transaction.

---

# 15. Livewire Architecture

## Admin

```text
Livewire
└── Admin
    ├── Dashboard
    ├── Quiz
    │   ├── Index
    │   ├── Create
    │   ├── Edit
    │   └── Show
    │
    └── Question
        ├── Index
        ├── Create
        └── Edit
```

## Public

```text
Livewire
└── Public
    ├── Quiz
    │   ├── Index
    │   └── Show
    │
    └── Attempt
        ├── Start
        ├── Take
        └── Result
```

---

# 16. Livewire Responsibility

Contoh:

```php
public function save(QuizService $service): void
{
    $this->validate([
        'title' => ['required', 'string', 'max:255'],
    ]);

    $service->create(
        new CreateQuizData(
            title: $this->title,
            description: $this->description,
        )
    );

    $this->redirectRoute('admin.quizzes.index');
}
```

Livewire:

```text
Validate
   ↓
Call Service
   ↓
Redirect
```

Bukan:

```text
Livewire
 ↓
DB query
 ↓
Business logic
 ↓
Scoring
 ↓
Transaction
```

---

# 17. Repository Pattern

Repository digunakan sebagai abstraction untuk data access.

```php
interface QuizRepositoryInterface
{
    public function findById(int $id): Quiz;

    public function findBySlug(string $slug): Quiz;

    public function create(array $data): Quiz;

    public function update(
        Quiz $quiz,
        array $data
    ): Quiz;

    public function delete(Quiz $quiz): void;
}
```

Implementation:

```php
final class QuizRepository implements QuizRepositoryInterface
{
    public function findById(int $id): Quiz
    {
        return Quiz::query()->findOrFail($id);
    }

    public function findBySlug(string $slug): Quiz
    {
        return Quiz::query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function create(array $data): Quiz
    {
        return Quiz::query()->create($data);
    }

    public function update(
        Quiz $quiz,
        array $data
    ): Quiz {
        $quiz->update($data);

        return $quiz->refresh();
    }

    public function delete(Quiz $quiz): void
    {
        $quiz->delete();
    }
}
```

---

# 18. Dependency Injection

Service menggunakan interface.

```php
final class QuizService
{
    public function __construct(
        private readonly QuizRepositoryInterface $repository,
    ) {
    }
}
```

Binding:

```php
$this->app->bind(
    QuizRepositoryInterface::class,
    QuizRepository::class,
);
```

Keuntungan:

```text
Service
   ↓
Interface
   ↓
Repository
```

Mudah diganti dan mudah di-test.

---

# 19. Authentication

MVP:

```text
Admin Login
Admin Logout
Protected Admin CMS
```

Acceptance criteria:

```text
[ ] Login tersedia
[ ] Email/password divalidasi
[ ] Password di-hash
[ ] CMS membutuhkan authentication
[ ] Logout tersedia
```

Ini mengikuti FR-01 PRD.

---

# 20. Authorization

Untuk MVP cukup:

```text
authenticated admin
```

Gunakan:

```text
Middleware
Policy
```

Jangan menyebarkan:

```php
if ($user->role === 'admin')
```

ke seluruh aplikasi.

Advanced RBAC masuk P2.

---

# 21. Public Quiz

Public frontend hanya menampilkan:

```text
status = published
```

Jangan menampilkan:

```text
draft
archived
```

Query:

```php
Quiz::query()
    ->where('status', QuizStatus::Published)
    ->latest()
    ->paginate(12);
```

Acceptance criteria ini mengikuti PRD.

---

# 22. Quiz Attempt

Start:

```text
User
 ↓
Quiz Detail
 ↓
Start
 ↓
QuizAttempt
 ↓
in_progress
```

Submit:

```text
QuizAttempt
 ↓
Validate
 ↓
Evaluate
 ↓
Score
 ↓
submitted
```

---

# 23. Answer Security

Frontend tidak boleh menentukan:

```text
is_correct
points
score
```

Frontend hanya mengirim:

```json
{
    "question_id": 1,
    "answer_option_id": 3
}
```

Server menentukan:

```text
answer_option
     ↓
is_correct
     ↓
points
     ↓
score
```

Ini penting untuk mencegah manipulasi hasil quiz dan sesuai security requirement dalam PRD.

---

# 24. Validation

Semua input harus divalidasi.

Contoh Quiz:

```php
'title' => [
    'required',
    'string',
    'max:255',
],

'description' => [
    'nullable',
    'string',
],
```

Question:

```php
'question' => [
    'required',
    'string',
],

'type' => [
    'required',
    Rule::enum(QuestionType::class),
],

'points' => [
    'required',
    'integer',
    'min:1',
],
```

---

# 25. Security

Minimum security:

```text
[ ] Password hashing
[ ] CSRF protection
[ ] Form validation
[ ] Authorization
[ ] SQL injection protection
[ ] XSS protection
[ ] Mass assignment protection
[ ] Secure authentication
[ ] Server-side score calculation
[ ] Secure error handling
[ ] No secrets in Git
```

Jangan mempercayai input dari browser.

---

# 26. Performance

MVP harus menghindari masalah obvious performance.

## Eager Loading

```php
Quiz::query()
    ->with('questions.answerOptions')
    ->findOrFail($id);
```

Hindari N+1 query.

## Pagination

Gunakan pagination untuk:

```text
Quiz List
Question List
Attempt List
Submission List
```

## Database Index

Minimal:

```text
quizzes.slug
quizzes.status

questions.quiz_id

answer_options.question_id

quiz_attempts.quiz_id
quiz_attempts.user_id
quiz_attempts.status

quiz_answers.quiz_attempt_id
quiz_answers.question_id
quiz_answers.answer_option_id
```

PRD secara khusus memasukkan database optimization dan index sebagai task performance.

---

# 27. Responsive UI

Prioritas:

```text
Mobile
   ↓
Tablet
   ↓
Desktop
```

Admin:

```text
Desktop
Tablet
Mobile
```

Public:

```text
Mobile-first
```

UI wajib mempunyai:

```text
Loading State
Empty State
Validation Error
Success State
Error State
```

---

# 28. Testing Strategy

## Unit Test

Fokus:

```text
ScoringService
QuizService
QuestionService
Business Rules
```

Contoh:

```text
ScoringServiceTest
QuizServiceTest
QuestionServiceTest
```

## Feature Test

Test:

```text
Admin Login
Quiz CRUD
Question CRUD
Answer Option
Publish Quiz
Start Attempt
Answer Question
Submit Quiz
Result
Authorization
```

PRD memang menetapkan testing untuk model, CRUD, calculation, dan submission.

---

# 29. Minimum Critical Tests

Wajib memiliki test untuk:

```text
[ ] Admin can login
[ ] Unauthenticated user cannot access admin
[ ] Admin can create quiz
[ ] Quiz slug is unique
[ ] Admin can publish quiz
[ ] Draft quiz is hidden publicly
[ ] Question requires quiz
[ ] Single choice has max one correct answer
[ ] User can start attempt
[ ] User can save answer
[ ] User can submit quiz
[ ] Score is calculated correctly
[ ] Result is generated
[ ] Submitted attempt cannot be resubmitted
```

---

# 30. Docker

Docker merupakan P1.

Minimal:

```text
docker/
├── nginx/
│   └── default.conf
│
└── php/
    └── Dockerfile

docker-compose.yml
```

Services:

```text
app
mysql
nginx
```

Optional:

```text
redis
queue
```

Target:

```bash
docker compose up -d
```

---

# 31. REST API

REST API adalah P2.

Gunakan versioning:

```text
/api/v1/
```

Contoh:

```text
GET    /api/v1/quizzes
GET    /api/v1/quizzes/{quiz}
POST   /api/v1/quizzes
PUT    /api/v1/quizzes/{quiz}
DELETE /api/v1/quizzes/{quiz}
```

Assessment:

```text
POST /api/v1/attempts
POST /api/v1/attempts/{attempt}/answers
POST /api/v1/attempts/{attempt}/submit
GET  /api/v1/attempts/{attempt}/result
```

API harus menggunakan:

```text
Form Request
Service
Repository
API Resource
```

---

# 32. Psychological Assessment

Psychological Assessment merupakan extension dari generic Quiz Engine.

Jangan membuat engine terpisah untuk setiap assessment.

Gunakan:

```text
Assessment
   ↓
Questions
   ↓
Options
   ↓
Scoring Rules
   ↓
Result
```

Kemungkinan assessment:

```text
Stress
Anxiety
MBTI
DISC
Big Five
```

PRD menyebut assessment tersebut sebagai extension/bonus, bukan blocker MVP.

---

# 33. Scoring Strategy

Jika psychological assessment dikembangkan, gunakan strategy.

```php
interface ScoringStrategy
{
    public function calculate(
        QuizAttempt $attempt
    ): AssessmentResult;
}
```

Implementasi:

```text
GenericQuizScoringStrategy
StressScoringStrategy
AnxietyScoringStrategy
MBTIScoringStrategy
DISCScoringStrategy
BigFiveScoringStrategy
```

Hindari:

```php
if ($quiz->type === 'stress') {
    // ...
}

if ($quiz->type === 'mbti') {
    // ...
}

if ($quiz->type === 'disc') {
    // ...
}
```

---

# 34. AI Result Interpretation

AI merupakan P2.

Flow:

```text
Quiz Result
     ↓
Scoring
     ↓
Result
     ↓
ResultInterpretationService
     ↓
AIService
     ↓
AI Provider
```

AI **tidak boleh menghitung score**.

AI hanya menginterpretasikan hasil yang sudah dihitung server.

Contoh:

```text
Score = 80
        ↓
AI
        ↓
Interpretation
```

API key:

```env
AI_API_KEY=
```

Tidak boleh hard-code.

---

# 35. Dashboard & Analytics

MVP dashboard:

```text
Total Quiz
Total Questions
Total Attempts
```

Advanced:

```text
Average Score
Completion Rate
Quiz Popularity
Score Distribution
Assessment Distribution
Date Range
```

Advanced analytics tetap P2.

PRD menetapkan dashboard sederhana untuk MVP dan advanced analytics di luar MVP.

---

# 36. PDF / Excel Export

P2.

Export:

```text
Quiz
Attempts
Results
```

Untuk dataset besar gunakan queue.

Flow:

```text
Request Export
      ↓
Export Service
      ↓
Queue
      ↓
Generate File
      ↓
Storage
      ↓
Download
```

---

# 37. Documentation

Minimal:

```text
README.md
PROJECT_GUIDELINES.md
ARCHITECTURE.md
DATABASE.md
TESTING.md
DEPLOYMENT.md
```

Jika API tersedia:

```text
API.md
```

Jika AI tersedia:

```text
AI.md
```

README harus menjelaskan:

```text
Project Overview
Requirements
Installation
Environment
Database Setup
Running Application
Testing
Architecture
Docker
Deployment
```

---

# 38. Git Workflow

Branch:

```text
main
develop
feature/*
bugfix/*
```

Contoh:

```text
feature/quiz-crud
feature/question-management
feature/quiz-attempt
feature/scoring-service
bugfix/quiz-submission
```

Commit:

```text
feat: add quiz management
feat: add question management
feat: implement quiz attempt
feat: add scoring service
fix: prevent duplicate submission
test: add quiz submission tests
docs: update architecture
```

---

# 39. Development Sequence

Ikuti urutan berikut.

```text
01
Project Setup
   ↓
02
Database
   ↓
03
Models & Relationships
   ↓
04
Authentication
   ↓
05
Quiz Repository
   ↓
06
Quiz Service
   ↓
07
Quiz CRUD
   ↓
08
Question Repository
   ↓
09
Question Service
   ↓
10
Question CRUD
   ↓
11
Answer Option
   ↓
12
Publish Quiz
   ↓
13
Public Quiz List
   ↓
14
Quiz Detail
   ↓
15
Quiz Attempt Service
   ↓
16
Quiz Taking UI
   ↓
17
Answer Persistence
   ↓
18
Scoring Service
   ↓
19
Submission Transaction
   ↓
20
Result
   ↓
21
Security
   ↓
22
Testing
   ↓
23
Performance
   ↓
24
Responsive UI
   ↓
25
Documentation
   ↓
26
Docker
   ↓
27
Deployment
```

---

# 40. Feature Implementation Pattern

Setiap feature mengikuti pola:

```text
Requirement
     ↓
Database
     ↓
Model
     ↓
Repository
     ↓
Service
     ↓
Livewire
     ↓
Blade
     ↓
Test
     ↓
Documentation
```

Contoh Quiz:

```text
Quiz Requirement
      ↓
quizzes migration
      ↓
Quiz Model
      ↓
QuizRepository
      ↓
QuizService
      ↓
Livewire/Admin/Quiz
      ↓
quiz/index.blade.php
      ↓
QuizFeatureTest
      ↓
Documentation
```

---

# 41. Definition of Done

Task hanya dianggap selesai apabila:

```text
[ ] Requirement terpenuhi
[ ] Database benar
[ ] Relationship benar
[ ] Validation tersedia
[ ] Authorization tersedia jika diperlukan
[ ] Service digunakan untuk business logic
[ ] Repository digunakan untuk data access
[ ] Livewire tidak mengandung business logic kompleks
[ ] Error handling tersedia
[ ] Loading state tersedia
[ ] Empty state tersedia
[ ] Responsive
[ ] Critical test tersedia
[ ] Tidak ada N+1 query
[ ] Tidak ada debug code
[ ] Tidak ada secret
[ ] Dokumentasi diperbarui
```

---

# 42. MVP Acceptance Flow

End-to-end MVP harus menghasilkan flow:

```text
ADMIN
  │
  ▼
LOGIN
  │
  ▼
DASHBOARD
  │
  ▼
CREATE QUIZ
  │
  ├── Title
  ├── Description
  └── Status
  │
  ▼
CREATE QUESTIONS
  │
  ├── Question
  ├── Type
  ├── Points
  └── Order
  │
  ▼
CREATE ANSWER OPTIONS
  │
  ├── A
  ├── B ✓
  ├── C
  └── D
  │
  ▼
PUBLISH
  │
  ▼
══════════════════════
     PUBLIC
══════════════════════
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
SCORING SERVICE
  │
  ▼
RESULT
```

Flow ini mengikuti core business flow PRD.

---

# 43. MVP vs Bonus Boundary

## Jangan masuk MVP

```text
AI
Advanced Psychological Scoring
Advanced Analytics
PDF
Excel
Complex RBAC
Full REST API
Multi-tenancy
Payment
Notification
```

## MVP harus solid

```text
Authentication
Quiz CRUD
Question CRUD
Answer Option
Publishing
Public Quiz
Quiz Attempt
Answer
Submission
Scoring
Result
Security
Testing
Responsive UI
Documentation
```

PRD secara eksplisit menetapkan boundary ini untuk menjaga fokus core feature dan kualitas implementasi.

---

# 44. Engineering Principles

## 1. Thin Livewire

```text
Livewire = UI orchestration
```

## 2. Fat Service

```text
Service = Business Logic
```

Tetap hindari service terlalu besar.

Jika service mulai terlalu kompleks, pecah berdasarkan business capability.

## 3. Repository = Data Access

```text
Repository = Database interaction
```

## 4. Server is the Source of Truth

```text
Client
   ↓
Untrusted Input
   ↓
Validation
   ↓
Business Rules
   ↓
Database
```

## 5. Test Business Rules

Semua business rule penting harus memiliki test.

## 6. Don't Over-engineer

Jangan membuat abstraction jika belum memiliki kebutuhan nyata.

---

# 45. Final Architecture

```text
                       ┌───────────────┐
                       │    Browser    │
                       └───────┬───────┘
                               │
                               ▼
                    ┌────────────────────┐
                    │ Livewire + Blade   │
                    └─────────┬──────────┘
                              │
                              ▼
                    ┌────────────────────┐
                    │      Service       │
                    │  Business Logic    │
                    └─────────┬──────────┘
                              │
                              ▼
                    ┌────────────────────┐
                    │    Repository      │
                    │    Data Access     │
                    └─────────┬──────────┘
                              │
                              ▼
                    ┌────────────────────┐
                    │      Eloquent      │
                    │       Model        │
                    └─────────┬──────────┘
                              │
                              ▼
                    ┌────────────────────┐
                    │   MySQL/MariaDB    │
                    └────────────────────┘
```

Extension architecture:

```text
                    Quiz Engine
                         │
             ┌───────────┼───────────┐
             │           │           │
             ▼           ▼           ▼
        Generic      Psychological   API
          Quiz        Assessment
             │           │
             ▼           ▼
        Scoring      Strategy
        Service      Scoring
             │           │
             └─────┬─────┘
                   ▼
                Result
                   │
          ┌────────┼─────────┐
          ▼        ▼         ▼
       Dashboard   AI       Export
```

---

# 46. Final Success Criteria

Project dianggap berhasil apabila core MVP:

```text
[✓] Admin dapat login
[✓] Admin dapat membuat Quiz
[✓] Admin dapat membuat Question
[✓] Admin dapat membuat Answer Option
[✓] Admin dapat publish Quiz
[✓] Public dapat melihat Quiz
[✓] User dapat memulai Quiz
[✓] User dapat menjawab Quiz
[✓] System menyimpan jawaban
[✓] System mengevaluasi jawaban
[✓] System menghitung score
[✓] System menyimpan result
[✓] User dapat melihat result
[✓] Submission atomic
[✓] Business logic berada di Service
[✓] Data access terisolasi Repository
[✓] Input tervalidasi
[✓] Authorization tersedia
[✓] Critical flow memiliki test
[✓] Responsive UI
[✓] Dokumentasi tersedia
[✓] Application dapat dijalankan/deploy
```

Setelah semua P0 stabil, baru lanjutkan P1/P2.

**Prinsip utama project:**

> **Build the core quiz engine correctly first. Extend it later into a full psychological assessment platform.**