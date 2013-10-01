#!/bin/bash

. $(dirname "$0")/config.sh

set -x
echo -n "$NOREPO" | tr ':' '\0' | tar -cvf portfolio-norepo.tar --null -T-
#scp portfolio-norepo.tar $USER@$HOST:backup/portfolio-norepo.tar
