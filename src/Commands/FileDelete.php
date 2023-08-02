<?php

namespace Drupal\file_deleter\Commands;

use Drush\Commands\DrushCommands;

/**
 * FileDelete provides a drush command for deleting files.
 *
 * The files have to be added to a delete.csv file at the public file directory.
 * The contents are a comma separated list of files, prefixed with the stream wrapper.
 * E.g. public://myPath/myFile.ext
 */
class FileDelete extends DrushCommands
{
  private int $count = 0;

  /**
   * @command file-deleter:run
   */
  public function removeFiles(): void
  {
    // Reset the counter in case of multiple runs.
    $this->count = 0;
    $manager = \Drupal::service('stream_wrapper_manager');
    $pathObj = $manager->getViaUri('public://delete.csv');
    // Get the CSV contents.
    $uris = file($pathObj->realpath());
    foreach ($uris as $uri) {
      $uri = str_replace("\r", "", $uri);
      $uri = str_replace("\n", "", $uri);
      $filePathObj = $manager->getViaUri($uri);
      $this->doDelete($filePathObj->realpath());
    }
    // At last, remove the csv file itself.
    $this->doDelete($pathObj->realpath());
    // Show success message but without the source CSV file.
    $this->logger()->success(sprintf('Successfully deleted %d files.', $this->count-1));
  }

  private function doDelete(string $path): void
  {
    if (!file_exists($path)) {
      $this->logger()->notice(sprintf('Cannot delete file %s', $path));
      return;
    }

    try {
      unlink($path);
    } catch (\Exception $e) {
      $this->logger()->error($e->getMessage());
      return;
    }

    $this->count++;
  }
}
