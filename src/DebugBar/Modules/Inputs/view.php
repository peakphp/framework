<?php
use Peak\DebugBar\View\Helper\ArrayTable;
?>
<div class="pkdebugbar-window pkdebugbar-window-inputs">
    <h1><div class="hblock">Inputs information</div></h1>
    <h3><div class="hblock">GET</div></h3>
    <?php echo (new ArrayTable($this->get))->render(); ?>
    <h3><div class="hblock">POST</div></h3>
    <?php echo (new ArrayTable($this->post))->render(); ?>
    <h3><div class="hblock">COOKIE</div></h3>
    <?php echo (new ArrayTable($this->cookie))->render(); ?>
    <h3><div class="hblock">SERVER</div></h3>
    <?php echo (new ArrayTable($this->server))->render(); ?>
    <h3><div class="hblock">ENV</div></h3>
    <?php echo (new ArrayTable($this->env))->render(); ?>
</div>
