<?php
namespace SiteMaster\Core\Auditor;

use SiteMaster\Core\Auditor\Site\Page;
use SiteMaster\Core\Config;
use SiteMaster\Core\Controller;
use SiteMaster\Core\FlashBagMessage;
use SiteMaster\Core\Registry\Registry;
use SiteMaster\Core\Registry\Site;
use SiteMaster\Core\User\Session;
use SiteMaster\Core\ViewableInterface;
use SiteMaster\Core\Auditor\Scan;

class QATestController implements ViewableInterface
{
    public $url;
    public $site;
    public $scan;
    public $page;
    public $current_user;
    
    public function __construct($options = array())
    {
        $this->current_user = Session::getCurrentUser();

        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->url = $_SERVER['HTTP_REFERER'];
        }
        
        if (isset($options['url'])) {
            $this->url = $options['url'];
        }
        
        if (NULL != $this->url) {
            $registry = new Registry();
            $this->site = $registry->getClosestSite($this->url);
        }
        
        if ($this->site) {
            //only find a scan if we found a site
            $this->scan = $this->site->getLatestScan(true);
        }
        
        if ($this->scan) {
            //Only try to find a page scan if we found a site scan
            $this->page = Page::getByScanIDAndURI($this->scan->id, $this->url);
        }
        
        if ($this->page) {
            //This page has been scanned, let's redirect to it.
            $message = new FlashBagMessage(
                FlashBagMessage::TYPE_INFO,
                'Welcome to ' . Config::get('SITE_TITLE') . '! Here you will find the latest report for ' . $this->page->uri . '.  The report is designed to help site developers find and fix possible problems.'
            );
            Controller::addFlashBagMessage($message);
            Controller::redirect($this->page->getURL());
        }

        
    }
    
    public function getRecommendedSiteURL()
    {
        $registry = new Registry();
        return $registry->getRecommendedBaseURL($this->url);
    }
    
    public function getURL()
    {
        return Config::get('URL') . 'qa-link/';
    }

    public function getPageTitle()
    {
        return 'Quality Assurance';
    }
    
    public function getPageScanForm()
    {
        return new Page\ScanForm(array('uri'=>$this->url));
    }
}
