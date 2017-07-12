<?php

use Rocket\Crawler\Blog;
use Symfony\Component\DomCrawler\Crawler;

class SpiderTask extends MainTask
{
    public function atomAction($params)
    {
        $this->parse($params, 'atom');
    }

    public function pageAction($params)
    {
        $this->parse($params, 'page');
    }

    protected function parse($params, $type = 'atom')
    {
        $reset = false;
        if (!empty($params[0])) {
            $reset = true;
        }
        if ($reset) {
            $this->clearData($type);
        }
        $sites = $this->config->crawler->sites;
        foreach ($sites as $url => $site) {
            $site['baseUrl'] = $url;
            $blog = Blog::getInstance($site);
            if ('atom' === $type) {
                echo $blog->getSiteConf()['atom'] . ' fetching...';
                $blog->atom();
            }
            if ('page' === $type) {
                echo $blog->getSiteConf()['baseUrl'] . ' fetching...';
                $blog->page();
            }
            echo 'Done' . PHP_EOL;
        }
    }

    protected function clearData($type)
    {
        $this->modelsManager->executeQuery('DELETE FROM \Rocket\Db\Article');
        if ('atom' === $type) {
            $this->modelsManager->executeQuery('DELETE FROM \Rocket\Db\Site');
        }
    }
};
