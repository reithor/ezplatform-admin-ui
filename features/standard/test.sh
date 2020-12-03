#!/bin/bash

HAS_PR=$(git ls-remote -h -h | grep "adapt-travis-updates-ibexa-co" | wc -l)
echo "${HAS_PR}"
if [ "${HAS_PR}" -eq 1 ] ; then
    echo -e "Repo already has a PR. Skipping"
fi
