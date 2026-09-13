<?php

/**
 * AR Entertainment - Admin Header Partial
 */
$page_title = $page_title ?? 'Dashboard';
$current_user = current_user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> &mdash; AR Entertainment Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --ar-primary: #e50914;
            --ar-primary-rgb: 229, 9, 20;
            --ar-primary-hover: #b80710;
            --ar-bg-dark: #0f1016;
            --ar-sidebar-bg: #14151f;
            --ar-card-bg: #181924;
            --ar-border-color: #242638;
            --ar-text-muted: #8c90a4;
            --ar-text-light: #f1f2f6;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--ar-bg-dark);
            color: var(--ar-text-light);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Layout */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .admin-sidebar {
            width: 260px;
            background-color: var(--ar-sidebar-bg);
            border-right: 1px solid var(--ar-border-color);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
        }

        .admin-main {
            flex-grow: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background-color: var(--ar-bg-dark);
        }

        /* Sidebar Branding */
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid var(--ar-border-color);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-brand .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--ar-primary), #9c0008);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.4);
        }

        .sidebar-brand .brand-title {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .sidebar-brand .brand-title span {
            color: var(--ar-primary);
        }

        .sidebar-brand .brand-sub {
            font-size: 11px;
            color: var(--ar-text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Sidebar Nav Links */
        .sidebar-nav {
            padding: 18px 12px;
            overflow-y: auto;
            flex-grow: 1;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #5d6175;
            padding: 10px 14px 6px;
            margin-top: 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 11px 14px;
            color: var(--ar-text-muted);
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .sidebar-link i {
            width: 22px;
            font-size: 16px;
            margin-right: 10px;
            text-align: center;
            transition: color 0.2s ease;
        }

        .sidebar-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-link.active {
            color: #fff;
            background: linear-gradient(90deg, rgba(229, 9, 20, 0.15) 0%, rgba(229, 9, 20, 0.04) 100%);
            border-left: 3px solid var(--ar-primary);
            font-weight: 600;
        }

        .sidebar-link.active i {
            color: var(--ar-primary);
        }

        /* Top Navbar */
        .admin-topbar {
            height: 70px;
            background-color: var(--ar-sidebar-bg);
            border-bottom: 1px solid var(--ar-border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Content Area */
        .admin-content {
            padding: 30px;
            flex-grow: 1;
        }

        /* Cards */
        .card-ar {
            background-color: var(--ar-card-bg);
            border: 1px solid var(--ar-border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* Stat Cards */
        .stat-card {
            background-color: var(--ar-card-bg);
            border: 1px solid var(--ar-border-color);
            border-radius: 12px;
            padding: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #3b3e58;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--ar-text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .icon-red {
            background: rgba(229, 9, 20, 0.15);
            color: #ff3838;
        }

        .icon-blue {
            background: rgba(24, 144, 255, 0.15);
            color: #1890ff;
        }

        .icon-green {
            background: rgba(46, 213, 115, 0.15);
            color: #2ed573;
        }

        .icon-orange {
            background: rgba(255, 159, 26, 0.15);
            color: #ff9f1a;
        }

        .icon-purple {
            background: rgba(155, 89, 182, 0.15);
            color: #a55eea;
        }

        /* Tables */
        .table-dark-custom {
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--ar-border-color);
            color: #e1e4ec;
            margin-bottom: 0;
        }

        .table-dark-custom th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8c90a4;
            font-weight: 700;
            border-bottom: 1px solid var(--ar-border-color);
            padding: 12px 16px;
        }

        .table-dark-custom td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--ar-border-color);
            font-size: 14px;
        }

        /* Badges */
        .badge-ar {
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
        }

        /* Buttons */
        .btn-ar-primary {
            background: linear-gradient(135deg, var(--ar-primary) 0%, #c40812 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-ar-primary:hover {
            background: linear-gradient(135deg, #ff1e2b 0%, var(--ar-primary) 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.4);
        }

        .btn-ar-secondary {
            background-color: #242638;
            border: 1px solid #33364d;
            color: #e1e4ec;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-ar-secondary:hover {
            background-color: #2c2f45;
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                left: -260px;
            }

            .admin-sidebar.show {
                left: 0;
            }

            .admin-main {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">