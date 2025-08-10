<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user me-2"></i>User Dashboard</h3>
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
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <h5>Documents</h5>
                                    <p>View documents</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-bell fa-3x mb-3"></i>
                                    <h5>Notifications</h5>
                                    <p>Check notifications</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-cog fa-3x mb-3"></i>
                                    <h5>Profile</h5>
                                    <p>Update profile</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 