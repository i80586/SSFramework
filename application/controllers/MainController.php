<?php

namespace SS\application\controllers;

use SS\framework\core\Application;

/**
 * MainController file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class MainController extends \SS\application\components\Controller
{

    /**
     * Index action
     */
    public function onIndex()
    {
        $this->renderPartial('wellcome');
    }

}