<?php use Peak\DebugBar\View\Helper\ArrayTable; ?>
<h1>Session</h1>
<?php echo (new ArrayTable($view->infos))->render(); ?>
<pre class="pre-block"><?php print_r($view->session); ?></pre>

