<?php

declare(strict_types=1);

namespace UniversityOfCopenhagen\KuRssCe\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class RssController extends ActionController
{
    /**
     * Initiate the RequestFactory.
     */
    public function __construct(
        protected readonly RequestFactory $requestFactory,
    ) {
    }

    /**
     * Request url and return response to fluid template.
     * @return ResponseInterface
     */
    public function getFeedAction(): ResponseInterface
    {
       
        return $this->htmlResponse();
    }
}