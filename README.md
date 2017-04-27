SolderPlus
=============

[![License](https://poser.pugx.org/solder/solder/license.svg)](https://packagist.org/packages/solder/solder)   

Join us on [Discord](https://discord.gg/0Ehm7p0AD3PdYLT1)!

What is SolderPlus?
-------------
SolderPlus is a continuation of [TechnicSolder](http://docs.io).
SolderPlus fixes many issues with the older solder, and implements a better workflow.

Why Did I Continue?
--------------
I feel as if TechnicSolder had a lot of issues with workflow, and must-have features that were never implemented.
It made it extremely difficult for the less-technical-minded people to use it.

Goals
--------------
- [ ] Add CurseForge mod fetching, so that you do not need to even download mods!
- [ ] Create a server-pack installer for SolderPlus.
- [ ] Streamline the adding of mods to modpacks.


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

Now you will need to copy an API key from the (TechnicPlatform)[https://www.technicpack.net/login].

Your name in the corner -> Edit my profile -> Solder Configuration.

Copy the API key into the Configure Solder -> API Key Management page.

Now you are free to use Solder with your modpacks! Enjoy!

New Features
-----
- Installer (Yes I know it's not done)
- Upload mods to SolderPlus

Troubleshooting
---
- Email me at [staz@staz.io](mailto:staz@staz.io).
- Submit an [issue](https://github.com/stazio/SolderPlus/issues)