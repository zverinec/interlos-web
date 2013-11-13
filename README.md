InterLoS website with online scoring system
-------------------------------------------

Authors: Jan Papoušek and Jan Drábek

Maintenance: Jan Drábek <me@jandrabek.cz>

Requirements
------------

- PHP >= 5.3
- PHP in congfiguration to run Nette framework, see http://doc.nette.org/cs/requirements
- MySQL (MariaDB) database


Installation
------------

1. Checkout this repository
2. Set WWW dir to /public/
3. Run `composer update` from root. When installing production server use `--no-dev` to use minified nette.
4. Copy /app/config/config.local.neon.example to /app/config/config.local.neon and overwrite parameters of config.neon (DB credentials...)
5. Make directories /temp and /log writable
6. Create database schema executing /resources/db/tables.sql and /resources/db/views.sql
7. Create temporary tables


Config
------
- Set mails which are used for sending mail
- Set cron and admin keys (randomly generated long password)
- Set database credentials


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

Running the contest
-------------------

1. Create series of task (ciphers, logical, programming) in table `serie` (column `to_show` should be ignored)
2. Populate table `task` with correct answers. Provide codes in upper case (just for case)
3. Run CRON jobs (generating statistics)
   /cron/database?admin-key=hesloproadmin OR /cron/database?cron-key=hesloprocron NEVER BOTH OF THEM
3. Run it! Test answering with testing team and then remove the answer.
4. Monitor server performance.
5. Disable CRON jobs

