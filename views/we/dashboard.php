<?php require_once VIEWS_PATH . 'layouts/main.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-desktop me-2"></i>PART CONTROL SYSTEM DASHBOARD</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Welcome, <?= htmlspecialchars($user['user_name']) ?>!</h5>
                            <p class="text-muted">User ID: <?= htmlspecialchars($user['user_id']) ?></p>
                            <p class="text-muted">Name: <?= htmlspecialchars($user['user_name']) ?></p>
                            <p class="text-muted">Role: <?= htmlspecialchars(ucfirst($user['role'])) ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= BASE_URL ?>logout" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mt-4">
                        <a href="<?= BASE_URL ?>materials" class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-boxes fa-3x mb-3"></i>
                                    <h5>Welding</h5>
                                    <p>Welding management</p>
                                </div>
                            </div>
                        </a>
                        <a href="<?= BASE_URL ?>production" class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-industry fa-3x mb-3"></i>
                                    <h5>Painting</h5>
                                    <p>Painting management</p>
                                </div>
                            </div>
                        </a>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                                    <h5>Assembly</h5>
                                    <p>Assembly management</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/main.php'; ?> 