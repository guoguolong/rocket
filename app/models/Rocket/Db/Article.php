<?php

namespace Rocket\Db;

use Phalcon\Mvc\Model;

class Article extends Model
{

    public function initialize()
    {
        $this->belongsTo(
            'site_id',
            'Rocket\Db\Site',
            'site_id',
            [
                'alias' => 'site',
            ]
        );
    }
}
