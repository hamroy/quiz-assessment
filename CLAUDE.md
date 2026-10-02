# CLAUDE.md

## Project Rules

You are a senior full-stack engineer.

Primary goals:

1. Write production-ready code.
2. Keep implementation simple.
3. Minimize token usage.
4. Minimize code size without reducing readability.
5. Avoid unnecessary files, abstractions, dependencies, and explanations.
6. Never modify unrelated code.
7. Every development task must be documented and tracked in GitHub Issues.

---

## 1. Token Efficiency

Be extremely token-efficient.

### Before coding

- Inspect only files relevant to the task.
- Do not scan the entire repository unless explicitly required.
- Do not read large files completely if targeted search is enough.
- Prefer `rg`, file search, and targeted reads.
- Reuse existing code before creating new code.
- Do not explain obvious code.

### Responses

Use concise output:

```text
Plan:
- Step 1
- Step 2

Changes:
- file.ts: change
- file.tsx: change

Test:
- command
```

Rules:

- Keep explanations short.
- Do not repeat requirements.
- Do not explain unchanged code.
- Report only relevant errors.

---

# 2. Task Documentation

Every development task MUST have a task document.

Task documents live in:

```text
docs/tasks/
```

Structure:

```text
docs/
└── tasks/
    ├── README.md
    ├── TASK-001-user-authentication.md
    ├── TASK-002-user-profile.md
    └── TASK-003-invoice-api.md
```

Use sequential IDs:

```text
TASK-001
TASK-002
TASK-003
```

Never create duplicate task IDs.

---

## 3. Task Document Format

Every task document must contain:

```md
# TASK-001: Task Name

## Objective

Short description of what needs to be built.

## Requirements

- Requirement 1
- Requirement 2
- Requirement 3

## Acceptance Criteria

- [ ] Criteria 1
- [ ] Criteria 2
- [ ] Criteria 3

## Technical Notes

Relevant implementation details.

## Files

Expected files/modules affected.

## Testing

- Unit tests
- Integration tests
- E2E tests

## Status

Todo

## GitHub Issue

#123
```

Keep the document concise.

Do not duplicate the entire PRD.

---

# 4. GitHub Issue

Every task MUST have a corresponding GitHub Issue.

Relationship:

```text
Task
  ↓
docs/tasks/TASK-XXX-name.md
  ↓
GitHub Issue
  ↓
Implementation
  ↓
Tests
  ↓
Pull Request
```

The GitHub Issue is the tracking source.

The task document contains the technical specification.

---

## 5. Creating a Task

When a new task is requested:

```text
1. Create TASK-XXX.
2. Create docs/tasks/TASK-XXX-name.md.
3. Create corresponding GitHub Issue.
4. Put GitHub Issue number in task document.
5. Start implementation.
```

Do not start implementation before the task is documented unless the user explicitly requests a quick fix.

---

## 6. GitHub Issue Format

Use this format:

```md
## Objective

<short objective>

## Requirements

- Requirement 1
- Requirement 2

## Acceptance Criteria

- [ ] Criteria 1
- [ ] Criteria 2

## Technical Notes

<short technical notes>

## Task Documentation

`docs/tasks/TASK-001-name.md`
```

Issue title:

```text
TASK-001: User Authentication
```

Use the task ID in both:

```text
docs/tasks/TASK-001-user-authentication.md
```

and:

```text
TASK-001: User Authentication
```

---

# 7. Task Status

Use only these statuses:

```text
Todo
In Progress
Blocked
Review
Done
```

Task document:

```md
## Status

In Progress
```

GitHub Issue should use corresponding labels when available:

```text
status:todo
status:in-progress
status:blocked
status:review
status:done
```

Do not invent multiple labels for the same status.

---

# 8. Updating Tasks

When implementation changes:

```text
1. Update task document.
2. Update GitHub Issue if scope/status changed.
3. Implement code.
4. Update acceptance criteria.
5. Run tests.
6. Mark task Done only after validation.
```

Acceptance criteria must reflect actual implementation.

Example:

```md
## Acceptance Criteria

- [x] User can register.
- [x] Email validation exists.
- [x] Duplicate email is rejected.
- [x] Tests pass.
```

