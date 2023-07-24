InterLoS website with online scoring system
-------------------------------------------

Authors: Jan Papoušek and Jan Drábek

Maintenance: Jan Drábek <jan@drabek.cz>

Requirements
------------

- PHP >= 8.2
- PHP in configuration to run Nette framework, see http://doc.nette.org/cs/requirements
- MariaDB 10.6 (MySQL a MariaDB of higher version may not work due to slight differences in behaviour)
- Composer to install dependencies
- For local deployment CLI tool `loopbind` installed via `composer global require kiwicom/loopbind`.

Hardware
--------

With 100 teams and about 400 players, there is always an initial peak in load, however then it drops and starts raising in the second half of the
competition (probably due to database cron job.)

Tested configuration: 4 CPU, 4 GB RAM virtual server.

Installation
------------

1. Checkout this repository
2. Set WWW dir to /public/
3. Run `composer install` from root. When installing production server use `--no-dev` to use minified nette.
4. Copy /app/config/config.local.neon.example to /app/config/config.local.neon and overwrite parameters of config.neon (DB credentials...)
5. Make directories /temp and /log writable
6. Create database schema executing /resources/db/tables.sql and /resources/db/views.sql
7. Create temporary tables (by running CRON manually)


Config
------
- Set mails which are used for sending mail
- Set cron and admin keys (randomly generated long password)
- Set database credentials
- Set smtp delivery and test registration e-mails


Creating new contest
--------------------

1. Create contest and set dates in table `year`:
   Name - year, like 2013, used for sorting to get latest year
   Registration start
   Registration end - beware of crossed dates
   Game start
   Game end - beware of crossed dates
2. Test registration by registering first team
3. To run

Cron example
------------

```
# Každou minutu
* * * * * wget -O /dev/null http://interlos-devel.fi.muni.cz/cron/database?cron-key=xyh88QhcVIZNuOJFTtlX > /dev/null 2>&1
# Každých pět minut
*/5 * * * * wget -O /dev/null http://interlos-devel.fi.muni.cz/cron/database?cron-key=xyh88QhcVIZNuOJFTtlX > /dev/null 2>&1
```

Running the contest
-------------------

0. Notify infrastructure operators about future usage (describe event, estimate number of people, describe amount of resources, request contact to technican which will be available)
1. Create series of task (ciphers, logical, programming) in table `serie` (fill `to_show` properly, to prevent answering before release whole series)
2. Populate table `task` with correct answers. Provide codes in upper case (just for case)
3. Update config - set page with in game information which will be shown below the header after the game beginning.
4. Udpate page (`app/FrontendModule/templates/Game/default.latte`) with proper URLs where the files are hosted.
5. Upload files for tasks and check web server has access to change file name `.htacess` to `.old-haccess`.
6. Run CRON job (generating statistics)
   /cron/database?admin-key=hesloproadmin OR /cron/database?cron-key=hesloprocron NEVER BOTH OF THEM.
7. Run it! Test answering with testing team and then remove the answer in the database.
8. Monitor server performance.
9. Disable CRON jobs

The statistics are automatically hidden 30 minutes before game end. Admins can get access by appending `?admin-key=hesloproadmin` to the URL.

Performance
-----------

Typical configuration before 2014 was 4 GB RAM, 4 CPUs which made the game totally ok for 150 teams.
However there was a higher peak at game start due high IO.

In 2016 we used configuration with 6 GB RAM and 6 CPUs (just beacuse our limit have allowed that) and it was totally ok for 200 teams, not even high peak was visible at game start. 

File hosting
------------

Prepare at least two servers with Apache and PHP (.htaccess needed). Prepare and test them in advance, so links to all series will be available at least 
30 minutes before (due to high load when game starts).

Revealing script
----------------

Prepare .htaccess file:
```
Deny from all
<FilesMatch "^$">
	Allow from all
</FilesMatch>
<FilesMatch "index.php">
	Allow from all
</FilesMatch>
```

Prepare index.php for each serie:
```
<?php
date_default_timezone_set('Europe/Prague');
if(new DateTime() >= new DateTime("2013-11-30 15:00:00")) {
	@rename(".htaccess",'.htaccess-old');
} else {
    die('Prvni sada bude zpristupnena az v 15:00.');
}
?>
Page content
```

Test:
1. that you can access index.php with index.php and / path
2. that PHP is running
3. that you cannot access other files in directory by direct path
4. that scripts works - renames .htaccess in proper time (permission to the parent dir must be set correctly).

Doing erratas
-------------

The system doesn't support multiple correct answers and later acceptance of additional correct answers is difficult and needs to be done due to following reasons:

- There is unique key on code, team and task, therefore the changes needs to be done to always keep this constraint.
- The correct answer needs to be manually added, or some other wrong option on that task needs to be modified (after removal on the correct answer), however do not forget:
    - The teams could (and typically at least some) get to the correct answer in the end, in such situation the correct answer has to be removed and some wrong answer has to be updated with the original correct code.
  (When doing this in batch you will need to use "temporary" table to store which IDs are to remove and which are to update)
    - All answers after the new correct answer should be removed (as their penalisation would still be counted).
    - With some task, the team could answer another wrong answer which should be evaluated as correct but was not found by the organisators yet!
    - There is no history or no going back! (There is `log` table but that is usable only for humans, not for automated queries and restoration.)
    - Preferred way is manual, when doing batch updates always backup database prior running the destroying queries.
    - Each such change can change scores of multiple teams (not only the team with the changed answer!) due to the penalisation for solved as nth, be aware of that and do not forget to update the results by running the CRON (especially when game has ended).


After the contest
-------------------

1. Disable CRON job.
2. Manually trigger (last) update of statistics.
3. Update homepage with results.
4. Commit whole year directory (archive also in-game information to the root of this folder; static html files recommended)
5. Save table with full statistics into static HTML (see previous yearXXXX).
6. Save list of teams into static HTML (see previous teamsYearXXXX).
7. Add entry to archive page about the year.


Developing locally
------------------

Use following setup for docker composition as we need real Apache and MySQL (as PHP built-in-server doesn't handle HTTPS, and with `secure` cookie flag you cannot use the system).

```bash
$ cd .                  # project root
$ nano .env             # IP address to bind (docker-machine vs native docker!)
$ loopbind apply        # apply IP address and hosts
$ docker-compose up
```

Then you have to manually install database via `http://${IP}:8080` (see `docker-compose.yml` for credentials).
Afterward the application is available via `https://interlos.test`, `https://${IP}` and `http://${IP}` (should be redirected immediately).

Database is persistent between up & downs and stored in `.mysql` directory.

Various notes
-------------

MySQL database handles timestamp and datetime types differently (timestamp is stored in UTC, datetime "as is") then translation to
current connection timezone is done see (https://www.eversql.com/mysql-datetime-vs-timestamp-column-types-which-one-i-should-use/).
