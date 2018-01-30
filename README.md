# VanillaWoWDB2

========== AoWoW README ==========

AoWoW is MaNGOS database web interface written in PHP.
This version of AoWoW supports World of Warcraft 1.12.1 version.
Adapted initially by Shadowcrank of the Lights Hope (Formerly Elysium) vanilla WoW community.

---------- Information ----------

 - Database Interface: DBSimple <http://dklab.ru/lib/DbSimple/>
 - Template Engine: Smarty <http://www.smarty.net/>

---------- Requirements ----------

 - PHP, version 4.2.0 or higher. 7.X.X is welcome.
	- php-SimpleXML
	- php-GD
	- php-Mysqli
	- php-mbString
 - MySQL, version 4.X.X or higher. 5.X.X is welcome.

---------- How To Install (Ubuntu Linux 17.10) ----------

 - Download AoWoW: $ git clone https://github.com/MarkusNemesis/VanillaWoWDB2.git aowow
 - Configure AoWoW by editing configs/config.php.in file.
 - Rename configs/config.php.in to configs/config.php.
 - Fetch the world.db file from the following repository: https://github.com/brotalnia/database
 - Execute the world.db sql into your aowow database.
 - Execute the following sql files into your aowow database (from tools/sql/aowow/):
	* aowow.sql
	* aowow_zones.sql
	* faction.sql
	* news.sql (feel free to modify this one first to reflect your changes)
    * indexes.sql (DB indexes, in case they were not already created)
 - Execute the following into your realmd database (from tools/sql/realmd/)
	* realmd.sql (import into realmd schema for accounts login/commenting)

---------- Attention! ----------

 - This website requires full access to the cache folder: $ chmod -R 777 ./cache/
 - Everything created here is done under GPL. You can modify files or take program code or create project branches under GPL.
