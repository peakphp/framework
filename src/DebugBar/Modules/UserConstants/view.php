<?php use Peak\DebugBar\View\Helper\ArrayTable; ?>
<h1>User Constants</h1>
<strong>
    <?php echo count($view->constants); ?> defined constant(s)<br />
</strong>
<?php echo (new ArrayTable($view->constants))->render(); ?>