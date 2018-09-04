<?php

use Rocket\Db\Site;

class SiteController extends ControllerBase
{
    public function listAction()
    {
        $sites = Site::find([
            'order' => 'updated_at DESC']);
        $this->view->setVars(['sites' => $sites]);
    }

    public function detailAction($id)
    {
        $site = Site::findFirst($id);
        $this->view->setVars(['site' => $site]);
    }
}
