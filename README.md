SolderPlus
=============
[![Build Status](https://travis-ci.org/stazio/SolderPlus.svg?branch=master)](https://travis-ci.org/stazio/SolderPlus)
[![License](https://poser.pugx.org/solder/solder/license.svg)](https://packagist.org/packages/solder/solder)   

What is SolderPlus?
-------------
SolderPlus is a continuation of [TechnicSolder](http://solder.io).
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
* A SQLlite, mysql, pgsql, or sqlsrv database
* Curl

Installation
-------------
Installation is easy! Download the latest version [here](https://github.com/stazio/SolderPlus/releases).
Extract the zip into the root of your server.
**Note** that the *public* folder should be the main folder for production. 
On some servers it could be called *public_html* or *htdocs*. Rename it accordingly.

Then, simply open the installation in your favorite browser and the installer will guide you through the process.

For more detailed information view the wiki guide [here](TODO).  


Dev Installation
----------------
Run the following command. 
```text
git clone https://github.com/stazio/SolderPlus.git
cp app/config-sample app/config
composer install --no-dev
```
To serve run ```php artisan serve```.

Now you will need to copy an API key from the [TechnicPlatform](https://www.technicpack.net/login).

Your name in the corner -> Edit my profile -> Solder Configuration.

Copy the API key into the Configure Solder -> API Key Management page.

Now you are free to use Solder with your modpacks! Enjoy!

TODO
-----
- Bulk Uploader
- Auto Updater
- Test the user permissions system and fix accordingly. 

Troubleshooting
---
- Email me at [staz@staz.io](mailto:staz@staz.io).
- Submit an [issue](https://github.com/stazio/SolderPlus/issues).

Contributing
---
Just, submit pull requests! I'll update accordingly. Please use the master branch of SolderPlus when forking.