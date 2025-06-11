<h1 class="mb-4"><?= $data['title']; ?></h1>

<ul class="list-group">
    <?php foreach ($data['courses'] as $course) : ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><?= htmlspecialchars($course['name']); ?></h5>
                <small>Instructor: <?= htmlspecialchars($course['instructor']); ?></small>
            </div>
            <span class="badge bg-primary rounded-pill">ID: <?= $course['id']; ?></span>
        </li>
    <?php endforeach; ?>
</ul>

<div class="mt-4">
    <a href="<?= URLROOT; ?>/home" class="btn btn-secondary"> &larr; Back to Home</a>
</div>
