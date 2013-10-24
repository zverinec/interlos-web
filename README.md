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
3. Run `composer update` from root
4. Copy /app/config/config.local.neon.example to /app/config/config.local.neon and overwrite parameters of config.neon (DB credentials...)
5. Make directories /temp and /log writable
6. Create database schema executing /resources/db/tables.sql and /resources/db/views.sql


Creating new contest
--------------------

1. Create contest and set dates
2. Test registration

Running the contest
-------------------

1. Populate table with correct answers
2. Run CRON jobs
3. Contest!
4. Disable CRON jobs

