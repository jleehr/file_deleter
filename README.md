Drupal File Deleter Modul

Deletes files provided by a CSV file.

## Requirements

  * Drupal 9 | 10
  * Drush >9

## Installation

* Install as you would normally install a contributed Drupal module. See:
  https://drupal.org/documentation/install/modules-themes/modules-8
  for further information.

## Usage

  * Create a CSV file with the file paths including the prefixed stream wrapper.
    There should be one file in each row.
    E.g. public://path_to_file/file.ext

  * Name the CSV file `delete.csv` and copy it to the public file path.

  * Execute the Drush command `drush file-deleter:run`.

  * The files will be deleted. Files that are not deleted are logged.
