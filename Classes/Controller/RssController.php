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
        

        // Check if the url is valid
        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            $message = sprintf('Feed URL is not valid "%s". Update your settings.', $url);
            throw new \RuntimeException($message, 1320651278);
        }

        if (!empty($url)) {
            try {
                $response = $this->requestFactory->request($url, 'GET');
                if ($response->getStatusCode() === 200) {
                    // Check if source is RSS or Atom
                    $feedtype = '';
                    if (false !== strpos($response->getHeaderLine('Content-Type'), 'application/atom+xml')) {
                        $feedtype = 'atom';
                    } elseif (false !== strpos($response->getHeaderLine('Content-Type'), 'application/rss+xml')) {
                        $feedtype = 'rss';
                    }
       
                    $data = $response->getBody()->getContents();
                    
                    // Fix namespaces
                    $data = str_replace('<content:encoded>', '<contentEncoded>', $data);
                    $data = str_replace('</content:encoded>', '</contentEncoded>', $data);

                    // Turn xml into json
                    $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NOCDATA);
                    $json = json_encode($xml);
                    $feed = json_decode($json, true);

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
                }
            } catch (\Exception $e) {
                // Display error message
                $this->addFlashMessage(
                    'Error: ' . $e->getMessage(),
                    '',
                    FlashMessage::ERROR,
                    false
                );
            }
        }
        return $this->htmlResponse();
    }
}
