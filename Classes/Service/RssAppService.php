<?php

namespace UniversityOfCopenhagen\KuRssCe\Service;

/***************************************************************
 *
 * Copyright notice
 *
 * (c) 2019 Thomas Deuling <typo3@coding.ms>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DateTime;
use DOMElement;
use Exception;
use DOMdocument;
use TYPO3\CMS\Core\Resource\Exception\ExistingTargetFolderException;
use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * RSS-App service
 *
 * @package rss_app
 * @subpackage Service
 *
 * @author Thomas Deuling <typo3@coding.ms>
 */
class RssAppService
{

    const BASE_PATH = 'uploads/tx_rssapp/';
    const INSTAGRAM_CACHE_BASE_PATH = 'uploads/tx_rssapp_cache/instagram/';

    protected function parseData($url)
    {
        if ($url) {
            $items = [];
            foreach ($url->channel->item as $entry) {
                $image = '';
                $image = 'N/A';
                $description = 'N/A';
                foreach ($entry->children('media', true) as $k => $v) {
                    $attributes = $v->attributes();
                
                    if ($k == 'content') {
                        if (property_exists($attributes, 'url')) {
                            $image = $attributes->url;
                        }
                    }
                    if ($k == 'description') {
                        $description = $v;
                    }
                }
                
                $items[] = [
                    'link' => $entry->link,
                    'title' => $entry->title,
                    'image' => $image,
                    'description' => $description,
                ];

            }
            return $items;
        }
    }
}
