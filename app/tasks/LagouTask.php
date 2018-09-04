<?php
use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\DomCrawler\Crawler;

class LagouTask extends MainTask {
    protected $headers = [
        'Accept' => 'application/json, text/javascript, */*; q=0.01',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Accept-Language' => 'zh-CN,zh;q=0.9,en;q=0.8,zh-TW;q=0.7',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
        'Content-Length' => '23',
        'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Cookie' => 'JSESSIONID=ABAAABAAAFCAAEG69F61582EF0D90527A2BDD5DD5A023FE; _ga=GA1.2.1944852986.1536041628; _gid=GA1.2.385569405.1536041628; Hm_lvt_4233e74dff0ae5bd0a3d81c6ccf756e6=1536041628; user_trace_token=20180904141348-afcfcd83-b009-11e8-85a3-525400f775ce; LGUID=20180904141348-afcfd0d6-b009-11e8-85a3-525400f775ce; index_location_city=%E5%85%A8%E5%9B%BD; TG-TRACK-CODE=search_code; X_HTTP_TOKEN=f7b564ddf83be799bd4f96bf7efd0dcb; Hm_lpvt_4233e74dff0ae5bd0a3d81c6ccf756e6=1536046685; LGRID=20180904153805-761ac488-b015-11e8-85a9-525400f775ce; SEARCH_ID=17c2a6d451cb49868657766b13274c66',
        'Host' => 'www.lagou.com',
        'Origin' => 'https://www.lagou.com',
        'Pragma' => 'no-cache',
        'Referer' => 'https://www.lagou.com/jobs/list_NODE?city=南京&cl=false&fromSearch=true&labelWords=&suginput=',
        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36',
        'X-Anit-Forge-Code' => '0',
        'X-Anit-Forge-Token' => 'None',
        'X-Requested-With' => 'XMLHttpRequest'
    ];
    protected $timeout = 60;

    public static function encodeUrl($link)
    {
        $url_segs = explode('/', $link);
        $name = array_pop($url_segs);
        if (!$name) {
            $name = rawurlencode(array_pop($url_segs));
        }
        array_push($url_segs, $name);
        $regular_link = implode('/', $url_segs);
        if (preg_match('/\/$/', $link)) {
            $regular_link .= '/';
        }
        return $regular_link;
    }

    public function ajaxAction($params) {
        $client = new GuzzleClient([
            'timeout' => $this->timeout,
            'headers' => $this->headers
        ]);

        $response = $client->request('POST', 'https://www.lagou.com/jobs/positionAjax.json?city=南京&needAddtionalResult=false', [
            'form_params' => [
                'first'=> true,
                'pn'   => 1,
                'kd'   => 'NODE'
            ]
        ]);
        print_r($response);
    }

    public function runAction($params) 
    {
        $link = 'https://www.lagou.com/jobs/list_NODE?city=%E5%8D%97%E4%BA%AC&cl=false&fromSearch=true&labelWords=&suginput=';
        $client = new GoutteClient();
        $client->setClient(new GuzzleClient([
            'timeout' => $this->timeout,
            'headers' => $this->headers
        ]));
        $link = static::encodeUrl($link);
        $crawler = $client->request('GET', $link);

        $this->parse($crawler);
        return $crawler;        
    }

    public function parse($crawler) {
        $self = $this;
        $nodes = $crawler
            ->filter('ul[class="item_con_list"] li[class="con_list_item"]');
            print_r($nodes);
            // ->each(function (Crawler $node, $i) use ($self) {
            //     // $list_data = [];
            //     $title = $node->filter(' h3')->text();
            //     echo $title . PHP_EOL;
            //     // $list_data['title'] = trim($node_title->text());
            //     // $list_data['link'] = $this->siteConf['link'] . trim($node_title->attr('href'));
            //     // $list_data['code'] = md5($list_data['link']);
            //     // $list_data['published_at'] = date('Y-m-d H:i:s', strtotime(trim($node->filter('div[class="post-meta"]')->text())));
            //     // $list_data['summary'] = trim(strip_tags($node->filter('div[class="post-content"]')->text()));
            //     // $list_data['summary'] = preg_replace('/\n/', '', $list_data['summary']);
            //     // $list_data['summary'] = preg_replace('/(\s*\.\.\.\s*)$/', '', $list_data['summary']);
            // });
    }
};
