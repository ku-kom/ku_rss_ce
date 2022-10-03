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
use DOMElement;
use Exception;
use DOMdocument;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use UniversityOfCopenhagen\KuRssCe\Service\RssAppService;

class RssController extends ActionController
{
    /**
     * @var RssAppService
     */
    protected $rssAppService;

    /**
     * @param RssAppService $rssAppService
     * @noinspection PhpUnused
     */
    public function injectRssAppService(RssAppService $rssAppService)
    {
        $this->rssAppService = $rssAppService;
    }


    /**
     * Request url and return response to fluid template.
     * @return ResponseInterface
     */
    public function getFeedAction()
    {
        $feedurl = $this->configurationManager->getContentObject()->data['ku_rss_ce'];
        $itemsPerPage = $this->configurationManager->getContentObject()->data['ku_rss_items'];

        // Check the template is a valid URL
        if (false === filter_var($feedurl, FILTER_VALIDATE_URL)) {
            $message = sprintf('Feed URL is not valid "%s". Update your settings.', $feedurl);
            throw new \RuntimeException($message, 1320651278);
        }

        $data = file_get_contents($feedurl);
        $xml = simplexml_load_string($data);
      

        $feed = $this->parseData($xml, $itemsPerPage);
        //debug($feed);
        
        // try {
        //     $feed = $this->fetchData($xml);
        // } catch (Exception $exception) {
        //     // Sisplay error message
        //     $this->addFlashMessage(
        //         (string)\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('rss_warningmsg', 'ku_rss_ce'),
        //         '',
        //         FlashMessage::ERROR,
        //         false
        //     );
        // }

        $this->view->assign('feed', $feed);
    }

    protected function parseData($url, $number)
    {
        if (!empty($url)) {
            $items = [];
            $i = 0;
            foreach($url->channel->item as $entry) {
                if ($i >= $number) {
                    break;
                }
                debug($entry);
                
                $items[] = [
                    'link' => $entry->link,
                    'title' => $entry->title,
                    'image' => '',
                    'description' => $entry->description,
                    'pubDate' => $entry->pubDate
                ];
                $i ++;
            }
            return $items;
        }
    }
    
}
