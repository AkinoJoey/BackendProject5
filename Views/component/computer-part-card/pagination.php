<?php
function generatePagination(int $currentPage, int $totalPages, int $perpage, string $queryFirstParam)
{
    ob_start();
?>
    <nav>
        <ul class="pagination">
            <?php if ($currentPage > 1) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $queryFirstParam . 'page=' . ($currentPage - 1) . '&perpage=' . $perpage; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) : ?>
                <li class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo ($i !== $currentPage) ? $queryFirstParam . 'page=' . $i . '&perpage=' . $perpage : '#'; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $queryFirstParam . 'page=' . ($currentPage + 1) . '&perpage=' . $perpage; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php
    return ob_get_clean();
}
?>