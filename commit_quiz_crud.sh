#!/usr/bin/env bash
set -e
git add -A 2>&1
git status --short 2>&1 | head -40
echo "---DIFF STAT---"
git diff --cached --stat 2>&1 | head -40
echo "---COMMIT---"
git commit -m "$(cat <<'EOF'
feat: quiz create and edit [TASK-014, TASK-015]

- Add QuizStatus enum and unique slug generation in QuizService
- Add create and edit Volt pages with validation
- Add quizzes/create and quizzes/{quiz}/edit routes
- Link Create Quiz button and Edit action in quiz list
- Add feature tests for create and edit

Closes #14
Closes #15

Co-Authored-By: Claude Code <noreply@anthropic.com>
EOF
)" 2>&1
echo "COMMIT_EXIT:$?"
git log --oneline -3 2>&1 | head -5
