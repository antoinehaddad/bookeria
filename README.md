Online library using Symfony 4
========================

This library


Requirements
------------

  * PHP 7.1.3 or higher;
  * Composer 
  * PHP extensions (fileinfo, pdo_sqlite and intl)

Installation
------------

```bash
$ git clone https://github.com/antoinehaddad/bookeria.git
$ cd bookeria;  composer install 
```

Usage
-----

Create demo user via command line 
```bash
$ cd bookeria
$ php bin/console app:create-user demo demopass
```
command to run the built-in web server and access the application in your
browser at <http://localhost:8000>:
```bash
$ cd bookeria
$ php bin/console server:run
```

Commands to display authors and/or books
```bash
$ cd bookeria
$ php bin/console app:library --display authors books
$ php bin/console app:library --help
```

Api endpoint 
http://localhost:8000/api/

Api search books before date
http://localhost:8000/api/books_show?before=2018-11-05

Api search books after date
http://localhost:8000/api/books_show?after=2018-11-03

Api search books by author nationality
http://localhost:8000/api/books_show?nationality=GB