#!/usr/bin/env bash

composer create-build
curl -T latest-build/latest-build.zip ftp://${USERNAME}:${PASSWORD}@staz.io/${BRANCH}-${VERSION}.zip