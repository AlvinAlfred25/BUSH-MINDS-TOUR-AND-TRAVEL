<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard – Bush Minds Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Lato', sans-serif; background: #F0EDE6; color: #2A2A2A; }

    /* SIDEBAR */
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: 240px; height: 100vh;
      background: #0E1F18;
      display: flex; flex-direction: column;
      padding: 2rem 0;
      z-index: 100;
    }
    .sidebar-logo {
      display: flex; align-items: center; gap: 0.75rem;
      padding: 0 1.5rem 2rem;
      border-bottom: 1px solid rgba(200,153,42,0.2);
    }
    .sidebar-logo img { width: 44px; height: 44px; border-radius: 50%; border: 2px solid #C8992A; }
    .sidebar-logo span { font-family: 'Playfair Display', serif; font-size: 0.95rem; color: #fff; }
    .sidebar-logo small { display: block; font-size: 0.65rem; color: #C8992A; text-transform: uppercase; letter-spacing: 1px; }
    .sidebar nav { margin-top: 1.5rem; flex: 1; }
    .sidebar nav a {
      display: flex; align-items: center; gap: 0.8rem;
      padding: 0.85rem 1.5rem;
      color: rgba(255,255,255,0.7);
      font-size: 0.88rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: 1px;
      text-decoration: none;
      transition: all 0.3s;
    }
    .sidebar nav a:hover, .sidebar nav a.active {
      background: rgba(200,153,42,0.12);
      color: #C8992A;
      border-left: 3px solid #C8992A;
    }
    .sidebar nav a i { width: 18px; text-align: center; }
    .sidebar-footer {
      padding: 1.5rem;
      border-top: 1px solid rgba(200,153,42,0.15);
    }
    .sidebar-footer a {
      display: flex; align-items: center; gap: 0.6rem;
      color: rgba(255,255,255,0.5); font-size: 0.82rem;
      text-decoration: none;
    }
    .sidebar-footer a:hover { color: #e74c3c; }

    /* MAIN */
    .main { margin-left: 240px; padding: 2rem; min-height: 100vh; }
    .topbar {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 2rem;
    }
    .topbar h1 { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #1B3A2D; }
    .topbar .admin-badge {
      background: #1B3A2D; color: #C8992A;
      padding: 0.4rem 1rem; border-radius: 20px;
      font-size: 0.82rem; font-weight: 700;
    }

    /* STAT CARDS */
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.2rem; margin-bottom: 2rem; }
    .stat-card {
      background: #fff; border-radius: 6px;
      padding: 1.5rem; text-align: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
      border-top: 3px solid #C8992A;
    }
    .stat-card .num { font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #1B3A2D; font-weight: 900; }
    .stat-card .lbl { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-top: 0.3rem; }

    /* TABLE */
    .table-card { background: #fff; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); overflow: hidden; }
    .table-header {
      padding: 1.2rem 1.5rem;
      border-bottom: 1px solid #eee;
      display: flex; justify-content: space-between; align-items: center;
    }
    .table-header h2 { font-size: 1.1rem; color: #1B3A2D; }
    table { width: 100%; border-collapse: collapse; }
    th {
      background: #1B3A2D; color: #C8992A;
      padding: 0.85rem 1rem; text-align: left;
      font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;
    }
    td { padding: 0.85rem 1rem; border-bottom: 1px solid #f0ede6; font-size: 0.88rem; vertical-align: top; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #faf8f4; }

    .badge {
      display: inline-block; padding: 0.25rem 0.7rem;
      border-radius: 20px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
    }
    .badge-new { background: #e8f5e9; color: #2e7d32; }
    .badge-read { background: #e3f2fd; color: #1565c0; }
    .badge-replied { background: #f3e5f5; color: #6a1b9a; }

    .action-btns { display: flex; gap: 0.4rem; }
    .btn-action {
      padding: 0.3rem 0.7rem; border-radius: 3px;
      font-size: 0.75rem; font-weight: 700;
      cursor: pointer; border: none; transition: all 0.2s;
      text-decoration: none; display: inline-block;
    }
    .btn-view { background: #1B3A2D; color: #C8992A; }
    .btn-view:hover { background: #2C5A42; }
    .btn-delete { background: #fdecea; color: #c0392b; }
    .btn-delete:hover { background: #c0392b; color: #fff; }

    .empty-state { text-align: center; padding: 3rem; color: #aaa; }
    .empty-state i { font-size: 2.5rem; margin-bottom: 1rem; display: block; }

    /* MODAL */
    .modal-overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,0.6); z-index: 999;
      align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal {
      background: #fff; border-radius: 6px;
      max-width: 600px; width: 90%;
      max-height: 85vh; overflow-y: auto;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    .modal-header {
      background: #1B3A2D; color: #fff;
      padding: 1.2rem 1.5rem;
      display: flex; justify-content: space-between; align-items: center;
    }
    .modal-header h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; }
    .modal-close { background: none; border: none; color: #fff; font-size: 1.3rem; cursor: pointer; }
    .modal-body { padding: 1.5rem; }
    .detail-row { display: grid; grid-template-columns: 140px 1fr; gap: 0.5rem; padding: 0.6rem 0; border-bottom: 1px solid #f0ede6; font-size: 0.88rem; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { font-weight: 700; color: #1B3A2D; }
    .detail-value { color: #444; }
    .message-box { background: #f9f7f3; padding: 1rem; border-radius: 4px; border-left: 3px solid #C8992A; line-height: 1.6; }
  </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require_once '../php/config.php';

$conn = getConnection();

// Handle status update
if (isset($_GET['mark']) && isset($_GET['id'])) {
    $newStatus = in_array($_GET['mark'], ['new','read','replied']) ? $_GET['mark'] : 'read';
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE enquiries SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $newStatus, $id);
    $stmt->execute();
    $stmt->close();
    header('Location: dashboard.php');
    exit;
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM enquiries WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: dashboard.php');
    exit;
}

// Fetch stats
$totalResult  = $conn->query("SELECT COUNT(*) AS c FROM enquiries")->fetch_assoc();
$newResult    = $conn->query("SELECT COUNT(*) AS c FROM enquiries WHERE status='new'")->fetch_assoc();
$repliedResult= $conn->query("SELECT COUNT(*) AS c FROM enquiries WHERE status='replied'")->fetch_assoc();
$total   = $totalResult['c'];
$newCount= $newResult['c'];
$replied = $repliedResult['c'];

// Fetch all enquiries
$enquiries = $conn->query("SELECT * FROM enquiries ORDER BY submitted_at DESC");

// Fetch single enquiry for modal
$viewEnquiry = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $id = intval($_GET['view']);
    $stmt = $conn->prepare("SELECT * FROM enquiries WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $viewEnquiry = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    // Mark as read
    if ($viewEnquiry && $viewEnquiry['status'] === 'new') {
        $conn->query("UPDATE enquiries SET status='read' WHERE id=$id");
        $viewEnquiry['status'] = 'read';
    }
}
?>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-logo">
    <img src="../images/logo3.jpeg" alt="Logo"/>
    <span>Bush Minds <small>Admin Panel</small></span>
  </div>
  <nav>
    <a href="dashboard.php" class="active"><i class="fas fa-inbox"></i> Enquiries</a>
    <a href="../index.html" target="_blank"><i class="fas fa-globe"></i> View Website</a>
  </nav>
  <div class="sidebar-footer">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['admin_username']) ?>)</a>
  </div>
</div>

<!-- MAIN -->
<div class="main">
  <div class="topbar">
    <h1>Booking Enquiries</h1>
    <span class="admin-badge"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
  </div>

  <!-- STATS -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="num"><?= $total ?></div>
      <div class="lbl">Total Enquiries</div>
    </div>
    <div class="stat-card">
      <div class="num" style="color:#2e7d32"><?= $newCount ?></div>
      <div class="lbl">New / Unread</div>
    </div>
    <div class="stat-card">
      <div class="num" style="color:#6a1b9a"><?= $replied ?></div>
      <div class="lbl">Replied</div>
    </div>
    <div class="stat-card">
      <div class="num" style="color:#1565c0"><?= $total - $newCount - $replied ?></div>
      <div class="lbl">Read / Pending</div>
    </div>
  </div>

  <!-- TABLE -->
  <div class="table-card">
    <div class="table-header">
      <h2><i class="fas fa-envelope"></i> All Enquiries</h2>
    </div>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Destination</th>
          <th>Travel Date</th>
          <th>Submitted</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($enquiries->num_rows === 0): ?>
          <tr><td colspan="8">
            <div class="empty-state">
              <i class="fas fa-inbox"></i>
              <p>No enquiries yet. They'll appear here once clients submit the contact form.</p>
            </div>
          </td></tr>
        <?php else: ?>
          <?php while ($row = $enquiries->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['destination'] ?: '—') ?></td>
            <td><?= $row['travel_date'] ? date('d M Y', strtotime($row['travel_date'])) : '—' ?></td>
            <td><?= date('d M Y, H:i', strtotime($row['submitted_at'])) ?></td>
            <td>
              <span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
            </td>
            <td>
              <div class="action-btns">
                <a href="?view=<?= $row['id'] ?>" class="btn-action btn-view"><i class="fas fa-eye"></i> View</a>
                <a href="?mark=replied&id=<?= $row['id'] ?>" class="btn-action" style="background:#f3e5f5;color:#6a1b9a;" title="Mark as Replied"><i class="fas fa-check"></i></a>
                <a href="?delete=<?= $row['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Delete this enquiry?')"><i class="fas fa-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- VIEW MODAL -->
<?php if ($viewEnquiry): ?>
<div class="modal-overlay open" id="viewModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Enquiry #<?= $viewEnquiry['id'] ?> — <?= htmlspecialchars($viewEnquiry['first_name'] . ' ' . $viewEnquiry['last_name']) ?></h3>
      <button class="modal-close" onclick="document.getElementById('viewModal').classList.remove('open')">✕</button>
    </div>
    <div class="modal-body">
      <div class="detail-row"><span class="detail-label">Full Name</span><span class="detail-value"><?= htmlspecialchars($viewEnquiry['first_name'] . ' ' . $viewEnquiry['last_name']) ?></span></div>
      <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value"><a href="mailto:<?= htmlspecialchars($viewEnquiry['email']) ?>"><?= htmlspecialchars($viewEnquiry['email']) ?></a></span></div>
      <div class="detail-row"><span class="detail-label">Phone / WhatsApp</span><span class="detail-value"><?= htmlspecialchars($viewEnquiry['phone'] ?: 'Not provided') ?></span></div>
      <div class="detail-row"><span class="detail-label">Destination</span><span class="detail-value"><?= htmlspecialchars($viewEnquiry['destination'] ?: 'Not specified') ?></span></div>
      <div class="detail-row"><span class="detail-label">No. of Travellers</span><span class="detail-value"><?= $viewEnquiry['travelers'] ?></span></div>
      <div class="detail-row"><span class="detail-label">Travel Date</span><span class="detail-value"><?= $viewEnquiry['travel_date'] ? date('d F Y', strtotime($viewEnquiry['travel_date'])) : 'Not specified' ?></span></div>
      <div class="detail-row"><span class="detail-label">Submitted</span><span class="detail-value"><?= date('d F Y, H:i', strtotime($viewEnquiry['submitted_at'])) ?></span></div>
      <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value"><span class="badge badge-<?= $viewEnquiry['status'] ?>"><?= ucfirst($viewEnquiry['status']) ?></span></span></div>
      <div style="margin-top:1rem;">
        <div class="detail-label" style="margin-bottom:0.5rem;">Message</div>
        <div class="message-box"><?= nl2br(htmlspecialchars($viewEnquiry['message'])) ?></div>
      </div>
      <div style="margin-top:1.5rem; display:flex; gap:0.75rem;">
        <a href="mailto:<?= htmlspecialchars($viewEnquiry['email']) ?>" class="btn-action btn-view" style="padding:0.6rem 1.2rem; font-size:0.85rem;">
          <i class="fas fa-reply"></i> Reply via Email
        </a>
        <a href="?mark=replied&id=<?= $viewEnquiry['id'] ?>" class="btn-action" style="background:#f3e5f5;color:#6a1b9a; padding:0.6rem 1.2rem; font-size:0.85rem;">
          <i class="fas fa-check"></i> Mark as Replied
        </a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
// Close modal on overlay click
document.querySelector('.modal-overlay')?.addEventListener('click', function(e) {
  if (e.target === this) this.classList.remove('open');
});
</script>
</body>
</html>
