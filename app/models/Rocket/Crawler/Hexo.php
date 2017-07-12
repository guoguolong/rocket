<?php
namespace Rocket\Crawler;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use Mekras\Atom\DocumentFactory;
use Mekras\Atom\Document\FeedDocument;
use Mekras\Atom\Exception\AtomException;
use Rocket\Crawler\Blog;
use Rocket\Db\Article;
use Rocket\Db\Site;
use Symfony\Component\DomCrawler\Crawler;

class Hexo extends Blog
{
    public function __construct($siteConf)
    {
        parent::__construct($siteConf);
        if (true === $this->siteConf['atom']) {
            $this->siteConf['atom'] = $this->siteConf['baseUrl'] . '/atom.xml';
        }
    }

    public function atom()
    {
        if (!is_string($this->siteConf['atom'])) {
            return false;
        }

        $siteConf = $this->siteConf;

        $xml = file_get_contents($siteConf['atom']);

        $factory = new DocumentFactory();

        try {
            $document = $factory->parseXML($xml);
        } catch (AtomException $e) {
            echo $e->getMessage();
            return false;
        }

        if ($document instanceof FeedDocument) {
            $feed = $document->getFeed();
            $entries = $feed->getEntries();
            $siteConf['articles'] = count($entries);
            $siteConf['link'] = $feed->getId();
            foreach ($feed->getAuthors() as $author) {
                $siteConf['author'] = (string) $author->getName();
                $siteConf['email'] = (string) $author->getEmail();
                break;
            }
            $siteConf['title'] = (string) $feed->getTitle();
            $siteConf['link'] = (string) $feed->getId();
            $siteConf['subtitle'] = (string) $feed->getSubtitle();
            $siteConf['updated_at'] = $feed->getUpdated();

            $siteObj = $this->saveSite($siteConf);
            foreach ($entries as $entry) {
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

                $article->title = (string) $entry->getTitle();
                $article->content = (string) $entry->getContent();
                $article->site_id = $siteObj->site_id;
                $article->published_at = $published_at;
                $article->updated_at = $updated_at;
                $article->link = $link;
                $article->code = $code;
                $article->save();
            }
        }
        return true;
    }

    public function page()
    {
        $self = $this;
        $siteConf = $this->siteConf;
        $site = Site::findFirstByCode(md5($siteConf['baseUrl']));

        if (!$site) {
            echo 'Site' . $siteConf['baseUrl'] . ' configuration is not existed.';
            return false;
        }
        $client = new GoutteClient();
        $client->setClient(new GuzzleClient([
            'timeout' => $this->timeout,
            'headers' => $this->headers,
        ]));
        $crawler = $client->request('GET', $siteConf['baseUrl']);
        $crawler = $crawler
            ->filter('main section article')
            ->each(function (Crawler $node, $i) use ($site) {
                $site_data = static::_parse($node, $site);

                $article = null;
                $existedArticle = Article::findFirstByCode($site_data['code']);
                if ($existedArticle) {
                    return;
                }

                $article = new Article;
                $article->published_at = $site_data['published_at'];
                $article->updated_at = $site_data['published_at'];
                $article->summary = $site_data['summary'];
                $article->link = $site_data['link'];
                $article->code = $site_data['code'];
                $article->site_id = $site->site_id;
                $article->title = $site_data['title'];

                $article->save();
            });
        exit;
        return true;
    }

    protected static function _parse(Crawler $node, $site)
    {
        $site_data = [];
        $site_data['title'] = trim($node->filter('header h1 a')->text());
        $site_data['link'] = $site->link . trim($node->filter('header h1 a')->attr('href'));
        $site_data['code'] = md5($site_data['link']);
        $site_data['published_at'] = date('Y-m-d H:i:s', strtotime(trim($node->filter('header time')->attr('datetime'))));
        $site_data['summary'] = trim($node->filter('div[class="post-body"]')->getNode(0)->firstChild->nodeValue);
        $site_data['summary'] = preg_replace('/\n/', '', $site_data['summary']);
        $site_data['summary'] = preg_replace('/(\s*\.\.\.\s*)$/', '', $site_data['summary']);
        return $site_data;
    }

    protected function _setHeaders($headers)
    {
        $this->headers = $headers;
    }
}
