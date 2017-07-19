<?php
namespace Rocket\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class HexoCho extends Hexo
{
    protected function _pageNext($crawler, $link)
    {
        $pageNode = $crawler->filter('nav a[class="extend next"]');
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

    protected function _pageListParse(Crawler $crawler)
    {
        $self = $this;
        $crawler
            ->filter('div[class="post"]')
            ->each(function (Crawler $node, $i) use ($self) {
                // 获得Article概要数据
                $list_data = [];
                $node_title = $node->filter('h1[class="post-title"] a');
                $list_data['title'] = trim($node_title->text());
                $list_data['link'] = $this->siteConf['link'] . trim($node_title->attr('href'));
                $list_data['code'] = md5($list_data['link']);
                $list_data['published_at'] = date('Y-m-d H:i:s', strtotime(trim($node->filter('div[class="post-meta"]')->text())));
                $list_data['summary'] = trim(strip_tags($node->filter('div[class="post-content"]')->text()));
                $list_data['summary'] = preg_replace('/\n/', '', $list_data['summary']);
                $list_data['summary'] = preg_replace('/(\s*\.\.\.\s*)$/', '', $list_data['summary']);
                $list_data['summary'] = trim(mb_substr($list_data['summary'], 0, 200));

                // 抓取Article详细数据
                $detail_data = $self->_pageDetailParse($self->fetchUrl($list_data['link']));
                if (empty($detail_data)) {
                    echo '[FAILED - Detail page]: ', $list_data['link'], PHP_EOL;
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
            ->filter('div[class="post"]')
            ->each(function (Crawler $node, $i) use (&$article_data) {
                $article_data['title'] = trim($node->filter('h1')->text());
                $article_data['content'] = trim($node->filter('div[class="post-content"]')->html());
            });
        return $article_data;
    }
}
