<?php

declare(strict_types=1);

namespace UniversityOfCopenhagen\KuRssCe\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
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
        $cObjectData = $this->configurationManager->getContentObject()->data;
        $url = $cObjectData['ku_rss_ce'];
        $itemsPerPage = $cObjectData['ku_rss_items'];
        

        // Check the template is a valid URL
        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            $message = sprintf('Feed URL is not valid "%s". Update your settings.', $url);
            throw new \RuntimeException($message, 1320651278);
        }

        if (!empty($url)) {
            $response = $this->requestFactory->request($url, 'GET');

            // Get the content on a successful request
            if ($response->getStatusCode() === 200) {

                $feedtype = '';
                if (false !== strpos($response->getHeaderLine('Content-Type'), 'application/atom+xml')) {
                    $feedtype = 'atom';
                } elseif (false !== strpos($response->getHeaderLine('Content-Type'), 'application/rss+xml')) {
                    $feedtype = 'rss';
                }
       
                $data = $response->getBody()->getContents();
                $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NOCDATA);
                $json = json_encode($xml);
                $feed = json_decode($json, true);

                //debug($feed);

                if ($feedtype == 'rss') {
                    // Feed is RSS 2.0
                    $items = $feed['channel']['item'];
                    $this->view->assign('type', 'rss');
                    $this->view->assign('title', $feed['channel']['title']);
                    $this->view->assign('description', $feed['channel']['description']);
                    $this->view->assign('data', $cObjectData);
                } else {
                    // Feed is Atom
                    $items = $feed['entry'];
                    $this->view->assign('type', 'atom');
                    $this->view->assign('title', $feed['title']);
                    $this->view->assign('data', $cObjectData);
                }
                $this->view->assign('feed', $items);
                $this->view->assign('numberOfItems', $itemsPerPage);
            } else {
                // Sisplay error message
                $this->addFlashMessage(
                    (string)\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('rss_warningmsg', 'ku_rss_ce'),
                    '',
                    FlashMessage::ERROR,
                    false
                );
            }
        }
        return $this->htmlResponse();
    }
}
