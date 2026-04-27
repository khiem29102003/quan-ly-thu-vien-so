<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="user-id" content="{{ auth()->id() }}">
    @endauth
    <title>@yield('title', 'Quản Lý Thư Viện')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #06b6d4;
            --primary-dark: #0891b2;
            --secondary: #0e7490;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #22d3ee;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0c4a6e 0%, #164e63 50%, #155e75 100%);
            background-attachment: fixed;
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Modern Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Cho phép rớt dòng khi thiếu chỗ */
            gap: 1rem;
        }
        
        .navbar .logo {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap; /* Không ngắt dòng chữ logo */
        }
        
        .nav-links {
            display: flex;
            gap: 0.4rem;
            list-style: none;
            align-items: center;
            flex-wrap: wrap; /* Cho phép các menu item xuống dòng thay vì ép hẹp lại */
            justify-content: center;
        }
        
        .nav-links a {
            color: var(--gray-700);
            text-decoration: none;
            padding: 0.6rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap; /* Chống đè chữ: luôn giữ text trên 1 dòng */
        }
        
        .nav-links a:hover {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.15) 0%, rgba(14, 116, 144, 0.1) 100%);
            color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.15);
        }
        
        .nav-links a i {
            font-size: 1.15rem;
        }

        @keyframes pulseBadge {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.6); }
            70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .nav-badge {
            min-width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #ef4444;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0 0.4rem;
            margin-left: 0.2rem;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.45);
            animation: pulseBadge 2.5s infinite;
        }
        
        .nav-logout {
            background: none;
            border: none;
            color: var(--gray-700);
            padding: 0.6rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            white-space: nowrap;
        }
        
        .nav-logout:hover {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.15);
        }
        
        .nav-logout i {
            font-size: 1.15rem;
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        /* Modern Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        }
        
        .card-body {
            padding: 0;
        }
        
        /* Modern Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--gray-600) 0%, var(--gray-700) 100%);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, var(--info) 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        /* Modern Table */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
        }
        
        .table th {
            padding: 0.875rem 1rem;
            text-align: left;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.04em;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-300);
        }
        
        .table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--gray-200);
            vertical-align: middle;
        }
        
        .table tbody tr {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(6, 182, 212, 0.05) 0%, rgba(8, 145, 178, 0.03) 100%);
            transform: scale(1.01);
            box-shadow: inset 0 2px 10px rgba(6, 182, 212, 0.05);
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }
        
        .bg-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }
        
        .bg-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }
        
        .bg-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }
        
        .bg-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
        }
        
        .bg-primary {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #3730a3;
        }
        
        /* Stats Cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 50%, #22d3ee 100%);
            opacity: 0.1;
            border-radius: 50%;
            transform: translate(30%, -30%);
            transition: all 0.4s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 30px 60px -12px rgb(0 0 0 / 0.3);
        }
        
        .stat-card:hover::before {
            transform: translate(20%, -20%) scale(1.5);
            opacity: 0.15;
        }
        
        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 50%, #22d3ee 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0.5rem 0;
        }
        
        .stat-card p {
            color: var(--gray-600);
            font-size: 0.95rem;
            font-weight: 500;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Compact Stats Cards */
        .stat-card-compact {
            padding: 1.25rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            animation: slideInUp 0.5s ease-out backwards;
        }
        
        .stat-card-compact:nth-child(1) { animation-delay: 0.1s; }
        .stat-card-compact:nth-child(2) { animation-delay: 0.2s; }
        .stat-card-compact:nth-child(3) { animation-delay: 0.3s; }
        .stat-card-compact:nth-child(4) { animation-delay: 0.4s; }
        .stat-card-compact:nth-child(5) { animation-delay: 0.5s; }
        .stat-card-compact:nth-child(6) { animation-delay: 0.6s; }
        
        /* Premium Stat Cards with Glass Morphism */
        .stat-card-premium {
            padding: 1rem;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            animation: slideInUp 0.6s ease-out backwards;
            backdrop-filter: blur(10px);
            will-change: transform;
        }
        
        .stat-card-premium:nth-child(1) { animation-delay: 0.05s; }
        .stat-card-premium:nth-child(2) { animation-delay: 0.1s; }
        .stat-card-premium:nth-child(3) { animation-delay: 0.15s; }
        .stat-card-premium:nth-child(4) { animation-delay: 0.2s; }
        .stat-card-premium:nth-child(5) { animation-delay: 0.25s; }
        .stat-card-premium:nth-child(6) { animation-delay: 0.3s; }
        
        .stat-card-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        
        .stat-card-premium::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        
        .stat-card-premium:hover {
            transform: translateY(-8px) scale(1.05) rotateY(2deg);
            box-shadow: 0 16px 48px rgba(6, 182, 212, 0.25);
        }
        
        .stat-card-premium:hover::before {
            opacity: 1;
        }
        
        .stat-card-premium:hover::after {
            opacity: 1;
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background-blend-mode: overlay;
        }
        
        /* Chart/Content Containers */
        .chart-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .chart-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgba(6, 182, 212, 0.15);
        }
        
        .chart-title {
            margin: 0 0 1.5rem 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-card-compact::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .stat-card-compact:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3);
        }
        
        .stat-card-compact:hover::before {
            opacity: 1;
        }
        
        .stat-card-compact:active {
            transform: translateY(-2px);
        }
        
        /* Premium Alerts */
        .alert {
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
            animation: slideInDownAlert 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transform-origin: top center;
        }
        
        .alert::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.8);
            animation: alertProgress 5s linear forwards;
            width: 100%;
        }

        @keyframes alertProgress {
            from { width: 100%; }
            to { width: 0%; }
        }

        @keyframes slideInDownAlert {
            from { opacity: 0; transform: translateY(-30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes alertFadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.9); padding: 0; margin: 0; max-height: 0; border: 0; }
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(209, 250, 229, 0.95) 0%, rgba(167, 243, 208, 0.95) 100%);
            color: #065f46;
            border-left: 4px solid var(--success);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(254, 226, 226, 0.95) 0%, rgba(254, 202, 202, 0.95) 100%);
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }
        
        .alert-info {
            background: linear-gradient(135deg, rgba(219, 234, 254, 0.95) 0%, rgba(191, 219, 254, 0.95) 100%);
            color: #1e40af;
            border-left: 4px solid var(--info);
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 1.75rem;
        }
        
        .form-label, label {
            display: block;
            margin-bottom: 0.625rem;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.9375rem;
        }
        
        .form-control, .form-select, input, textarea, select {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid var(--gray-300);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus, .form-select:focus, input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            transform: translateY(-1px);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        /* Book Grid */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 2rem;
            margin-top: 1.5rem;
        }
        
        .book-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .book-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 30px 60px -12px rgb(0 0 0 / 0.3);
        }
        
        .book-cover {
            width: 100%;
            height: 280px;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 50%, #22d3ee 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }
        
        .book-cover::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255,255,255,0.05) 10px,
                rgba(255,255,255,0.05) 20px
            );
            animation: slide 20s linear infinite;
        }
        
        @keyframes slide {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50%, 50%); }
        }
        
        .book-info {
            padding: 1.5rem;
        }
        
        .book-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .book-author {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .book-status {
            font-size: 0.85rem;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            display: inline-block;
            margin-top: 0.5rem;
            font-weight: 600;
        }
        
        .status-available {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }
        
        .status-unavailable {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }
        
        /* Footer */
        footer {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            color: white;
            text-align: center;
            padding: 2.5rem;
            margin-top: 4rem;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
        }
        
        footer p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }
        
        /* Search Bar */
        .search-bar {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .search-bar input {
            flex: 1;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .nav-container {
                flex-direction: column;
                justify-content: center;
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .navbar {
                padding: 1rem;
            }
            
            .nav-links {
                gap: 0.4rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .nav-links a, .nav-logout {
                padding: 0.5rem 0.6rem;
                font-size: 0.85rem;
            }
            
            /* Chỉ ẩn thẻ text thông thường, không ẩn thông báo số lượng */
            .nav-links a span:not(.nav-badge), .nav-logout span {
                display: none;
            }
            
            .stats {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .stat-card h3 {
                font-size: 2rem;
            }
            
            .stat-card-compact {
                padding: 1rem;
            }
            
            .stat-card-compact h3 {
                font-size: 1.5rem !important;
            }
            
            .stat-card-compact p {
                font-size: 0.75rem !important;
            }
            
            .stat-card-compact div[style*="font-size: 2.5rem"] {
                font-size: 2rem !important;
            }
            
            /* Dashboard responsive */
            .book-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            
            .table {
                font-size: 0.875rem;
            }
            
            .card {
                padding: 1.25rem;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .stat-card h3 {
                font-size: 2rem;
            }
            
            .book-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        
        /* Utilities */
        .text-center { text-align: center; }
        .d-flex { display: flex; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 2rem; }
        
        /* Advanced Animation Effects */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(6, 182, 212, 0.5);
            }
            50% {
                box-shadow: 0 0 40px rgba(6, 182, 212, 0.8);
            }
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        
        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(6, 182, 212, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(6, 182, 212, 0);
            }
        }
        
        .animates-on-scroll {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
        
        /* Floating Animation */
        .stat-card-compact {
            animation: slideInUp 0.5s ease-out backwards, float 3s ease-in-out infinite !important;
        }
        
        /* Glow effect for chart containers */
        .chart-glow {
            animation: glow 3s ease-in-out infinite;
        }
        
        /* Number counter animation */
        .counter {
            font-variant-numeric: tabular-nums;
        }

        .realtime-toast-wrap {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            z-index: 2500;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            max-width: min(420px, calc(100vw - 2rem));
        }

        .realtime-toast {
            position: relative;
            overflow: hidden;
            background: rgba(15, 23, 42, 0.85); /* Glass Dark */
            backdrop-filter: blur(12px);
            color: #f8fafc;
            border: 1px solid rgba(34, 211, 238, 0.35);
            border-left: 4px solid #22d3ee;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.3);
            animation: toastEnter 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            transform-origin: right center;
        }

        .realtime-toast::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: #22d3ee;
            box-shadow: 0 0 10px #22d3ee;
            animation: alertProgress 6s linear forwards;
        }
        
        @keyframes toastEnter {
            from { opacity: 0; transform: translateX(100%) scale(0.9); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }

        .realtime-toast h4 {
            margin: 0 0 0.35rem 0;
            font-size: 0.92rem;
            color: #67e8f9;
        }

        .realtime-toast p {
            margin: 0;
            font-size: 0.82rem;
            line-height: 1.35;
            color: #cbd5e1;
        }

        @keyframes toastExit {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(50px) scale(0.9); }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-book-reader"></i>
                <span>Quản Lý Thư Viện</span>
            </div>
            <ul class="nav-links">
                @guest
                    <li><a href="/"><i class="fas fa-home"></i><span>Trang Chủ</span></a></li>
                @endguest
                @auth
                    @if(Auth::user()->role === 'member')
                        <li><a href="/member/browse"><i class="fas fa-heart"></i><span>Đặt Sách</span></a></li>
                        <li><a href="/member/borrowed"><i class="fas fa-book-open"></i><span>Đang Mượn</span></a></li>
                        <li><a href="/member/dashboard"><i class="fas fa-user-circle"></i><span>Tài Khoản</span></a></li>
                    @else
                        <li><a href="/"><i class="fas fa-home"></i><span>Trang Chủ</span></a></li>
                        <li><a href="/books"><i class="fas fa-book"></i><span>Sách</span></a></li>
                        <li><a href="/borrows"><i class="fas fa-clipboard-list"></i><span>Phiếu Mượn</span></a></li>
                        <li>
                            <a href="{{ route('borrows.reservations') }}">
                                <i class="fas fa-inbox"></i><span>Duyệt Mượn</span>
                                @if(($adminNavBadges['pending_reservations'] ?? 0) > 0)
                                    <span class="nav-badge">{{ $adminNavBadges['pending_reservations'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li><a href="/users"><i class="fas fa-users"></i><span>Người Dùng</span></a></li>
                        <li>
                            <a href="/wallet-topups">
                                <i class="fas fa-wallet"></i><span>Nạp Ví</span>
                                @if(($adminNavBadges['pending_topups'] ?? 0) > 0)
                                    <span class="nav-badge">{{ $adminNavBadges['pending_topups'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="/activity-logs">
                                <i class="fas fa-history"></i><span>Nhật Ký</span>
                                @if(($adminNavBadges['pending_total'] ?? 0) > 0)
                                    <span class="nav-badge">{{ $adminNavBadges['pending_total'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li><a href="/admin/settings"><i class="fas fa-cog"></i><span>Cài Đặt Hệ Thống</span></a></li>
                    @endif
                @endauth
                @guest
                    <li><a href="/login" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-sign-in-alt"></i>Đăng Nhập</a></li>
                    <li><a href="/register" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-user-plus"></i>Đăng Ký</a></li>
                @endauth
                @auth
                    <li>
                        <form action="/logout" method="POST" style="margin: 0; padding: 0;">
                            @csrf
                            <button type="submit" class="nav-logout" title="Đăng xuất">
                                <i class="fas fa-sign-out-alt"></i><span>Đăng Xuất</span>
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                <span>{{ $message }}</span>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle" style="font-size: 1.5rem;"></i>
                <span>{{ $message }}</span>
            </div>
        @endif

        @yield('content')
    </div>

    <div id="realtimeToastWrap" class="realtime-toast-wrap"></div>

    <footer>
        <p>&copy; 2026 Hệ Thống Quản Lý Thư Viện - Powered by Laravel & AI</p>
        <p style="margin-top: 0.5rem; font-size: 0.875rem; opacity: 0.7;">
            Designed with <i class="fas fa-heart" style="color: #ec4899;"></i> for Excellence
        </p>
    </footer>

    <script>
        // Auto remove standard alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'alertFadeOut 0.5s ease forwards';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });

        // Scroll-based animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('animates-on-scroll');
                    entry.target.style.animation = 'fadeInUp 0.8s ease-out forwards';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all cards and charts
        document.querySelectorAll('.card, .chart-container, .book-card, .stat-card').forEach(el => {
            el.classList.add('animates-on-scroll');
            observer.observe(el);
        });

        // Number counter animation for stats
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.textContent = value;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Trigger counter animation when stat cards are visible
        const statObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    entry.target.dataset.animated = 'true';
                    const h3 = entry.target.querySelector('h3');
                    if (h3) {
                        const finalValue = parseInt(h3.textContent) || 0;
                        animateValue(h3, 0, finalValue, 1500);
                    }
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.stat-card-compact').forEach(el => {
            statObserver.observe(el);
        });

        // Dynamic glow effect on hover for charts
        document.querySelectorAll('.chart-container').forEach(container => {
            container.addEventListener('mouseenter', function() {
                this.classList.add('chart-glow');
            });
            container.addEventListener('mouseleave', function() {
                this.classList.remove('chart-glow');
            });
        });

        // Table row hover with ripple effect
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.background = 'linear-gradient(90deg, rgba(6, 182, 212, 0.05) 0%, rgba(8, 145, 178, 0.05) 100%)';
            });
            row.addEventListener('mouseleave', function() {
                this.style.background = 'linear-gradient(90deg, #f9fafb 0%, #f3f4f6 100%)';
            });
        });

        // Form input labeling animation
        document.querySelectorAll('.form-control, .form-select, input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], textarea, select').forEach(input => {
            const label = input.previousElementSibling;
            if (label && label.classList.contains('form-label')) {
                input.addEventListener('focus', function() {
                    label.style.color = '#06b6d4';
                    label.style.transform = 'scale(1.05)';
                });
                input.addEventListener('blur', function() {
                    label.style.color = '#374151';
                    label.style.transform = 'scale(1)';
                });
            }
        });

        // Dashboard glow background effect
        const canvas = document.createElement('canvas');
        canvas.style.position = 'fixed';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100%';
        canvas.style.height = '33%';
        canvas.style.pointerEvents = 'none';
        canvas.style.zIndex = '0';
        canvas.style.opacity = '0.03';
        canvas.style.mixBlendMode = 'screen';
        
        let lastScrollY = 0;
        window.addEventListener('scroll', (e) => {
            lastScrollY = window.scrollY;
        });

        // Animate cards on page load
        window.addEventListener('load', () => {
            document.querySelectorAll('.btn, .nav-links a').forEach((btn, index) => {
                btn.style.animation = `fadeInUp 0.5s ease-out ${index * 0.05}s forwards`;
                btn.style.opacity = '0';
            });
        });

        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });

        // Parallax effect on dashboard
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        if (parallaxElements.length > 0) {
            window.addEventListener('scroll', () => {
                parallaxElements.forEach(el => {
                    const speed = parseFloat(el.dataset.parallax) || 0.5;
                    el.style.transform = `translateY(${window.scrollY * speed}px)`;
                });
            });
        }

        @auth
        @if(in_array(auth()->user()->role, ['admin', 'librarian']))
        (function () {
            let latestReservationId = {{ (int) ($adminNavBadges['latest_pending_reservation_id'] ?? 0) }};
            const toastWrap = document.getElementById('realtimeToastWrap');
            const endpoint = '{{ route('admin.realtime-notifications') }}';
            let audioCtx = null;
            let audioUnlocked = false;

            function ensureAudioContext() {
                if (!audioCtx) {
                    const Ctx = window.AudioContext || window.webkitAudioContext;
                    if (Ctx) {
                        audioCtx = new Ctx();
                    }
                }
                return audioCtx;
            }

            function unlockAudio() {
                const ctx = ensureAudioContext();
                if (!ctx) return;

                if (ctx.state === 'suspended') {
                    ctx.resume().then(() => {
                        audioUnlocked = true;
                    }).catch(() => {
                        audioUnlocked = false;
                    });
                } else {
                    audioUnlocked = true;
                }
            }

            function playNotifySound() {
                const ctx = ensureAudioContext();
                if (!ctx || !audioUnlocked) return;

                const now = ctx.currentTime;
                const master = ctx.createGain();
                master.gain.setValueAtTime(0.0001, now);
                master.gain.exponentialRampToValueAtTime(0.05, now + 0.02);
                master.gain.exponentialRampToValueAtTime(0.0001, now + 0.32);
                master.connect(ctx.destination);

                const osc1 = ctx.createOscillator();
                const osc2 = ctx.createOscillator();
                osc1.type = 'sine';
                osc2.type = 'triangle';
                osc1.frequency.setValueAtTime(880, now);
                osc2.frequency.setValueAtTime(1174, now + 0.08);

                osc1.connect(master);
                osc2.connect(master);

                osc1.start(now);
                osc1.stop(now + 0.18);
                osc2.start(now + 0.08);
                osc2.stop(now + 0.28);
            }

            document.addEventListener('click', unlockAudio, { once: true, passive: true });
            document.addEventListener('keydown', unlockAudio, { once: true, passive: true });

            function pushToast(title, lines) {
                if (!toastWrap) return;
                const node = document.createElement('div');
                node.className = 'realtime-toast';
                node.innerHTML = '<h4>' + title + '</h4><p>' + lines.join('<br>') + '</p>';
                toastWrap.prepend(node);
                playNotifySound();

                setTimeout(() => {
                    node.style.animation = 'toastExit 0.4s ease-in forwards';
                }, 6000);

                setTimeout(() => {
                    if (node.parentNode) node.parentNode.removeChild(node);
                }, 6400);
            }

            async function pollNotifications() {
                try {
                    const res = await fetch(endpoint, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        cache: 'no-store'
                    });

                    if (!res.ok) return;

                    const data = await res.json();
                    const currentLatestId = parseInt(data.latest_pending_reservation_id || 0, 10);

                    if (currentLatestId > latestReservationId) {
                        latestReservationId = currentLatestId;
                        const first = (data.pending_reservations || [])[0];
                        const summary = first
                            ? ['Sách: ' + (first.book_title || 'N/A'), 'Thành viên: ' + (first.member_name || 'N/A'), 'Lúc: ' + (first.reserved_at || 'vừa xong')]
                            : ['Có yêu cầu đặt sách mới cần xử lý.'];
                        pushToast('Yêu cầu đặt sách mới', summary);
                    }
                } catch (e) {
                    // Keep silent for transient polling failures.
                }
            }

            setInterval(pollNotifications, 12000);
        })();
        @endif
        @endauth
    </script>

    <!-- AI Chatbot Component -->
    @include('components.chatbot')
</body>
</html>
