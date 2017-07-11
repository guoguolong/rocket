<?php
use Mekras\Atom\DocumentFactory;
use Mekras\Atom\Document\FeedDocument;
use Mekras\Atom\Exception\AtomException;
use Rocket\Db\Article;
use Rocket\Db\Site;

class MainTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        echo "Congratulations! You are now flying with Phalcon CLI!";
    }

    public function atomAction($params)
    {
        $reset = false;
        if (!empty($params[0])) {
            $reset = true;
        }
        if ($reset) {
            $this->clearData();
        }
        $sites = $this->config->crawler->sites;
        foreach ($sites as $url => $site) {
            $site['domain'] = $url;
            if ('hexo' == strtolower($site['power'])) {
                if (true === $site['atom']) {
                    $site['atom'] = $url . '/atom.xml';
                }
                if (is_string($site['atom'])) {
                    $this->_atom($site);
                }
            }
        }
    }

    private function clearData()
    {
        $this->modelsManager->executeQuery('DELETE FROM \Rocket\Db\Article');
        $this->modelsManager->executeQuery('DELETE FROM \Rocket\Db\Site');
    }

    private function saveSite($feed)
    {
        $link = $feed->getId();
        $code = md5($link);
        $existed_site = Site::findFirstByCode($code);
        $site = null;
        if ($existed_site) {
            $site = $existed_site;
        } else {
            $site = new Site();
        }
        foreach ($feed->getAuthors() as $author) {
            $site->author = (string) $author->getName();
            $site->email = (string) $author->getEmail();
            break;
        }
        $site->title = (string) $feed->getTitle();
        $site->link = (string) $feed->getId();
        $site->subtitle = (string) $feed->getSubtitle();
        $site->updated_at = date('Y-m-d H:i:s', strtotime($feed->getUpdated()));
        $site->save();

        return $site;
    }

    private function _atom($siteConf)
    {
        $xml = file_get_contents($siteConf['atom']);

        $factory = new DocumentFactory();

        try {
            $document = $factory->parseXML($xml);
        } catch (AtomException $e) {
            die($e->getMessage());
        }

        if ($document instanceof FeedDocument) {
            $feed = $document->getFeed();
            $siteObj = $this->saveSite($feed);
            // echo 'Feed: ', $feed->getTitle(), PHP_EOL;
            // echo 'Updated: ', $feed->getUpdated(), PHP_EOL;
            // foreach ($feed->getAuthors() as $author) {
            //     echo 'Author: ', $author->getName(), PHP_EOL;
            // }
            foreach ($feed->getEntries() as $entry) {
                $link = implode(',', $entry->getLinks());
                $code = md5($entry->getId());
                $published_at = date('Y-m-d H:i:s', strtotime($entry->getPublished()));
                $updated_at = date('Y-m-d H:i:s', strtotime($entry->getUpdated()));

                $article = null;
                $existedArticle = Article::findFirstByCode($code);
                if ($existedArticle) {
                    if ($updated_at === $existedArticle->updated_at) {
                        continue;
                    }
                    $article = $existedArticle;
                } else {
                    $article = new Article;
                }

                // echo '  Authors: ', implode(',', $entry->getAuthors()), PHP_EOL;
                // echo '  Summary: ', $entry->getSummary(), PHP_EOL;
                // echo '  Categories: ', implode(',', $entry->getCategories()), PHP_EOL;
                $article->title = (string) $entry->getTitle();
                $article->content = (string) $entry->getContent();
                $article->site_id = $siteObj->site_id;
                $article->published_at = $published_at;
                $article->updated_at = $updated_at;
                $article->link = $link;
                $article->code = $code;
                $article->save();
                // break;
                // echo PHP_EOL, (string) $entry->getAuthor(), PHP_EOL;
                // if ($entry->getLinks()) {
                //     echo '  URL: ', implode(',', $entry->getLinks()), PHP_EOL;
                // } else {
                //     // echo PHP_EOL, (string) $entry->getContent(), PHP_EOL;
                // }
            }
        }
    }
};
