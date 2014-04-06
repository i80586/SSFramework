<?php

use SS\Application;

/**
 * MainController file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class MainController extends Controller
{

    /**
     * Index action
     */
    public function onIndex()
    {
        $this->renderPartial('wellcome');
    }

}