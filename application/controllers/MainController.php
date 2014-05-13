<?php

namespace application\controllers;

/**
 * MainController file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class MainController extends \application\components\Controller
{

    /**
     * Index action
     */
    public function onIndex()
    {
        $this->renderPartial('welcome', [
			'content' => \application\models\Post::model()->getContent()
		]);
    }

}