<?php
try {
    $errorCode = strtoupper(bin2hex(random_bytes(3))); // 6 hex chars
} catch (Exception $e) {
    $errorCode = strtoupper(substr(uniqid(), -6));
}

// Optional: accept order/amount from query string for context
$orderId = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : 'N/A';
$amount = isset($_GET['amount']) ? htmlspecialchars($_GET['amount']) : null;
$supportEmail = 'support@example.com';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Payment Failed</title>
<style>
    :root { --bg:#fafbfd; --card:#fff; --accent:#d9534f; --muted:#6b7280; }
    body{font-family:Inter,Segoe UI,Helvetica,Arial,sans-serif;background:var(--bg);margin:0;display:flex;align-items:center;justify-content:center;height:100vh}
    .card{background:var(--card);border-radius:12px;box-shadow:0 8px 30px rgba(15,23,42,0.08);padding:34px;width:420px;max-width:94%;}
    .emoji{font-size:48px;margin-bottom:6px}
    h1{margin:0;font-size:20px;color:#0f172a}
    p{color:var(--muted);margin:12px 0 20px}
    .meta{background:#fff4f4;border:1px solid #ffd6d6;padding:10px;border-radius:8px;color:#7a1f1f;font-weight:600;margin-bottom:18px}
    .row{display:flex;gap:8px}
    .btn{flex:1;padding:10px 12px;border-radius:8px;border:0;cursor:pointer;font-weight:600}
    .btn.primary{background:var(--accent);color:#fff}
    .btn.ghost{background:#f3f4f6;color:#0f172a}
    .small{font-size:13px;color:var(--muted);margin-top:10px}
    a.inline{color:inherit;text-decoration:none}
</style>
</head>
<body>
  <div class="card" role="alert" aria-live="polite">
    <div class="emoji" aria-hidden="true">❌</div>
    <h1>Payment Failed</h1>
    <p>We were unable to complete your payment<?php if ($amount) echo " of {$amount}"; ?>. No charges were made.</p>

    <div class="meta">
      Error code: <strong><?php echo $errorCode; ?></strong>
      <?php if ($orderId !== 'N/A') echo " • Order: " . $orderId; ?>
    </div>

    <div class="row" style="margin-bottom:10px">
      <button class="btn primary" onclick="location.href='checkout.php'">Try Again</button>
      <button class="btn ghost" onclick="history.back()">Back</button>
    </div>

    <div class="small">
      Need help? <a class="inline" href="mailto:<?php echo $supportEmail; ?>?subject=Payment%20Issue%20(<?php echo urlencode($errorCode); ?>)&body=<?php echo urlencode("Order: {$orderId}\nError code: {$errorCode}\nAmount: " . ($amount ?? 'N/A') . "\n\nDescribe the issue:"); ?>">Contact support</a>
    </div>
  </div>
</body>
</html>