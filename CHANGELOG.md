1.5.0
---
+ Added a filter in Build Management to show mods that can be updated
+ Fixed a bug where the File Explorers "return to" buttons redirected to the localhost/ equivalent.
+ Fixed a bug where the top navigation's Update Avaiable and help nav are on seperate lines when in tablet view.
+ Added a "Builds used in" view when in the mod view.
+ Fixes a bug where the panel heading does not expand on overflow
+ Fixed a bug where you can have identical versions
+ Added a "mod import" that imports mods from the mods repo folder.
+ Fixes a bug where mod uploading does not work like mentioned on Firefox.
+ Began work on a version updater. **NOTE:** This is in beta and no testing has been done
+ File upload limits are now visible

1.4.0
---
+ Did a proper (hopefully) rebranding to SolderPlus
+ Fixed a bug where the update utility does not properly cache files
+ Fixed a bug with the installer that does not show stages 4 and 5
+ Fixed a bug where the menu bar glitches out when scrolling on a tablet device
+ Fixed a bug where the installer appends an unnecessary '/' to the application URL
+ Made some help text more appropriate.
+ SolderPlus will now not allow you to upload a mod if it detects you are using Amazon S3 or an external service.
+ Created a new PlatformAPI library to connect with the Technic Platform. 
+ New checks have been added to ensure that there is no slug collision with the Technic Platform.
+ Checks have also been added to alert the user when the modpack is not on the Technic Platform.
+ Additional integrations has been added    when editing a modpack
+ Sidebar now expands when a menu is opened
+ Changed composer dependencies to be stable / latest versions
+ SolderPlus will now not overwrite the config folder when updating. Updating is still overall a difficult process.

1.3.0
----
+ Added in a file viewer.
+ Added back the update checker.

1.2.0 / 1.2.1
-----
+ You can now automatically generate server packs that you can
download.

1.0.1
----
+ The installer will now guide you through adding 
the Technic Platform API key to your solder installation.


1.0.0
---
+ Installer
+ Added the option to choose if a build is published, 
latest, and recommended from the build manage page.
+ Updated the UI to be a bit more manageable.
- Removed the update checker