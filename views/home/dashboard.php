<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Welcome, <?= htmlspecialchars($user['user_name']) ?>!</h5>
                            <p class="text-muted">User ID: <?= htmlspecialchars($user['user_id']) ?></p>
                            <p class="text-muted">Name: <?= htmlspecialchars($user['user_name']) ?></p>
                            <?php
                            // Ensure roles are an array; support comma-separated string or array
                            $roles = isset($user['roles']) ? $user['roles'] : [];
                            if (!is_array($roles)) {
                                $roles = array_filter(array_map('trim', explode(',', (string)$roles)));
                            }
                            ?>
                            <div class="mb-2">
                                <small class="text-muted">Roles:</small>
                                <div class="mt-1 badges-row">
                                    <?php if (empty($roles)): ?>
                                        <span class="text-muted">None</span>
                                    <?php else: ?>
                                        <?php foreach ($roles as $r):
                                            $clean = trim($r);
                                            $label = htmlspecialchars(ucfirst((string)$clean));
                                            $roleClass = 'role-' . preg_replace('/[^a-z0-9_-]/', '', strtolower($clean));
                                        ?>
                                            <span class="badge-role badge-role--sm badge-role--dark badge-role-pill <?= $roleClass ?> me-1 mb-1" title="<?= $label ?>">
                                                <?php if (strtolower($clean) === 'admin'): ?>
                                                    <i class="fas fa-user-shield me-1"></i>
                                                <?php elseif (strtolower($clean) === 'staff'): ?>
                                                    <i class="fas fa-briefcase me-1"></i>
                                                <?php elseif (strtolower($clean) === 'viewer'): ?>
                                                    <i class="fas fa-eye me-1"></i>
                                                <?php endif; ?>
                                                <?= $label ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        
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
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <h5>Users</h5>
                                    <p>Manage system users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-cogs fa-3x mb-3"></i>
                                    <h5>Settings</h5>
                                    <p>System configuration</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                    <h5>Reports</h5>
                                    <p>View system reports</p>
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