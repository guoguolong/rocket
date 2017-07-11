<?php

namespace Rocket\Db;

use Phalcon\Mvc\Model;

class Site extends Model
{
    public function initialize()
    {
        $this->hasMany(
            'article_id',
            'Rocket\Db\Article',
            'article_id',
            ['alias' => 'articles']
        );
    }
}
