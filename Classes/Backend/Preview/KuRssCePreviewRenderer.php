<?php
declare(strict_types=1);

/*
 * This file is part of the package ku_rss_ce.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace UniversityOfCopenhagen\KuRssCe\Backend\Preview;

use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;

class KuRssCePreviewRenderer implements PreviewRendererInterface
{
    public function renderPageModulePreviewHeader(GridColumnItem $item): string
    {
        // $record = $item->getRecord();
        // return $record['CType'];
        
        return $this->getLanguageService()->sL('LLL:EXT:ku_rss_ce/Resources/Private/Language/locallang_be.xlf:linklabel');
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $rssUrl = '';
        $record = $item->getRecord();
        if ($record['ku_rss_ce']) {
            $rssUrl .= $rssUrl;
        }
        return $record['ku_rss_ce'];
    }

    public function renderPageModulePreviewFooter(GridColumnItem $item): string
    {
        return '';
    }

    public function wrapPageModulePreview(string $previewHeader, string $previewContent, GridColumnItem $item): string
    {
        $previewHeader = $previewHeader ? '<div class="content-element-preview-ctype">' . $previewHeader . '</div>' : '';
        $previewContent = $previewContent ? '<div class="content-element-preview-content">' . $previewContent . '</div>' : '';
        $preview = $previewHeader || $previewContent ? '<div class="ku-rss-ce">' . $previewHeader . $previewContent . '</div>' : '';
        return $preview;
    }

    /**
     * Returns LanguageService
     *
     * @return \TYPO3\CMS\Core\Localization\LanguageService
     */
    protected function getLanguageService(): \TYPO3\CMS\Core\Localization\LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
