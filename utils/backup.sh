#!/bin/bash

. $(dirname "$0")/config.sh

(set -x; echo -n "$NOREPO" | tr ':' '\0' | tar -cvf portfolio.tar --null -T-)
