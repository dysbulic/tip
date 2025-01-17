#!/bin/sh

# PRE-COMMIT HOOK
#
# The pre-commit hook is invoked before a Subversion txn is
# committed.  Subversion runs this hook by invoking a program
# (script, executable, binary, etc.) named 'pre-commit' (for which
# this file is a template), with the following ordered arguments:
#
#   [1] REPOS-PATH   (the path to this repository)
#   [2] TXN-NAME     (the name of the txn about to be committed)
#
# The default working directory for the invocation is undefined, so
# the program should set one explicitly if it cares.
#
# If the hook program exits with success, the txn is committed; but
# if it exits with failure (non-zero), the txn is aborted, no commit
# takes place, and STDERR is returned to the client.   The hook
# program can use the 'svnlook' utility to help it examine the txn.
#
# On a Unix system, the normal procedure is to have 'pre-commit'
# invoke other programs to do the real work, though it may do the
# work itself too.
#
#   ***  NOTE: THE HOOK PROGRAM MUST NOT MODIFY THE TXN, EXCEPT  ***
#   ***  FOR REVISION PROPERTIES (like svn:log or svn:author).   ***
#
#   This is why we recommend using the read-only 'svnlook' utility.
#   In the future, Subversion may enforce the rule that pre-commit
#   hooks should not modify the versioned data in txns, or else come
#   up with a mechanism to make it safe to do so (by informing the
#   committing client of the changes).  However, right now neither
#   mechanism is implemented, so hook writers just have to be careful.
#
# Note that 'pre-commit' must be executable by the user(s) who will
# invoke it (typically the user httpd runs as), and that user must
# have filesystem-level permission to access the repository.
#
# On a Windows system, you should name the hook program
# 'pre-commit.bat' or 'pre-commit.exe',
# but the basic idea is the same.
#
# Here is an example hook script, for a Unix /bin/sh interpreter:

REPOS="$1"
TXN="$2"

# Make sure that the log message contains some text.
SVNLOOK=/usr/bin/svnlook
# $SVNLOOK log -t "$TXN" "$REPOS" | grep "[a-zA-Z0-9]" > /dev/null || exit 1

# Check that the author of this commit has the rights to perform
# the commit on the files and directories being modified.

CONFIG=$REPOS.commit-access-control.cfg
ACCESS=/usr/lib/subversion/hook-scripts/commit-access-control.pl

[ -r "$CONFIG" ] || (echo "Missing configuration: $CONFIG" >&2; exit 1)
[ -x "$ACCESS" ] || (echo "Missing auth check: $ACCESS" >&2; exit 1)

"$ACCESS" "$REPOS" "$TXN" "$CONFIG" || exit 1

# All checks passed, so allow the commit.
exit 0
