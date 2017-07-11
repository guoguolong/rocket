<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->response->redirect('article/list');
    }
}
