#!/bin/bash

. $(dirname "$0")/config.sh

set -x
touch framework/cache/.timestamp
rsync -vat --delete \
	--include=external \
	--include=framework/cache \
	--include=framework/cache/.timestamp \
	--exclude=.git \
	--exclude=.gitignore \
	--exclude-from=.gitignore \
	--exclude=utils \
	. "$USER@$HOST:$DOCROOT"

# HACK: remove the local timestamp file to force the cached PHP/HTML files to
# regenerate on localhost
rm framework/cache/.timestamp
