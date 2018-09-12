<?php use Peak\DebugBar\View\Helper\ArrayTable; ?>
<h1>Inputs information</h1>
<h3><div class="h-block">GET</div></h3>
<?php echo (new ArrayTable($view->get))->render(); ?>
<h3><div class="h-block">POST</div></h3>
<?php echo (new ArrayTable($view->post))->render(); ?>
<h3><div class="h-block">COOKIE</div></h3>
<?php echo (new ArrayTable($view->cookie))->render(); ?>
<h3><div class="h-block">SERVER</div></h3>
<?php echo (new ArrayTable($view->server))->render(); ?>
<h3><div class="h-block">ENV</div></h3>
<?php echo (new ArrayTable($view->env))->render(); ?>

