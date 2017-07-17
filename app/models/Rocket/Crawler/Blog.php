<?php
namespace Rocket\Crawler;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use Rocket\Db\Article;
use Rocket\Db\Site;

class Blog
{
    protected static $BLOG_CLASSES = [
        'hexo' => '\Rocket\Crawler\Hexo',
        'wordpress' => '\Rocket\Crawler\WordPress',
        'www.domyself.me' => '\Rocket\Crawler\DomyselfMe',
    ];
    protected $siteConf = null;
    protected $headers = [
        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:47.0) Gecko/20100101 Firefox/47.0',
        'Accept' => '"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"',
        'Accept-Language' => 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding' => "gzip, deflate",
        'Connection' => 'keep-alive',
        'Cookie' => 'splash=1',
        'Cache-Control' => 'max-age=0',
        'Upgrade-Insecure-Requests' => 1,
    ];
    protected $timeout = 60;

    public function __construct($siteConf)
    {
        $siteConf['baseUrl'] = preg_replace('/\/$/', '', $siteConf['baseUrl']);
        $siteConf['domain'] = preg_replace('/^https*:\/\//', '', $siteConf['baseUrl']);
        $this->siteConf = $siteConf;
    }

    public static function getInstance($siteConf)
    {
        $class_name = $siteConf['class'];
        if (!$class_name) {
            $class_name = static::$BLOG_CLASSES[strtolower($siteConf['power'])];
        }
        if (!$class_name) {
            throw new Exception('Not existed engine for' . $class_name);
        }
        return new $class_name($siteConf);
    }

    public function getSiteConf()
    {
        return $this->siteConf;
    }

    public static function encodeUrl($link)
    {
        $url_segs = explode('/', $link);
        $name = array_pop($url_segs);
        if (!$name) {
            $name = rawurlencode(array_pop($url_segs));
        }
        array_push($url_segs, $name);
        $link = implode('/', $url_segs) . '/';
        return $link;
    }

    public function saveSite($siteConf)
    {
        if (!$siteConf) {
            $siteConf = $this->siteConf;
        }

        $code = md5($siteConf['baseUrl']);
        $existed_site = Site::findFirstByCode($code);
        $site = null;
        if ($existed_site) {
            $site = $existed_site;
        } else {
            $site = new Site();
        }
        $site->author = $siteConf['author'];
        $site->email = $siteConf['email'];

        $site->title = $siteConf['title'];
        $site->link = $siteConf['link'];
        $site->subtitle = $siteConf['subtitle'];
        $site->power = $siteConf['power'];
        $site->code = $code;
        $site->articles = $siteConf['articles'];
        $site->updated_at = date('Y-m-d H:i:s', strtotime($siteConf['updated_at']));

        $site->save();

        return $site;
    }

    protected function saveArticle($article_data)
    {
        $article = null;
        $existedArticle = Article::findFirstByCode($article_data['code']);
        if ($existedArticle) {
            $article = $existedArticle;
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
        return $article;
    }

    protected function fetchUrl($link)
    {
        $client = new GoutteClient();
        $client->setClient(new GuzzleClient([
            'timeout' => $this->timeout,
            'headers' => $this->headers,
        ]));
        $link = static::encodeUrl($link);
        return $client->request('GET', $link);
    }

    protected function _pagesHeaders($headers)
    {
        $this->headers = $headers;
    }
}
