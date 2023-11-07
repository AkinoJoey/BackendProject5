<?php
function generatePaginationLinks(int $currentPage, int $totalItems, int $perpage, int $paginationRange, string $type){
    $totalPages = ceil($totalItems / $perpage);

    $paginationRange = 5;
    $halfPaginationRange = floor($paginationRange / 2);

    if ($totalPages > $paginationRange) {
        $startPage = max(1, min($currentPage - $halfPaginationRange, $totalPages - $paginationRange + 1));
        $endPage = min($startPage + $paginationRange - 1, $totalPages);
    } else {
        $startPage = 1;
        $endPage = $totalPages;
    }


    for($i = $startPage; $i <= $endPage; $i++){
        if ($i == $currentPage) {
            echo '<li class="page-item active"><a class="page-link" href="?page=' . $i . '">' . $i .  '</li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="?type='. $type .'&page='. $i .'&perpage='.  $perpage .'">' . $i . '</a></li>';
        }
    }
}
