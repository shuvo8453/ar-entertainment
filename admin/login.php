<?php

/**
 * AR Entertainment - Admin Login
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/helpers.php';

// If already logged in, redirect to dashboard
if (is_logged_in()) {
    redirect('admin/index.php');
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error_msg = 'Security validation failed (Invalid CSRF token). Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error_msg = 'Please enter both email and password.';
        } else {
            try {
                $stmt = db()->prepare("SELECT * FROM users WHERE email = ? AND status = 'active' LIMIT 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    // Password correct! Create session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];

                    // Update last login
                    $update_stmt = db()->prepare("UPDATE users SET last_login_at = NOW() WHERE id = ?");
                    $update_stmt->execute([$user['id']]);

                    set_flash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
                    redirect('admin/index.php');
                } else {
                    $error_msg = 'Invalid email address or password.';
                }
            } catch (PDOException $e) {
                $error_msg = 'A database error occurred. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login &mdash; AR Entertainment</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --ar-primary: #e50914;
            --ar-primary-hover: #b80710;
            --ar-dark: #0f1016;
            --ar-card-bg: #181924;
            --ar-border: #282a3c;
        }

        body {
            background-color: var(--ar-dark);
            color: #f1f2f6;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at 50% 20%, #25283f 0%, #0f1016 70%);
        }

        .login-card {
            background-color: var(--ar-card-bg);
            border: 1px solid var(--ar-border);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }

        .login-header {
            padding: 35px 30px 20px;
            text-align: center;
            border-bottom: 1px solid var(--ar-border);
        }

        .brand-logo-text {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #fff;
            text-transform: uppercase;
        }

        .brand-logo-text span {
            color: var(--ar-primary);
        }

        .login-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #a4b0be;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group-text {
            background-color: #202232;
            border: 1px solid var(--ar-border);
            color: #747d8c;
            border-radius: 8px 0 0 8px;
        }

        .form-control {
            background-color: #202232;
            border: 1px solid var(--ar-border);
            color: #fff;
            padding: 12px 14px;
            border-radius: 0 8px 8px 0;
            font-size: 14px;
        }

        .form-control:focus {
            background-color: #25273a;
            border-color: var(--ar-primary);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.25);
        }

        .btn-ar-primary {
            background: linear-gradient(135deg, var(--ar-primary) 0%, #c40812 100%);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-ar-primary:hover {
            background: linear-gradient(135deg, #ff1e2b 0%, var(--ar-primary) 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(229, 9, 20, 0.35);
        }

        .quick-hint {
            background: rgba(255, 255, 255, 0.04);
            border: 1px dashed #3a3d54;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 12px;
            color: #9aa0a6;
            margin-top: 20px;
        }

        .quick-hint code {
            color: #ff6b81;
            background: rgba(0, 0, 0, 0.3);
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="container p-3">
        <div class="login-card mx-auto">
            <div class="login-header">
                <div class="brand-logo-text mb-1">
                    <i class="fa-solid fa-clapperboard me-2 text-danger"></i>AR <span>ENTERTAINMENT</span>
                </div>
                <div class="text-muted small">Administration Control Panel</div>
            </div>

            <div class="login-body">
                <?= render_flash() ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger py-2 px-3 small d-flex align-items-center mb-3">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <div><?= htmlspecialchars($error_msg) ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label" for="email">Admin Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control" placeholder="admin@arentertainment.bd" value="<?= htmlspecialchars($_POST['email'] ?? 'admin@arentertainment.bd') ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label" for="password">Password</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••••••" value="Admin@AREnt2026!" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ar-primary w-100">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In to Dashboard
                    </button>
                </form>

                <div class="quick-hint text-center">
                    <div class="fw-semibold mb-1"><i class="fa-solid fa-key me-1 text-warning"></i> Default Credentials:</div>
                    <div>User: <code>admin@arentertainment.bd</code></div>
                    <div>Pass: <code>Admin@AREnt2026!</code></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>