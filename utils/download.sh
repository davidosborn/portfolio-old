#!/bin/bash

. $(dirname "$0")/config.sh

SSHSOCKET="~/.ssh/$USER@$HOST"
ssh -M -f -N -o "ControlPath=$SSHSOCKET" "$USER@$HOST"
(IFS=':'; for file in $NOREPO; do
	(set -x; scp -o "ControlPath=$SSHSOCKET" -p "$USER@$HOST:$DOCROOT/$file" "$file")
	done)
ssh -S "$SSHSOCKET" -O exit "$USER@$HOST"
