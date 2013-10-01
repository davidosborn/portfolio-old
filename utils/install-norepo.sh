#!/bin/bash

. $(dirname "$0")/config.sh

set -x
scp $USER@$HOST:backup/portfolio-norepo.tar portfolio-norepo.tar)
tar -xvf portfolio-norepo.tar
rm portfolio-norepo.tar
