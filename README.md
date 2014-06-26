What?
===============

Its a Drupal 8 module which uses Drupal 8 Migration API to migrate "content" (users, nodes along with fields) of drupalladder.org site from Drupal 7 to Drupal 8.

Why?
===============
As of writing this module the core `migrate_drupal` supports only migration from Drupal 6 to Drupal 8. It does not support migration of content from Drupal 7.

So I created this module.

How?
===============
To run/execute migration you need Drush 7.

1. create a yaml manifest file (eg. manifest.yml) with list of migrations. Currently this module supports only following migrations: 
 - `d7_user` 
 - `d7_page`
 - `d7_lesson`
 - `d7_ladder`

2. run `drush migrate-manifest <path-to-manifest-file> --db-url=<mysql://root:pass@127.0.0.1/d7_db_name>`

TODO
================
* Migrate fields
* Currently users must be migrated before node. Otherwise `uid` values are getting messsed up. Create Dependency.

