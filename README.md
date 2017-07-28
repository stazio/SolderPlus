SolderPlus
=============

[![License](https://poser.pugx.org/solder/solder/license.svg)](https://packagist.org/packages/solder/solder)   

What is SolderPlus?
-------------
SolderPlus is a continuation of [TechnicSolder](http://docs.io).
SolderPlus fixes many issues with the older solder, and implements a better workflow.

Why Did I Continue It?
--------------
I feel as if TechnicSolder had a lot of issues with workflow, and must-have features that were never implemented.
It was also extremely difficult for the less-technical-minded people to setup.

Requirements
-------------

* PHP 5.5+
* PHP MCrypt Extension
* PHP Curl Extension
* PHP GD Extension
* A sqllite, mysql, pgsql, or sqlsrv database
* Composer - PHP Dependency Manager - http://getcomposer.org

Installation
-------------
```text
git clone https://github.com/stazio/SolderPlus.git
cp app/config-sample app/config
composer install --no-dev
```

Now you will need to copy an API key from the [TechnicPlatform](https://www.technicpack.net/login).

Your name in the corner -> Edit my profile -> Solder Configuration.

Copy the API key into the Configure Solder -> API Key Management page.

Now you are free to use Solder with your modpacks! Enjoy!

New Features
-----
- Installer (Yes I know it's not done)
- Uploading files

TODO
-----
- Bulk Uploader
- Server Installer
    - Endpoint
    - App
- Auto Updater
- Create a rolling release system
    - TravisCI

Troubleshooting
---
- Email me at [staz@staz.io](mailto:staz@staz.io).
- Submit an [issue](https://github.com/stazio/SolderPlus/issues).