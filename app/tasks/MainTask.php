<?php

use Mekras\Atom\DocumentFactory;
use Mekras\Atom\Document\FeedDocument;
use Mekras\Atom\Exception\AtomException;
use Rocket\Db\Article;
use Vinelab\Rss\Rss;

class MainTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        echo "Congratulations! You are now flying with Phalcon CLI!";
    }

    public function feedAction()
    {

        $rss = new Rss();
        $feed = $rss->feed('http://codepub.cn/atom.xml');
        $count = $feed->articlesCount(); // 10
        echo 'Count:::'+$count;
    }

    public function atomAction()
    {
        $xml = file_get_contents('http://codepub.cn/atom.xml');

        $factory = new DocumentFactory();

        try {
            $document = $factory->parseXML($xml);
        } catch (AtomException $e) {
            die($e->getMessage());
        }

        if ($document instanceof FeedDocument) {
            $feed = $document->getFeed();
            echo 'Feed: ', $feed->getTitle(), PHP_EOL;
            echo 'Updated: ', $feed->getUpdated(), PHP_EOL;
            // echo 'Updated: ', $feed->getUpdated()->format('d.m.Y H:i:s'), PHP_EOL;
            foreach ($feed->getAuthors() as $author) {
                echo 'Author: ', $author->getName(), PHP_EOL;
            }
            foreach ($feed->getEntries() as $entry) {
                // echo '  Title: ', $entry->getTitle(), PHP_EOL;
                // echo '  Link: ', implode(',', $entry->getLinks()), PHP_EOL;
                // echo '  Id: ', $entry->getId(), PHP_EOL;
                // echo '  Authors: ', implode(',', $entry->getAuthors()), PHP_EOL;
                // echo '  Published: ', $entry->getPublished(), PHP_EOL;
                // echo '  Updated: ', $entry->getUpdated(), PHP_EOL;
                // echo '  Summary: ', $entry->getSummary(), PHP_EOL;
                // echo '  Categories: ', implode(',', $entry->getCategories()), PHP_EOL;
                // echo PHP_EOL;
                $article = new Article;
                $article->title = (string) $entry->getTitle();
                $article->content = (string) $entry->getContent();
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
}
