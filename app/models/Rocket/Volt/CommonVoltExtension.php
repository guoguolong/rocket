<?php
/**
 * Copyright (c) 2017 MirrorOffice.com, All rights reserved.
 * Author: Allen Guo <guojunlong@mirroroffice.com>
 * Create: 2016/06/01
 */
namespace Rocket\Volt;

use Rocket\Volt\BaseVoltExtension;

/**
 * Class Rocket\Volt\CommonVoltExtension
 */
class CommonVoltExtension extends BaseVoltExtension
{
    /**
     * 注册方法
     * @return array
     */
    protected function registerFunctions()
    {
        return [
            'image_base_url' => 'getImageBaseUrl',
        ];
    }

    /**
     * 注册过滤器
     * @return array
     */
    protected function registerFilters()
    {
        return [
            'substr' => 'substrFilter',
            'date_str_format' => 'dateStrFormatFilter',
            'image_urls' => 'generateImageUrlsFilter',
            'first_image_url' => 'generateFirstImageUrlFilter',
            'split_first_image_url' => 'splitFirstImageUrlFromHtmlFilter',
            'digest' => 'digestFilter',
        ];
    }

    public static function getImageBaseUrl()
    {
        $image_service = static::getDI()->get('image.srv');
        return $image_service->getUrl();
    }

    public static function substrFilter($text, $length)
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s/iu', ' ', $text); // 去掉空白
        $text = preg_replace('/&nbsp;/iu', '', $text); // 去掉实体空格
        if (!$length) {
            $length = count($text);
        }

        $text = mb_substr($text, 0, $length, 'utf-8');

        return $text;
    }

    public static function dateStrFormatFilter($date, $format = 'Y-m-d H:i:s', $incr = null)
    {
        if (empty($date)) {
            return null;
        }

        if ($incr) {
            $d = date($format, strtotime($incr, strtotime($date)));
        } else {
            $d = date($format, strtotime($date));
        }
        return $d;
    }

    /**
     * @param $images_filenames
     * @return array
     */
    public static function generateImageUrlsFilter($images_filenames, $thumb = null)
    {
        $urls = [];
        if (empty($images_filenames)) {
            return $urls;
        }

        $filenames = explode(',', $images_filenames);

        $image_service = static::getDI()->get('image.srv');
        foreach ($filenames as $filename) {
            $urls[] = $image_service->getUrl($filename, $thumb);
        }
        return $urls;
    }

    /**
     * @param $images_filenames
     * @return array
     */
    public static function generateFirstImageUrlFilter($images_filenames, $thumb = null)
    {
        $url = null;
        if (empty($images_filenames)) {
            return $url;
        }

        $filenames = explode(',', $images_filenames);

        $image_service = static::getDI()->get('image.srv');
        foreach ($filenames as $filename) {
            $url = $image_service->getUrl($filename, $thumb);
            break;
        }
        return $url;
    }

    public static function digestFilter($content, $length = 200)
    {
        $text = $content;
        if (is_object($content) && get_class($content) === 'Rocket\Db\Article') {
            $text = $content->summary;
            if (!$text) {
                $text = $content->content;
            } else {
                return $text;
            }
        }

        if (is_string($text)) {
            return mb_substr(trim(strip_tags($text)), 0, $length);
        }
    }

    /**
     * @param $html
     * @return string
     */
    public static function splitFirstImageUrlFromHtmlFilter($html)
    {
        $pattern = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/';
        preg_match_all($pattern, $html, $match);
        if (count($match[1]) <= 0) {
            return '';
        }

        return $match[1][0];
    }
}
