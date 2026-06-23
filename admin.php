<?php
// ── ADMIN PANEL ──
// Bush Minds Tours & Travel
// View all contact form enquiries
// Access at: http://localhost/bushminds/admin.php

session_start();

// ── Simple login credentials (change these!) ──
define('ADMIN_USER', 'bushminds_admin');
define('ADMIN_PASS', 'BushIsHome2026!');

// ── Handle login ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = 'Incorrect username or password.';
    }
}

// ── Handle logout ──
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// ── Handle mark as read / delete ──
if ($_SESSION['admin_logged_in'] ?? false) {
    require_once 'db.php';

    if (isset($_GET['mark_read'])) {
        $pdo->prepare("UPDATE enquiries SET is_read = 1 WHERE id = ?")->execute([$_GET['mark_read']]);
        header('Location: admin.php');
        exit;
    }
    if (isset($_GET['delete'])) {
        $pdo->prepare("DELETE FROM enquiries WHERE id = ?")->execute([$_GET['delete']]);
        header('Location: admin.php');
        exit;
    }

    // ── Fetch all enquiries ──
    $filter = $_GET['filter'] ?? 'all';
    if ($filter === 'unread') {
        $enquiries = $pdo->query("SELECT * FROM enquiries WHERE is_read = 0 ORDER BY submitted_at DESC")->fetchAll();
    } else {
        $enquiries = $pdo->query("SELECT * FROM enquiries ORDER BY submitted_at DESC")->fetchAll();
    }

    $total    = $pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
    $unread   = $pdo->query("SELECT COUNT(*) FROM enquiries WHERE is_read = 0")->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin – Bush Minds Enquiries</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    :root {
      --green: #1B3A2D;
      --gold:  #C8992A;
      --dark:  #0E1F18;
      --ivory: #F5F0E8;
      --red:   #c0392b;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Lato', sans-serif; background: #f0f0f0; color: #222; }

    /* ── LOGIN PAGE ── */
    .login-wrap {
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      background: linear-gradient(135deg, var(--dark), var(--green));
    }
    .login-box {
      background: #fff;
      padding: 3rem 2.5rem;
      border-radius: 6px;
      width: 100%; max-width: 400px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.3);
      text-align: center;
    }
    .login-box img { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid var(--gold); margin-bottom: 1rem; }
    .login-box h2 { font-family: 'Playfair Display', serif; color: var(--green); margin-bottom: 0.3rem; }
    .login-box p  { color: #888; font-size: 0.9rem; margin-bottom: 1.8rem; }
    .login-box input {
      width: 100%; padding: 0.8rem 1rem; margin-bottom: 1rem;
      border: 1.5px solid #ddd; border-radius: 4px;
      font-size: 0.95rem; font-family: 'Lato', sans-serif;
    }
    .login-box input:focus { outline: none; border-color: var(--green); }
    .login-box button {
      width: 100%; padding: 0.85rem;
      background: var(--gold); color: var(--dark);
      border: none; border-radius: 4px;
      font-weight: 700; font-size: 1rem; cursor: pointer;
      text-transform: uppercase; letter-spacing: 1px;
    }
    .login-box button:hover { background: #e8b94a; }
    .login-error { background: #fdecea; color: var(--red); padding: 0.7rem 1rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }

    /* ── ADMIN LAYOUT ── */
    .admin-header {
      background: var(--dark);
      padding: 1rem 2rem;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 2px solid var(--gold);
    }
    .admin-header .brand { display: flex; align-items: center; gap: 0.75rem; }
    .admin-header img { width: 44px; height: 44px; border-radius: 50%; border: 2px solid var(--gold); object-fit: cover; }
    .admin-header h1 { font-family: 'Playfair Display', serif; color: #fff; font-size: 1.2rem; }
    .admin-header h1 span { color: var(--gold); font-size: 0.75rem; font-family: 'Lato', sans-serif; display: block; text-transform: uppercase; letter-spacing: 2px; }
    .logout-btn {
      background: transparent; border: 1px solid rgba(255,255,255,0.3);
      color: rgba(255,255,255,0.75); padding: 0.5rem 1.2rem;
      border-radius: 4px; cursor: pointer; font-size: 0.85rem;
      text-decoration: none; transition: all 0.2s;
    }
    .logout-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }

    .admin-body { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; }

    /* Stats */
    .stats-row { display: flex; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .stat-box {
      background: #fff; border-radius: 6px; padding: 1.5rem 2rem;
      flex: 1; min-width: 160px; text-align: center;
      border-top: 4px solid var(--gold);
      box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
    .stat-box .num { font-size: 2.5rem; font-weight: 700; color: var(--green); font-family: 'Playfair Display', serif; }
    .stat-box .lbl { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-top: 0.2rem; }

    /* Filters */
    .filter-bar { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; align-items: center; }
    .filter-bar a {
      padding: 0.5rem 1.2rem; border-radius: 4px; font-size: 0.85rem;
      font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
      text-decoration: none; transition: all 0.2s;
      background: #fff; color: #555; border: 1px solid #ddd;
    }
    .filter-bar a.active, .filter-bar a:hover { background: var(--green); color: #fff; border-color: var(--green); }
    .filter-bar .count-badge {
      background: var(--gold); color: var(--dark);
      font-size: 0.72rem; padding: 0.15rem 0.5rem;
      border-radius: 20px; font-weight: 700; margin-left: 0.3rem;
    }

    /* Table */
    .table-wrap { background: #fff; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th {
      background: var(--green); color: #fff;
      padding: 0.9rem 1rem; text-align: left;
      font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1px;
    }
    td { padding: 1rem; border-bottom: 1px solid #f0f0f0; font-size: 0.9rem; vertical-align: top; }
    tr:last-child td { border-bottom: none; }
    tr.unread { background: #fffbf0; }
    tr:hover td { background: #fafafa; }

    .badge-unread {
      display: inline-block; background: var(--gold); color: var(--dark);
      font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem;
      border-radius: 3px; text-transform: uppercase; margin-left: 0.4rem;
    }
    .message-cell { max-width: 280px; color: #555; font-size: 0.85rem; line-height: 1.5; }
    .action-links { display: flex; gap: 0.6rem; flex-wrap: wrap; }
    .action-links a {
      font-size: 0.78rem; font-weight: 700; padding: 0.3rem 0.7rem;
      border-radius: 3px; text-decoration: none; transition: opacity 0.2s;
    }
    .action-links a:hover { opacity: 0.8; }
    .btn-mark  { background: #e8f5e9; color: #2e7d32; }
    .btn-email { background: #e3f2fd; color: #1565c0; }
    .btn-del   { background: #fdecea; color: var(--red); }

    .empty-state {
      text-align: center; padding: 4rem 2rem; color: #aaa;
    }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; color: #ddd; display: block; }

    .date-cell { font-size: 0.82rem; color: #888; white-space: nowrap; }
  </style>
</head>
<body>

<?php if (!($_SESSION['admin_logged_in'] ?? false)): ?>

  <!-- ── LOGIN FORM ── -->
  <div class="login-wrap">
    <div class="login-box">
      <img src="images/logo1.jpeg" alt="Bush Minds" />
      <h2>Admin Panel</h2>
      <p>Bush Minds Tours & Travel — Enquiries Dashboard</p>
      <?php if (!empty($login_error)): ?>
        <div class="login-error"><i class="fas fa-exclamation-circle"></i> <?= $login_error ?></div>
      <?php endif; ?>
      <form method="POST">
        <input type="text"     name="username" placeholder="Username" required autocomplete="off" />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="login"><i class="fas fa-sign-in-alt"></i> Sign In</button>
      </form>
    </div>
  </div>

<?php else: ?>

  <!-- ── ADMIN DASHBOARD ── -->
  <header class="admin-header">
    <div class="brand">
      <img src="images/logo1.jpeg" alt="Bush Minds" />
      <h1>Bush Minds <span>Enquiries Dashboard</span></h1>
    </div>
    <a href="admin.php?logout=1" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </header>

  <div class="admin-body">

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-box">
        <div class="num"><?= $total ?></div>
        <div class="lbl">Total Enquiries</div>
      </div>
      <div class="stat-box">
        <div class="num"><?= $unread ?></div>
        <div class="lbl">Unread</div>
      </div>
      <div class="stat-box">
        <div class="num"><?= $total - $unread ?></div>
        <div class="lbl">Read</div>
      </div>
    </div>

    <!-- Filter -->
    <div class="filter-bar">
      <a href="admin.php?filter=all"    class="<?= $filter === 'all'    ? 'active' : '' ?>">All <span class="count-badge"><?= $total ?></span></a>
      <a href="admin.php?filter=unread" class="<?= $filter === 'unread' ? 'active' : '' ?>">Unread <span class="count-badge"><?= $unread ?></span></a>
    </div>

    <!-- Table -->
    <div class="table-wrap">
      <?php if (empty($enquiries)): ?>
        <div class="empty-state">
          <i class="fas fa-inbox"></i>
          <p>No enquiries found.</p>
        </div>
      <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email / Phone</th>
            <th>Destination</th>
            <th>Travellers</th>
            <th>Travel Date</th>
            <th>Message</th>
            <th>Submitted</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($enquiries as $i => $row): ?>
          <tr class="<?= !$row['is_read'] ? 'unread' : '' ?>">
            <td><?= $i + 1 ?><?= !$row['is_read'] ? '<span class="badge-unread">New</span>' : '' ?></td>
            <td><strong><?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?></strong></td>
            <td>
              <?= htmlspecialchars($row['email']) ?><br/>
              <span style="color:#888; font-size:0.82rem;"><?= htmlspecialchars($row['phone']) ?></span>
            </td>
            <td><?= htmlspecialchars($row['destination'] ?: '—') ?></td>
            <td style="text-align:center;"><?= $row['travelers'] ?: '—' ?></td>
            <td><?= $row['travel_date'] ?: '—' ?></td>
            <td class="message-cell"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td class="date-cell"><?= date('d M Y', strtotime($row['submitted_at'])) ?><br/><?= date('H:i', strtotime($row['submitted_at'])) ?></td>
            <td>
              <div class="action-links">
                <?php if (!$row['is_read']): ?>
                <a href="admin.php?mark_read=<?= $row['id'] ?>&filter=<?= $filter ?>" class="btn-mark"><i class="fas fa-check"></i> Mark Read</a>
                <?php endif; ?>
                <a href="mailto:<?= htmlspecialchars($row['email']) ?>?subject=Re: Your Safari Enquiry – Bush Minds Tours & Travel&body=Dear <?= htmlspecialchars($row['fname']) ?>,%0D%0A%0D%0AThank you for your enquiry about <?= htmlspecialchars($row['destination']) ?>.%0D%0A%0D%0A%0D%0ABest regards,%0D%0ABush Minds Tours %26 Travel%0D%0AbushMinds@gmail.com%0D%0AWakiso, Uganda" class="btn-email"><i class="fas fa-envelope"></i> Reply</a>
                <a href="admin.php?delete=<?= $row['id'] ?>&filter=<?= $filter ?>" class="btn-del" onclick="return confirm('Delete this enquiry?')"><i class="fas fa-trash"></i> Delete</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

  </div>

<?php endif; ?>

</body>
</html>
