<h1>Execution time</h1>

<h3><div class="h-block">Current Request</div></h3>
<span class="strong-block"><?php echo $view->stats['current_request']['time']; ?></span>
<span class="strong-block"><?php echo $view->stats['current_request']['uri']; ?></span>

<?php if (!empty($view->stats['last_request']['uri'])) : ?>
    <h3><div class="h-block">Last request</div></h3>
    <span class="strong-block"><?php echo $view->stats['last_request']['time']; ?></span>
    <span class="strong-block"><?php echo $view->stats['last_request']['uri']; ?></span>
<?php endif; ?>

<h3><div class="h-block">Average request time</div></h3>
<span class="strong-block"><?php echo $view->stats['average_request']; ?></span>

<?php if (!empty($view->stats['shortest_request'])) : ?>
    <h3><div class="h-block">Shortest request time</div></h3>
    <span class="strong-block"><?php echo $view->stats['shortest_request']['time']; ?></span>
    <span class="strong-block"><?php echo $view->stats['shortest_request']['uri']; ?></span>
<?php endif; ?>

<?php if (!empty($view->stats['longest_request'])) : ?>
    <h3><div class="h-block">Longest request time</div></h3>
    <span class="strong-block"><?php echo $view->stats['longest_request']['time']; ?></span>
    <span class="strong-block"><?php echo $view->stats['longest_request']['uri']; ?></span>
<?php endif; ?>

<h3><div class="h-block">Stats</div></h3>
<table style="table-border-line">
    <thead>
    <tr>
        <th>URI</th>
        <th>Average</th>
        <th>Nb. request</th>
    </tr>
    </thead>
    <tbody>
    <?php
        foreach ($view->stats['requests_avg'] as $uri => $stats) {
            echo '<tr>
                    <td class="width-1"><strong>'.$uri.'</strong></td>
                    <td class="">'.$stats['average'].'</td>
                    <td class="width-1">'.$stats['count'].'</td>
                  </tr>';
        }
    ?>
    </tbody>
</table>
