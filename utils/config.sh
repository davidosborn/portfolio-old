#!/bin/bash

HOST='william-paca.dreamhost.com'
USER='davidcosborn'
#PASSWORD=
DOCROOT='davidcosborn.com/portfolio'

EXCLUDES='.*(~|(^|/)desktop.*\.ini)|\.(git(|ignore))|dump|framework/cache|LICENSE|portfolio.tar|README\.md|TODO|utils'
NOREPO=$(sed -n '/#NOREPO_BEGIN/,/#NOREPO_END/p' .gitignore | sed '/^\s*#/d' | tr '\n' ':' | sed 's/:$//')
KEEPS="framework/cache|${NOREPO//:/|}"

# change to site root directory
cd $(dirname "$0")/..
