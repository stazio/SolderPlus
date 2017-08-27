#!/usr/bin/env bash
USERNAME=$DEPLOY_USERNAME
PASSWORD=$DEPLOY_PASSWORD
BRANCH=$TRAVIS_BRANCH
VERSION=$TRAVIS_BUILD_NUMBER
REPO=SolderPlus

composer create-build
curl -T latest-build/latest-build.zip ftp://${USERNAME}:${PASSWORD}@staz.io/${REPO}/${BRANCH}-${VERSION}.zip