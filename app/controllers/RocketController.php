<?php
class RocketController extends ControllerBase
{
    public function indexAction()
    {
        $this->response->setHeader("Content-Type", "text/javascript");
    }
}
