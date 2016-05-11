Jira Agile Burndown
=============

Install
-------

_If you are willing to use our Vagrantfile, the project lives inside the /vagrant directory_

### Install composer

_This is done automatically in the vagrant box._
```bash
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    # php -r "if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    sudo mv composer.phar /usr/local/bin/composer
```

### Install dependencies

```bash
composer install
```

### Prepare the database

```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
```

Workflow
--------

### Start a sprint

```bash
# will create an empty entry in the database
php bin/console agile:sprint:create SPRINT_NAME
# Retrieve all issues and set them as started when the sprint started
php bin/console agile:sprint:sync -i
```

If, for some reason, some tickets have been added between the time the sprint started and the time you use the `agile:sprint:sync -i` command, you need to mark each issue as added:
```bash
php bin/console agile:issue:added ISSUE_ID
```

### Synchronize data

If you want to get the status after the sprint is started, you must synchronize with Jira

```bash
php bin/console agile:sprint:sync
```

### Show the progress

```bash
php bin/console agile:sprint:status
```

Example of output:

> * 16 Total issues

Number of issues that should be treated

> * 3 Added issues

Number of issues added after the sprint started

> * 5 Completed issues

Number of issues completed

> * 11 Not completed issues

Number of issues left

> * 10 Initial total complexity

Complexity when the sprint started.
When we create or burn-down chart, we trace a black dotted line to see our common global objective.

> * 3.5 Initial Completed complexity

> * 0.75 Added after sprint start total complexity

When we report this on our burn-down chart, we add this information each day on top of our black-dotted line

> * 0.75 Added after sprint start Completed complexity

Total Complexity spent on issues added after the sprint started.
This data is used in addition with "Initial completed complexity" data to report our current overall completed complexity.
