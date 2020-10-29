<ul>
    <?php foreach ($shows as $show): ?>
        <li>
            <?= $show->sid . " | " . $show->name ?>
        </li>
    <?php endforeach; ?>
</ul>
