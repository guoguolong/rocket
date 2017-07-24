<?php
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
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
        // $args['limit'] = 10;

        $current_page = 1;
        // $total = Article::count($args);
        $total = 1089;
        $paginator = new PaginatorModel(
            [
                "data" => Article::find($args),
                "limit" => 10,
                "page" => $current_page,
            ]
        );

        // Get the paginated results
        $page = $paginator->getPaginate();
        $this->view->setVars(['page' => $page, 'total' => $total]);
    }

    public function detailAction($article_id)
    {
        $article = Article::findFirst($article_id);
        if (!$article) {
            $this->response->setStatusCode(404, 'Not Found');
            $this->response->redirect('error/show404');
        }
        $this->view->setVars(['article' => $article]);
    }
}
