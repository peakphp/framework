<style><?php include __DIR__.'/../assets/style.css'; ?></style>
<div id="pkdebugbar" style=""display:none;">
    <div class="pkdebugbar-menu-wrapper">
        <ul class="pkdebugbar-menu">
            <?php
            foreach ($view->tabs as $tab_name => $tab_content) {
                echo '<li class="pkdebugbar-tab" data-target="'.$tab_name.'">'.$tab_content.'</li>';
            }
            ?>
            <li class="pkdebugbar-toggle">
                <span class="toggle-open">&rtrif;</span><span class="toggle-close">&ltrif;</span>
            </li>
        </ul>
    </div>
</div>
<div id="pkdebugbar-windows">
    <?php echo $content; ?>
</div>
<script><?php include __DIR__.'/../assets/script.js'; ?></script>
