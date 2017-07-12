<?php
namespace Rocket\Crawler;

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
        $class_name = static::$BLOG_CLASSES[strtolower($siteConf['power'])];
        if (!$class_name) {
            throw new Exception('Not existed engine for' . $class_name);
        }
        return new $class_name($siteConf);
    }

    public function getSiteConf()
    {
        return $this->siteConf;
    }

    public function saveSite()
    {
        $siteConf = $this->siteConf;

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
}
