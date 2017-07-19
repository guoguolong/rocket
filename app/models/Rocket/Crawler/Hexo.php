<?php
namespace Rocket\Crawler;

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

    public function atom($options = [])
    {
        if (!is_string($this->siteConf['atom'])) {
            $this->saveSite();
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
            // $siteConf['link'] = $feed->getId();
            $email = null;
            foreach ($feed->getAuthors() as $author) {
                $siteConf['author'] = (string) $author->getName();
                $email = (string) $author->getEmail();
                break;
            }
            if ($email) {
                $siteConf['email'] = $email;
            }
            $siteConf['title'] = (string) $feed->getTitle();
            // $siteConf['link'] = preg_replace('/\/$/', '', (string) $feed->getId());
            $siteConf['subtitle'] = (string) $feed->getSubtitle();
            $siteConf['updated_at'] = $feed->getUpdated();

            $siteObj = $this->saveSite($siteConf);

            if (!empty($options['only_site'])) {
                // 仅存储站点.
                return;
            }
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

            // 更新site文章数
            $siteObj->articles = Article::countBySiteId($siteObj->site_id);
            $siteObj->save();
        }
        return true;
    }

    public function pages($link = null)
    {
        $self = $this;
        $site = Site::findFirstByCode(md5($this->siteConf['baseUrl']));

        if (!$site) {
            echo 'Site' . $this->siteConf['baseUrl'] . ' configuration is not existed.';
            return false;
        }
        $this->siteConf['id'] = $site->site_id;
        $this->siteConf['link'] = $site->link;
        if (!$link) {
            $link = $this->siteConf['baseUrl'];
        }
        $crawler = $this->fetchUrl($link);
        if (!$crawler) {
            return false;
        }
        $this->_pageListParse($crawler, $site);

        echo '.'; // 进度显示.

        // 下一页数据
        $this->_pageNext($crawler, $this->siteConf['link']);

        // 更新site的文章计数
        $site->articles = Article::countBySiteId($this->siteConf['id']);
        $site->save();

        return true;
    }

    protected function _pageNext($crawler, $link)
    {
        $pageNode = $crawler->filter('nav[class="pagination"] a[class="extend next"]');
        try {
            $next_page_link = $pageNode->attr('href');
            if ($next_page_link) {
                $this->pages($link . $next_page_link);
            }
        } catch (\Exception $e) {
            // echo PHP_EOL . '[BAD LINK]' . $next_page_link . ': ' . $e->getMessage() . PHP_EOL;
        }
    }

    protected function _pageListParse(Crawler $crawler)
    {
        $self = $this;
        $crawler
            ->filter('main article')
            ->each(function (Crawler $node, $i) use ($self) {
                // 获得Article概要数据
                $list_data = [];
                $list_data['title'] = trim($node->filter('header h1 a')->text());
                $list_data['link'] = $self->siteConf['link'] . trim($node->filter('header h1 a')->attr('href'));
                $list_data['code'] = md5($list_data['link']);
                $list_data['published_at'] = date('Y-m-d H:i:s', strtotime(trim($node->filter('header time')->attr('datetime'))));
                $list_data['summary'] = trim($node->filter('div[class="post-body"]')->getNode(0)->firstChild->nodeValue);
                $list_data['summary'] = preg_replace('/\n/', '', $list_data['summary']);
                $list_data['summary'] = preg_replace('/(\s*\.\.\.\s*)$/', '', $list_data['summary']);

                // 抓取Article详细数据
                $detail_data = $self->_pageDetailParse($self->fetchUrl($list_data['link']));
                if (empty($detail_data)) {
                    echo '[FAILED]: ', $list_data['link'], PHP_EOL;
                    return false;
                }
                // 合并Article概要和详细数据.
                $article_data = array_merge($list_data, $detail_data);
                $article_data['site_id'] = $self->siteConf['id'];

                // 存储Article数据
                $self->saveArticle($article_data);
            });
    }

    protected function _pageDetailParse(Crawler $crawler)
    {
        $article_data = [];
        $crawler
            ->filter('main article')
            ->each(function (Crawler $node, $i) use (&$article_data) {
                $article_data['title'] = trim($node->filter('header h1')->text());
                $article_data['content'] = trim($node->filter('div[class="post-body"]')->html());
            });
        return $article_data;
    }
}
