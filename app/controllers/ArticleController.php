<?php

use Rocket\Db\Article;

class ArticleController extends ControllerBase
{
    public function listAction($site_id)
    {
        $args = [];
        if ($site_id) {
            $args['conditions'] = ['site_id' => $site_id];
        }
        $args['sort'] = ['published_at' => 1];
        // echo '<pre>';
        // print_r($args);exit;
        $articles = Article::find($args);
        $this->view->setVars(['articles' => $articles]);
    }

    public function detailAction($article_id)
    {
        $article = Article::findFirst($article_id);
        $this->view->setVars(['article' => $article]);
    }
}
