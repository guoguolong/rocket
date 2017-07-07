<?php

use Rocket\Db\Article;

class IndexController extends ControllerBase
{

    public function indexAction() {}

    public function articlesAction()
    {
        $articles = Article::find();
        $this->view->setVars(['articles' => $articles]);
    }
}
