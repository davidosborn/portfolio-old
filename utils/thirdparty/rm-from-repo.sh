#!/usr/bin/env bash

# Removes a specific file from a Git repository.
# Written by James McMahon for StackOverflow.
# Licensed under cc-wiki (cc-by-sa).
# http://stackoverflow.com/a/11277470
# http://stackoverflow.com/questions/11255802
# http://creativecommons.org/licenses/by-sa/2.5/

git filter-branch --index-filter 'git rm -r -q --cached --ignore-unmatch '$1'' --prune-empty --tag-name-filter cat -- --all

rm -rf .git/refs/original/
git reflog expire --expire=now --all
git gc --prune=now
git gc --aggressive --prune=now
