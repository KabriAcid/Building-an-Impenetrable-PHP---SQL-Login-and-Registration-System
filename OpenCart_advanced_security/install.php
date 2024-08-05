<?php
// I wrote this script for the installation of the plugin

// I imported necessary classes for installation
use Phpml\Classification\KNearestNeighbors;
use Phpml\Dataset\CsvDataset;

// I set the installation function
function install() {
    // I created the security log file
    $logfile = DIR_LOGS . 'security.log';
    if (!file_exists($logfile)) {
        touch($logfile);
    }

    // I wrote the headers to the log file
    $log = fopen($logfile, 'w');
    fputcsv($log, ['username', 'password', 'timestamp']);
    fclose($log);
}

// I set the uninstallation function
function uninstall() {
    // I deleted the security log file
    $logfile = DIR_LOGS . 'security.log';
    if (file_exists($logfile)) {
        unlink($logfile);
    }
}
?>
