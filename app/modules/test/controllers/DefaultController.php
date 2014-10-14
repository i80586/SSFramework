<?php

namespace app\modules\test\controllers;

/**
 * Description of DefaultController
 *
 * @author tux
 */
class DefaultController extends \framework\core\BaseController
{
    
    public function onIndex()
    {
        echo $this->render('index');
    }
    
}
