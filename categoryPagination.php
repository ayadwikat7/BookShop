<?php
global $totalPages, $page, $cat_id;

if ($totalPages > 1): ?>
    <div class="pagination-container">

        <?php if ($page > 1): ?>
            <a class="page-btn prev"
               href="?id=<?= $cat_id ?>&page=<?= $page - 1 ?>">
                « Previous Page
            </a>
        <?php endif; ?>

        <span class="page-number">
            Page <strong><?= $page ?></strong> of <?= $totalPages ?>
        </span>

        <?php if ($page < $totalPages): ?>
            <a class="page-btn next"
               href="?id=<?= $cat_id ?>&page=<?= $page + 1 ?>">
                Next Page »
            </a>
        <?php endif; ?>

    </div>
<?php endif; ?>
