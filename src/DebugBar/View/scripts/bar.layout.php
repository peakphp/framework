<style><?php include __DIR__.'/../assets/style.css'; ?></style>
<div id="pkdebugbar" style=""display:none;">
    <div class="pkdebugbar-menu-wrapper">
        <ul class="pkdebugbar-menu">
            <?php
            foreach ($view->tabs as $tab_name => $tab_content) {
                echo '<li class="pkdebugbar-tab" data-target="'.$tab_name.'">'.$tab_content.'</li>';
            }
            ?>
        </ul>
    </div>
    <?php echo $content; ?>
</div>
<script><?php include __DIR__.'/../assets/script.js'; ?></script>
