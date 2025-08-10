<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-shield me-2"></i>Admin Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Welcome, <?= htmlspecialchars($user['user_name']) ?>!</h5>
                            <p class="text-muted">User ID: <?= htmlspecialchars($user['user_id']) ?></p>
                            <p class="text-muted">Email: <?= htmlspecialchars($user['user_email']) ?></p>
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
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <h5>Users</h5>
                                    <p>Manage all users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-cogs fa-3x mb-3"></i>
                                    <h5>Settings</h5>
                                    <p>System settings</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                    <h5>Reports</h5>
                                    <p>View reports</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-shield-alt fa-3x mb-3"></i>
                                    <h5>Security</h5>
                                    <p>Security settings</p>
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