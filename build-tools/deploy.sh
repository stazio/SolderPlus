#!/usr/bin/env bash
BRANCH=${TRAVIS_BRANCH}
VERSION=${TRAVIS_BUILD_NUMBER}

composer create-build
OUTPUT=app/version.php php build-tools/build-version.php ${BRANCH} ${VERSION}
curl -T latest-build/latest-build.zip ftp://${DEPLOY_USERNAME}:${DEPLOY_PASSWORD}@staz.io/${BRANCH}-${VERSION}.zip