<?php
namespace SiteMaster\Core\Auditor\Logger;

use DOMXPath;

use SiteMaster\Core\Auditor\Site\Page;

abstract class PageTitleInterface extends \Spider_LoggerAbstract
{
    /**
     * @var bool|Page
     */
    protected $page = false;

    function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function log($uri, $depth, DOMXPath $xpath)
    {
        $this->page->title = $this->getPageTitle($xpath);
        $this->page->save();
    }

    /**
     * Get the Page Title
     *
     * @param DOMXPath $xpath the xpath of the page
     * @return bool|string the page title
     */
    abstract public function getPageTitle(DOMXPath $xpath);
}
