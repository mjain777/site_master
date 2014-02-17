<?php
namespace SiteMaster\Plugins\Metric_links;

class MetricTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getLinks()
    {
        $metric = new Metric('metric_links');

        $xpath = $this->getTestXPath();
        
        $links = $metric->getLinks('http://www.test.com/', $xpath);
        
        $this->assertEquals(true, in_array('http://unlcms.unl.edu/university-communications/sitemaster/example-404', $links));
        $this->assertEquals(true, in_array('http://www.google.com/', $links));
        $this->assertEquals(true, in_array('http://unlcms.unl.edu/university-communications/sitemaster/example-redirect-301', $links));
        
        //These should not be in the links to check
        $this->assertEquals(false, in_array('javascript:void(0)', $links));
        $this->assertEquals(false, in_array('tel:555-555-5555', $links));
        $this->assertEquals(false, in_array('mailto:test@test.com', $links));
        $this->assertEquals(false, in_array('http://www.test.com/invalid', $links));
    }

    /**
     * @test
     */
    public function stripURIFragment()
    {
        $metric = new Metric('metric_links');

        $this->assertEquals('http://www.test.com/', $metric->stripURIFragment('http://www.test.com/#'));
        $this->assertEquals('http://www.test.com/', $metric->stripURIFragment('http://www.test.com/'));
        $this->assertEquals('http://www.test.com/?test=test', $metric->stripURIFragment('http://www.test.com/?test=test#test'));
    }

    /**
     * @test
     */
    public function checkLinks()
    {
        $metric = new Metric('metric_links');
        $xpath = $this->getTestXPath();
        $links = $metric->getLinks('http://www.test.com/', $xpath);
        
        $metric->checkLinks($links);
    }
    
    public function getTestXPath()
    {
        $parser = new \Spider_Parser();
        $html = file_get_contents(__DIR__ . '/data/example.html');
        return $parser->parse($html);
    }
}