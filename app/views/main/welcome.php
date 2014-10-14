<?php
/* example view file */

$this->pageTitle = framework\core\App::$get->name();
?>

<title><?= $this->pageTitle ?></title>
<h3><?= $content ?></h3>