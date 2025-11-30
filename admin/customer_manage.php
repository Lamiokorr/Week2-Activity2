<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - KultureKart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 50%, #ffe8f0 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle pattern overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 60px, rgba(255, 107, 53, 0.02) 60px, rgba(255, 107, 53, 0.02) 120px),
                repeating-linear-gradient(-45deg, transparent, transparent 60px, rgba(233, 30, 99, 0.02) 60px, rgba(233, 30, 99, 0.02) 120px);
            z-index: 0;
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            padding-top: 120px;
        }

        /* Logo & Back Button */
        .site-logo {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 120px;
            height: auto;
            z-index: 1001;
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 10px rgba(255, 107, 53, 0.2));
        }

        .site-logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 6px 15px rgba(233, 30, 99, 0.3));
        }

        .btn-back {
            position: fixed;
            top: 20px;
            left: 160px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid #ff6b35;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            color: #ff6b35;
            font-weight: 600;
            font-style: oblique;
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.2);
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.3);
        }

        /* Header styling */
        h2 {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 3rem;
            font-weight: 700;
            font-style: oblique;
            text-align: center;
            margin-bottom: 1rem;
            letter-spacing: -1px;
            animation: fadeInDown 0.6s ease;
        }

        .subtitle {
            text-align: center;
            color: #e91e63;
            font-size: 1.1rem;
            font-style: oblique;
            margin-bottom: 2.5rem;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.15);
            transition: all 0.3s ease;
            border-left: 4px solid #ff6b35;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.25);
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #666;
            font-style: oblique;
            font-weight: 600;
            margin: 0;
        }

        /* Search Bar */
        .search-container {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.15);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-container input {
            flex: 1;
            padding: 0.8rem 1.2rem;
            border: 2px solid #ffe0ec;
            border-radius: 12px;
            font-style: oblique;
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
        }

        .search-container button {
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(255, 107, 53, 0.3);
        }

        .search-container button:hover {
            background: linear-gradient(135deg, #e91e63 0%, #ff6b35 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4);
        }

        /* Customer Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
            animation: fadeInUp 0.8s ease;
            overflow-x: auto;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        thead {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
        }

        th {
            padding: 1.2rem 1rem;
            text-align: left;
            color: white;
            font-weight: 700;
            font-style: oblique;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            border: none;
        }

        th:first-child {
            border-radius: 15px 0 0 0;
        }

        th:last-child {
            border-radius: 0 15px 0 0;
        }

        tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #ffe0ec;
        }

        tbody tr:hover {
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            transform: scale(1.01);
        }

        td {
            padding: 1rem;
            color: #333;
            font-style: oblique;
            font-weight: 500;
            vertical-align: middle;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            font-style: oblique;
        }

        .status-active {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #999 0%, #777 100%);
            color: white;
        }

        /* Action Buttons */
        .btn-view {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
            text-decoration: none;
            display: inline-block;
            font-size: 0.85rem;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
            color: white;
        }

        .btn-delete {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #c2185b 0%, #ad1457 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(233, 30, 99, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #e91e63;
            font-style: oblique;
            font-weight: 600;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                padding-top: 100px;
            }

            h2 {
                font-size: 2rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .search-container {
                flex-direction: column;
            }

            .search-container button {
                width: 100%;
            }

            .table-card {
                padding: 1rem;
            }

            .site-logo {
                width: 80px;
            }

            .btn-back {
                left: 110px;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <a href="../index.php">
        <img src="../assets/images/logo.png" alt="KultureKart Logo" class="site-logo">
    </a>
    <a href="../index.php" class="btn-back">← Back to Home</a>

    <div class="container">
        <h2>Customer Management</h2>
        <p class="subtitle">Manage and view all registered customers</p>
