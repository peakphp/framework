<?php use Peak\DebugBar\View\Helper\ArrayTable; ?>
<h1><div class="hblock">Inputs information</div></h1>
<h3><div class="hblock">GET</div></h3>
<?php echo (new ArrayTable($view->get))->render(); ?>
<h3><div class="hblock">POST</div></h3>
<?php echo (new ArrayTable($view->post))->render(); ?>
<h3><div class="hblock">COOKIE</div></h3>
<?php echo (new ArrayTable($view->cookie))->render(); ?>
<h3><div class="hblock">SERVER</div></h3>
<?php echo (new ArrayTable($view->server))->render(); ?>
<h3><div class="hblock">ENV</div></h3>
<?php echo (new ArrayTable($view->env))->render(); ?>

