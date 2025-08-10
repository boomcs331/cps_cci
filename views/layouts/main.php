<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>
<?php require_once VIEWS_PATH . 'layouts/navbar.php'; ?>

<!-- Main Content -->
<main>
    <?= isset($content) ? $content : '' ?>
</main>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?> 