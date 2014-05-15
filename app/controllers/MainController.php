<?php

namespace app\controllers;

/**
 * MainController file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class MainController extends \app\components\Controller
{

    /**
     * Index action
     */
    public function onIndex()
    {
        $this->renderPartial('welcome', [
			'content' => \app\models\Example::model()->getContent()
		]);
    }

}