<?php
namespace Rocket\Crawler;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use Rocket\Db\Article;
use Rocket\Db\Site;
use Symfony\Component\DomCrawler\Crawler;

class HexoApollo extends Hexo
{
    public function pages($link = null)
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
        if (!$link) {
            $link = $siteConf['baseUrl'];
        }

        echo '.'; // 进度显示.
        $rootCrawler = $client->request('GET', $link);
        $crawler = $rootCrawler
            ->filter('main article')
            ->each(function (Crawler $node, $i) use ($site, $self) {
                // 获得Article数据
                $article_data = static::_pagesParse($node, $site);
                // 抓取Article详细数据;
                $detail_data = $self->_pageFetchDetail($article_data['link']);
                if (empty($detail_data)) {
                    echo '[FAILED]: ', $article_data['link'], PHP_EOL;
                    return false;
                }

                $article_data = array_merge($article_data, $detail_data);
                $article_data['site_id'] = $site->site_id;

                $article = null;
                $existedArticle = Article::findFirstByCode($article_data['code']);
                if ($existedArticle) {
                    $article = $existedArticle;
                    echo 'udapted..';
                } else {
                    $article = new Article;
                }
                $article->published_at = $article_data['published_at'];
                $article->updated_at = $article_data['published_at'];
                $article->summary = $article_data['summary'];
                $article->content = $article_data['content'];
                $article->link = $article_data['link'];
                $article->code = $article_data['code'];
                $article->site_id = $article_data['site_id'];
                $article->title = $article_data['title'];

                $article->save();
            });

        // 下一页数据
        $this->_pageNext($rootCrawler, $site->link);

        // 更新site的文章计数
        $articles = Article::countBySiteId($site->site_id);
        $site->articles = $articles;
        $site->save();
        exit;
        return true;
    }

    protected function _pageNext($crawler, $link)
    {
        $pageNode = $crawler->filter('footer a[class="next"]');
        $next_page_link = null;
        try {
            $next_page_link = $pageNode->attr('href');
            if ($next_page_link) {
                $this->pages($link . $next_page_link);
            }
        } catch (\Exception $e) {
            // echo PHP_EOL . '[BAD LINK]' . $next_page_link . ': ' . $e->getMessage() . PHP_EOL;
        }
    }

    protected function _pageFetchDetail($link)
    {
        $client = new GoutteClient();
        $client->setClient(new GuzzleClient([
            'timeout' => $this->timeout,
            'headers' => $this->headers,
        ]));
        $olink = $link;
        $link = static::encodeUrl($link);
        $crawler = $client->request('GET', $link);
        $article_data = [];
        $crawler = $crawler
            ->filter('main article')
            ->each(function (Crawler $node, $i) use (&$article_data) {
                $article_data['title'] = trim($node->filter('h1')->text());
                $article_data['content'] = trim($node->filter('div[class="post-content"]')->html());
            });
        return $article_data;
    }

    protected static function _pagesParse(Crawler $node, $site)
    {
        $article_data = [];
        $article_data['title'] = trim($node->filter('h2 a')->text());
        $article_data['link'] = $site->link . trim($node->filter('h2 a')->attr('href'));
        $article_data['code'] = md5($article_data['link']);
        $article_data['published_at'] = date('Y-m-d H:i:s', strtotime(trim($node->filter('div[class="post-info"]')->text())));
        $article_data['summary'] = trim(strip_tags($node->filter('div[class="post-content"]')->text()));
        $article_data['summary'] = preg_replace('/\n/', '', $article_data['summary']);
        $article_data['summary'] = preg_replace('/(\s*\.\.\.\s*)$/', '', $article_data['summary']);
        $article_data['summary'] = trim(mb_substr($article_data['summary'], 0, 200));
        return $article_data;
    }
};
