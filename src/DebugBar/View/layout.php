<div id="pkdebugbar">
    <div class="pkdebugbar-menu-wrapper">
        <ul class="pkdebugbar-menu">
            <?php
            foreach ($tabs as $tab_name => $tab_content) {
                echo '<li class="pkdebugbar-tab" data-target="'.$tab_name.'">'.$tab_content.'</li>';
            }
            ?>
        </ul>
    </div>
    <?php echo $content; ?>
</div>
<style><?php include __DIR__.'/style.css'; ?></style>
<script><?php include __DIR__.'/script.js'; ?></script>
