<?php

use Rocket\Db\Article;

class ArticleController extends ControllerBase
{
    public function listAction($site_id = null)
    {
        $args = [];
        if ($site_id) {
            $args = [
                'site_id = :site_id:',
                'bind' => [
                    'site_id' => $site_id,
                ],
            ];
        }
        $args['order'] = 'published_at DESC';
        $args['limit'] = 200;

        $articles = Article::find($args);
        $this->view->setVars(['articles' => $articles]);
    }

    public function detailAction($article_id)
    {
        $article = Article::findFirst($article_id);
        $this->view->setVars(['article' => $article]);
    }
}
