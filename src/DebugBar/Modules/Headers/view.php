<?php use Peak\DebugBar\View\Helper\ArrayTable; ?>
<h1>Response Headers</h1>
<?php
    echo (new ArrayTable($view->headers))
        ->setFirstColumnClass('width-200')
        ->render();
    ?>