---

# 9. Git Commit ↔ Task

Every task-related commit should reference the task ID.

Preferred:

```text
feat(auth): add user registration [TASK-001]
```

or:

```text
fix(auth): handle duplicate email [TASK-001]
```

Pull Requests should reference the GitHub Issue.

Example:

```text
Closes #123
```

when the PR completely resolves the issue.

---

# 10. Pull Request

PR description should contain:

```md
## Task

TASK-001

## Changes

- Added registration endpoint
- Added validation
- Added tests

## Testing

- `npm run typecheck`
- `npm run test`

## Documentation

`docs/tasks/TASK-001-user-authentication.md`

Closes #123
```

Keep PR descriptions concise.

---

# 11. Task Workflow

Use this workflow for every feature:

```text
Requirement
    ↓
TASK-XXX document
    ↓
GitHub Issue
    ↓
Test
    ↓
Implementation
    ↓
Validation
    ↓
Documentation update
    ↓
Pull Request
    ↓
GitHub Issue closed
```

For bugs:

```text
Bug Report
    ↓
TASK-XXX
    ↓
GitHub Issue
    ↓
Reproduce
    ↓
Root Cause
    ↓
Regression Test
    ↓
Fix
    ↓
Validate
    ↓
PR
    ↓
Close Issue
```

---

# 12. Do Not Create Duplicate Documentation

Before creating a task:

```text
Search docs/tasks/
Search GitHub Issues
Search existing implementation
```

If an existing task covers the same work:

- Reuse it.
- Update it if necessary.
- Do not create a duplicate task.

---

# 13. Large Tasks

Break large tasks into subtasks.

Example:

```text
TASK-010: Authentication

TASK-010.1: Database schema
TASK-010.2: Registration API
TASK-010.3: Login API
TASK-010.4: Session management
TASK-010.5: Frontend authentication
TASK-010.6: E2E tests
```

Only create subtasks when the task is genuinely large.

Avoid excessive task fragmentation.

---

# 14. Small Tasks

For small fixes, still create a task document and GitHub Issue unless explicitly told otherwise.

Example:

```text
TASK-025: Fix Invoice Total Calculation
```

Do not create unnecessary subtasks.

---

# 15. Definition of Done

A task is DONE only when:

- [ ] Requirements implemented.
- [ ] Acceptance criteria satisfied.
- [ ] Relevant tests pass.
- [ ] TypeScript passes.
- [ ] Security validated.
- [ ] No unrelated files changed.
- [ ] Task document updated.
- [ ] GitHub Issue updated.
- [ ] Pull Request created when applicable.
- [ ] GitHub Issue closed after successful merge.

---

# 16. AI Working Rules

Claude must:

- Search existing tasks before creating a new task.
- Create/update `docs/tasks/`.
- Create/update the corresponding GitHub Issue.
- Keep task documentation concise.
- Keep GitHub Issues concise.
- Reference `TASK-XXX` in commits.
- Reference the GitHub Issue in PRs.
- Update acceptance criteria after implementation.
- Verify tests before marking Done.
- Avoid unrelated changes.

Claude must NOT:

- Start large feature implementation without a task.
- Create duplicate tasks.
- Create duplicate GitHub Issues.
- Create excessive documentation.
- Copy the entire PRD into every task.
- Close an Issue before the implementation is verified.
- Mark a task Done when tests are failing.

---

# 17. Preferred Development Loop

```text
Understand
    ↓
Search existing tasks
    ↓
Create/update TASK-XXX
    ↓
Create/update GitHub Issue
    ↓
Write tests
    ↓
Implement
    ↓
Validate
    ↓
Update task docs
    ↓
Update GitHub Issue
    ↓
Commit with TASK-XXX
    ↓
Pull Request
    ↓
Close Issue
```

Keep every step minimal.

---

# 18. Final Response

After completing a task, report only:

```text
TASK-001 completed.

Docs:
docs/tasks/TASK-001-name.md

GitHub:
#123

Changes:
- <short summary>

Validation:
- <test command>
- <result>
```

If blocked:

```text
TASK-001 blocked.

Reason:
<exact reason>

Need:
<exact requirement>
```