<?php

/*
 * This file is part of the package ku_phonebook.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
// Only include page.tsconfig if TYPO3 version is below 12 so that it is not imported twice.
if ($versionInformation->getMajorVersion() < 12) {
  ExtensionManagementUtility::addPageTSConfig('
      @import "EXT:ku_rss_ce/Configuration/page.tsconfig"
   ');
}

// Register plugin

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
  'ku_rss_ce',
  'Pi1',
  [\UniversityOfCopenhagen\KuRssCe\Controller\RssController::class => 'getFeed'],
  []
);
