# Migrano: Database agnostic migrations

[![Author](https://img.shields.io/badge/author-@martinkrizan-blue.svg?style=flat-square)](https://twitter.com/martinkrizan)
[![Build Status](https://travis-ci.org/mnohosten/migrano.svg?branch=master)](https://travis-ci.org/mnohosten/migrano)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Migrano is database (or purpose) agnostic migration tool. It's made with easy of use in mind. 
 Migrano doesn't requires any specific configuration of database setup. Instead of that it lets you 
 define migration dependencies with dependency injection container. What you are doing in your 
 migrations is absolutely on you.

Migrano expects that you are using implementation of PSR-11 container like league/container, pimple/pimple, etc.
 
## How to use it?

1. Write migrations in specific directory
2. Define migration Application instance
3. Define which migration action you want execute
4. Run migration action

Migrano intentionally does not contain any binary execution like Phinx or other migration libraries
 because it's very easy to implement by yourself.
 
See [examples](./examples) to know more.
 
## Why do you need Migrano?

* You want to use other database than classic RDBMS and you need to deal with schema or 
 mapping updates.
* You can use Migrano also if your action needs rollback. For example installation of 
 your application and reverse steps on error.
 
===
 
*If you found this library useful. Let me know on [twitter](https://twitter.com/martinkrizan).*
  