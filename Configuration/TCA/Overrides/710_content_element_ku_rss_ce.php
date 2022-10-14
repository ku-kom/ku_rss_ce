<?php

/*
 * This file is part of the package ku_rss_ce.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 * Sep 2022 Nanna Ellegaard, University of Copenhagen.
 */

defined('TYPO3') or die('Access denied.');

// Add Content Element
if (!is_array($GLOBALS['TCA']['tt_content']['types']['ku_rss_ce'] ?? false)) {
    $GLOBALS['TCA']['tt_content']['types']['ku_rss_ce'] = [];
}

// Add content element to selector list
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:ku_rss_ce/Resources/Private/Language/locallang_be.xlf:title',
        'ku_rss_ce',
        'ku-rss-icon'
    ],
    'special',
    'after'
);

// KU RSS/Atom content element
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'ku_rss_ce' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:ku_rss_ce/Resources/Private/Language/locallang_be.xlf:linklabel',
        'config' => [
            'type' => 'input',
            'renderType' => 'inputLink',
            // Replace with the following in v.12:
            //'type' => 'link',
            //'allowedTypes' => ['page', 'url', 'record'],
            'eval' => 'lower,required'
        ],
    ],
    'ku_rss_items' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:ku_rss_ce/Resources/Private/Language/locallang_be.xlf:items',
        'config' => [
           'type' => 'text',
           'renderType' => 'input',
           'size' => 5,
           'eval' => 'trim,int',
           'min' => 1,
           'max' => 100,
           'default' => 10,
        ],
     ],
     'ku_rss_layout' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:ku_rss_ce/Resources/Private/Language/locallang_be.xlf:layout',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:ku_rss_ce/Resources/Private/Language/locallang_be.xlf:layuot_tiles', 'layuot_tiles'],
                ['LLL:EXT:ku_rss_ce/Resources/Private/Language/locallang_be.xlf:layuot_list', 'layuot_list'],
            ],
            'default' => 'layuot_tiles',

        ],
    ],
]);

$ku_rss_ce_settings = [
    'showitem' => '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,ku_rss_ce,ku_rss_items,ku_rss_layout,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        rowDescription,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ',
];

// Configure element type
$GLOBALS['TCA']['tt_content']['types']['ku_rss_ce'] = $ku_rss_ce_settings;

/**
 * Registers backend previewRenderer
 */

$GLOBALS['TCA']['tt_content']['types']['ku_rss_ce']['previewRenderer'] = \UniversityOfCopenhagen\KuRssCe\Backend\Preview\KuRssCePreviewRenderer::class;
