<div class="d-flex flex-wrap">
    <?php foreach ($parts as $part) : ?>
        <div class="card mt-4 mx-3" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($part['name']) ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($part['type']) ?> - <?= htmlspecialchars($part['brand']) ?></h6>
                <p class="card-text">
                    <strong>Model:</strong> <?= htmlspecialchars($part['model_number']) ?><br />
                    <strong>Release Date:</strong> <?= htmlspecialchars($part['release_date']) ?><br />
                    <strong>Description:</strong> <?= htmlspecialchars($part['description']) ?><br />
                    <strong>Performance Score:</strong> <?= htmlspecialchars($part['performance_score']) ?><br />
                    <strong>Market Price:</strong> $<?= htmlspecialchars($part['market_price']) ?><br />
                    <strong>RSM:</strong> $<?= htmlspecialchars($part['rsm']) ?><br />
                    <strong>Power Consumption:</strong> <?= htmlspecialchars($part['power_consumptionw']) ?>W<br />
                    <strong>Dimensions:</strong> <?= htmlspecialchars($part['lengthm']) ?>m x <?= htmlspecialchars($part['widthm']) ?>m x <?= htmlspecialchars($part['heightm']) ?>m<br />
                    <strong>Lifespan:</strong> <?= htmlspecialchars($part['lifespan']) ?> years<br />
                </p>
                <p class="card-text"><small class="text-muted">Last updated on <?= htmlspecialchars($part['updated_at']) ?></small></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="mt-5 d-flex justify-content-center">
    <nav>
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php include 'pagination.php' ?>
            <?php generatePaginationLinks($page, $totalParts, $perpage, 5, $type) ?>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>