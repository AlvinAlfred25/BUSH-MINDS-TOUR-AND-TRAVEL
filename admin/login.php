<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login – Bush Minds</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet"/>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Lato', sans-serif;
      background: #0E1F18;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      background: #fff;
      border-radius: 6px;
      padding: 3rem 2.5rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.4);
      text-align: center;
    }
    .login-box img {
      width: 90px; height: 90px;
      border-radius: 50%;
      border: 3px solid #C8992A;
      margin-bottom: 1rem;
    }
    h1 { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #1B3A2D; margin-bottom: 0.3rem; }
    p.sub { font-size: 0.85rem; color: #888; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.2rem; text-align: left; }
    label { display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #333; margin-bottom: 0.4rem; }
    input {
      width: 100%; padding: 0.8rem 1rem;
      border: 1.5px solid #ddd; border-radius: 4px;
      font-size: 0.95rem; font-family: 'Lato', sans-serif;
      outline: none; transition: border-color 0.3s;
    }
    input:focus { border-color: #1B3A2D; }
    .btn-login {
      width: 100%; padding: 0.9rem;
      background: #C8992A; color: #0E1F18;
      border: none; border-radius: 4px;
      font-weight: 700; font-size: 0.95rem;
      text-transform: uppercase; letter-spacing: 1px;
      cursor: pointer; transition: background 0.3s;
      margin-top: 0.5rem;
    }
    .btn-login:hover { background: #E8B94A; }
    .error-msg {
      background: #fdecea; color: #c0392b;
      padding: 0.8rem 1rem; border-radius: 4px;
      font-size: 0.88rem; margin-bottom: 1.2rem;
      border-left: 4px solid #c0392b;
      text-align: left;
    }
  </style>
</head>
<body>
<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

require_once '../php/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $username;
                header('Location: dashboard.php');
                exit;
            }
        }
        $error = 'Invalid username or password.';
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
  <div class="login-box">
    <img src="../images/logo3.jpeg" alt="Bush Minds Logo"/>
    <h1>Admin Login</h1>
    <p class="sub">Bush Minds Tours & Travel</p>

    <?php if ($error): ?>
      <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="admin" required/>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required/>
      </div>
      <button type="submit" class="btn-login">Sign In</button>
    </form>
  </div>
</body>
</html>
