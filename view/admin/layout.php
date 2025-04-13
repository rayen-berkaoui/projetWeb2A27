<?php include 'partials/sidebar.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(isset($page_title) ? $page_title : 'Dashboard') ?></title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/view/assets/css/dashboard.css" rel="stylesheet">
    <script src="<?= base_url('view/assets/js/dashboard.js') ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
        }

        .content {
            padding: 30px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            margin: 20px;
            color: #2e2e2e;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eef2f7;
        }

        .header-actions h1 {
            font-size: 24px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h1, h3, p { color: #2b354f; }

        .open-modal-btn {
            padding: 12px 22px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .open-modal-btn:hover { 
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            transform: translateY(-1px);
        }

        #campaignModal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-y: auto;
            backdrop-filter: blur(8px);
            background: rgba(15, 23, 42, 0.4);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Table Styles */
        .campaigns-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid #eef2f7;
        }

        .campaigns-table th,
        .campaigns-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #eef2f7;
            font-size: 14px;
        }

        .campaigns-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .campaigns-table tr:hover {
            background: #f9fafb;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-edit {
            background: #4b6cb7;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .action-buttons button {
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .action-buttons button:hover {
            transform: translateY(-1px);
        }

        /* Updated Modal Styles */
        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                0 1px 2px rgba(255, 255, 255, 0.2) inset;
            padding: 20px; /* Adjusted padding */
            border-radius: 16px;
            width: min(90vw, 850px); /* Adjusted width dynamically */
            max-height: calc(100vh - 40px); /* Dynamically constrain height */
            overflow: hidden; /* Remove scroll */
            display: flex;
            flex-direction: column; /* Ensure content stacks vertically */
            margin: auto;
            position: relative;
            animation: fadeInUp 0.4s ease-out;
        }

        .modal-content h2 {
            margin: 0 0 15px 0; /* Adjusted bottom margin */
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.025em;
            color: #1e293b;
            text-align: center;
        }

        /* Adjust form layout for reduced spacing */
        .modal-content form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Adjusted gap between form rows */
            flex: 1; /* Allow form to take available space */
            overflow-y: auto; /* Allow internal scrolling for form content */
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Adjusted column width */
            gap: 12px; /* Adjusted gap between columns */
        }

        .form-group {
            position: relative;
            margin-bottom: 0.8rem; /* Adjusted bottom margin */
            min-height: 70px; /* Ensure space for error messages */
        }

        .form-group.full-width {
            grid-column: 1 / -1; /* Full width for textarea or larger inputs */
        }

        .form-group input,
        .form-group textarea {
            padding: 10px; /* Adjusted padding */
            font-size: 0.9rem; /* Adjusted font size */
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            transition: all 0.2s ease;
            width: 100%;
        }

        .form-group textarea {
            min-height: 90px; /* Adjusted height */
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px; /* Adjusted gap between buttons */
            margin-top: 10px; /* Adjusted top margin */
            padding-top: 10px; /* Adjusted padding */
            border-top: 1px solid #e2e8f0;
        }

        /* Error message styles */
        .error-message {
            color: red;
            font-size: 0.85rem;
            margin-top: 5px;
            position: absolute;
            bottom: -20px; /* Position error message inside the form group */
            left: 0;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .modal-content {
                width: 95vw; /* Use 95% of viewport width for smaller screens */
                max-height: calc(100vh - 20px); /* Dynamically reduce height for smaller screens */
            }

            .form-row {
                grid-template-columns: 1fr; /* Stack inputs vertically on smaller screens */
            }
        }

        /* Hide scrollbar */
        .modal-content::-webkit-scrollbar {
            width: 0; /* Remove scrollbar width */
            height: 0; /* Remove scrollbar height */
        }

        .modal-content {
            scrollbar-width: none; /* Hide scrollbar in Firefox */
            -ms-overflow-style: none; /* Hide scrollbar in Internet Explorer */
        }

        .modal-content h2 {
            margin: 0 0 10px 0; /* Reduced bottom margin */
            font-size: 1.5rem; /* Slightly smaller font size */
            font-weight: 600;
            letter-spacing: -0.025em;
            color: #1e293b;
            text-align: center;
        }

        /* Adjust form layout for reduced spacing */
        .modal-content form {
            display: grid;
            gap: 10px; /* Further reduced gap between form rows */
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Adjusted column width */
            gap: 12px; /* Further reduced gap between columns */
        }

        .form-group {
            position: relative;
            margin-bottom: 0.8rem; /* Reduced bottom margin */
            min-height: 50px; /* Reduced height */
        }

        .form-group.full-width {
            grid-column: 1 / -1; /* Full width for textarea or larger inputs */
        }

        .form-group input,
        .form-group textarea {
            padding: 10px; /* Further reduced padding */
            font-size: 0.9rem; /* Slightly smaller font size */
            border: 2px solid #e2e8f0;
            border-radius: 8px; /* Slightly smaller border radius */
            background: #ffffff;
            transition: all 0.2s ease;
            width: 100%;
        }

        .form-group textarea {
            min-height: 90px; /* Reduced height */
            resize: vertical;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            gap: 8px; /* Further reduced gap between buttons */
            margin-top: 8px; /* Reduced top margin */
            padding-top: 10px; /* Reduced padding */
            border-top: 1px solid #e2e8f0;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .modal-content {
                width: 95vw; /* Use 95% of viewport width for smaller screens */
                max-height: calc(100vh - 40px); /* Dynamically reduce height for smaller screens */
            }

            .form-row {
                grid-template-columns: 1fr; /* Stack inputs vertically on smaller screens */
            }
        }

        .modal-content form {
            display: grid;
            gap: 25px;
            padding: 10px 5px;
        }

        /* Close Button Styles */
        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: #e2e8f0;
            color: #334155;
            transform: rotate(90deg);
        }

        /* Flex layout for form groups */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            align-items: start;
        }

        .form-group {
            margin-bottom: 0; /* Remove margin since we're using grid gap */
            position: relative;
            margin-bottom: 1.5rem;
            min-height: 65px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px 16px;
            background: #f8faff;
            border: 2px solid #e2e8f0;
            width: 100%;
            padding: 16px 14px;
            font-size: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            transition: all 0.2s ease;
            position: relative;
        }

        /* Adjust textarea */
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Form actions at bottom */
        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 10px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-cancel, .open-modal-btn {
            min-width: 120px;
        }

        .form-group label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
            color: #4a5568;
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            padding: 0 4px;
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            pointer-events: none;
            left: 12px;
            top: 18px;
            font-size: 0.9rem;
            padding: 0 6px;
            background: transparent;
            transition: all 0.2s ease;
            pointer-events: none;
            color: #64748b;
            z-index: 1;
        }

        .form-group.full-width label {
            top: 24px;
            top: 4px;
            font-size: 0.75rem;
            background: #ffffff;
        }

        .form-group input:focus ~ label,
        .form-group input:not(:placeholder-shown) ~ label,
        .form-group textarea:focus ~ label,
        .form-group textarea:not(:placeholder-shown) ~ label {
            top: 0;
            font-size: 0.8rem;
            color: #4f46e5;
            background: #ffffff;
            top: 4px;
            font-size: 0.75rem;
            color: #6366f1;
            font-weight: 500;
            background: #ffffff;
        }

        .form-group input,
        .form-group textarea {
            padding: 16px;
            font-size: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: transparent;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            border-color: #6366f1;
            padding-top: 22px;
            padding-bottom: 10px;
        }

        .form-group.with-icon {
            position: relative;
        }

        .form-group.with-icon input {
            padding-left: 40px;
        }

        .form-group.with-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .id-field {
            background: #f8fafc;
            color: #a0aec0;
            cursor: not-allowed;
            background: #f8fafc !important;
            color: #64748b;
            cursor: not-allowed;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #ffffff;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        .content-area {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
            background: #f0f4f8;
        }

        .top-header {
            background: #ffffff;
            padding: 15px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            .content {
                padding: 15px;
                margin: 10px;
            }

            .campaigns-table {
                display: block;
                overflow-x: auto;
            }

            .header-actions {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .header-actions h1 {
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .form-actions {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .modal-content {
                width: 95vw; /* Use 95% of viewport width for smaller screens */
                max-height: 85vh; /* Slightly reduce height for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .content-area {
                margin-left: 0;
                padding: 10px;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .modal-content {
                width: 95vw;
                padding: 20px;
                margin: 10px;
                max-height: 90vh;
            }

            .campaigns-table th,
            .campaigns-table td {
                padding: 10px;
                font-size: 13px;
            }
        }

        /* Form Actions */
        .form-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-cancel {
            padding: 12px 22px;
            background: #f1f5f9;
            color: #4a5568;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            padding: 12px 24px;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
        }

        .open-modal-btn,
        .btn-cancel {
            font-size: 0.95rem;
            padding: 12px 24px;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .form-row {
            gap: 24px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar"></aside>
            <?php include 'partials/sidebar.php' ?>
        </aside>

        <!-- Main Content Area -->
        <main class="content-area">
            <!-- Top Header Bar -->
            <header class="top-header">
                <div class="header-left">
                    <h1><?php echo htmlspecialchars(isset($page_title) ? $page_title : 'Dashboard'); ?></h1>
                </div>
                <div class="header-right">
                    <div class="user-menu">
                        <span class="user-avatar">👤</span>
                        <span class="user-name">Admin</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                <?php echo isset($content) ? $content : ''; ?>
            </div>
        </main>
    </div>
</body>
</html>
