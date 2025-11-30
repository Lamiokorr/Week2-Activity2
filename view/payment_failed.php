<?php
// payment_failed.php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$DEBUG_MODE = true;  

$debug = [];
if (isset($_GET['debug'])) {
    $debug = json_decode(base64_decode($_GET['debug']), true) ?: [];
}

$orderId = $_GET['order'] ?? 'N/A';
$amount  = $_GET['amount'] ?? null;
$supportEmail = 'support@example.com';

// Generate error code for user (still useful)
try {
    $errorCode = strtoupper(bin2hex(random_bytes(3)));
} catch (Exception $e) {
    $errorCode = strtoupper(substr(uniqid(), -6));
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Payment Failed - Debug Mode</title>
<style>
    :root { --bg:#fafbfd; --card:#fff; --accent:#d9534f; --muted:#6b7280; --green:#10b981; --border:#e5e7eb; }
    body{font-family:Inter,Segoe UI,Helvetica,Arial,sans-serif;background:var(--bg);margin:0;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:1rem}
    .card{background:var(--card);border-radius:12px;box-shadow:0 8px 30px rgba(15,23,42,0.08);padding:34px;width:520px;max-width:94%;}
    .emoji{font-size:48px;margin-bottom:6px}
    h1{margin:0;font-size:20px;color:#0f172a}
    p{color:var(--muted);margin:12px 0 20px}
    .meta{background:#fff4f4;border:1px solid #ffd6d6;padding:12px;border-radius:8px;color:#7a1f1f;font-weight:600;margin-bottom:18px}
    .debug{background:#f8f9fa;border:1px solid var(--border);border-radius:8px;padding:16px;margin:16px 0;font-family:SFMono-Regular,Menlo,Monaco,Consolas,monospace;font-size:13px;max-height:400px;overflow:auto;line-height:1.5;background:#1e1e1e;color:#d4d4d4}
    pre{margin:0;white-space:pre-wrap;word-wrap:break-word}
    .row{display:flex;gap:8px;margin-bottom:10px}
    .btn{flex:1;padding:12px;border-radius:8px;border:0;cursor:pointer;font-weight:600;transition:all .2s}
    .btn.primary{background:var(--accent);color:#fff}
    .btn.ghost{background:#f3f4f6;color:#0f172a}
    .btn:hover{transform:translateY(-1px)}
    .small{font-size:13px;color:var(--muted);margin-top:16px}
    a.inline{color:inherit;text-decoration:underline}
    .success{color:var(--green);font-weight:600}
</style>
</head>
<body>
  <div class="card" role="alert" aria-live="polite">
    <div class="emoji">Failed</div>
    <h1>Payment Failed</h1>
    <p>We couldn't complete your payment<?php if ($amount) echo " of <strong>GHS " . number_format($amount, 2) . "</strong>"; ?>. No charges were made.</p>

    <div class="meta">
      Error code: <strong><?php echo $errorCode; ?></strong>
      <?php if ($orderId !== 'N/A') echo " • Order: " . htmlspecialchars($orderId); ?>
    </div>

    <?php if ($DEBUG_MODE && !empty($debug)): ?>
        <div class="debug">
            <strong>DEBUG INFORMATION (only visible in test mode)</strong><br><br>
            <?php 
            if (!empty($debug['server_error'])) {
                echo "<strong style='color:#ff6b6b'>Server Error:</strong> " . htmlspecialchars($debug['server_error']) . "<br><br>";
            }
            if (!empty($debug['paystack_response'])) {
                echo "<strong>Paystack Raw Response:</strong><br>";
                echo "<pre>" . htmlspecialchars(json_encode($debug['paystack_response'], JSON_PRETTY_PRINT)) . "</pre>";
            }
            if (!empty($debug['cart_total'])) {
                echo "<br><strong>Cart Total (PHP):</strong> GHS " . number_format($debug['cart_total'], 2);
                echo "<br><strong>Paid Amount (Paystack):</strong> GHS " . number_format($debug['paid_amount'], 2);
                if (isset($debug['amount_match'])) {
                    echo $debug['amount_match'] ? " <span class='success'>Matched</span>" : " <span style='color:#ff6b6b'>Mismatched</span>";
                }
            }
            if (!empty($debug['sql_error'])) {
                echo "<br><br><strong style='color:#ff6b6b'>SQL Error (record_payment):</strong> " . htmlspecialchars($debug['sql_error']);
            }
            ?>
        </div>
    <?php endif; ?>

    <div class="row">
      <button class="btn primary" onclick="location.href='checkout.php'">Try Again</button>
      <button class="btn ghost" onclick="history.back()">Back</button>
    </div>

    <div class="small">
      Need help? 
      <a class="inline" href="mailto:<?php echo $supportEmail; ?>?subject=Payment%20Issue%20(<?php echo $errorCode; ?>)">
        Contact support
      </a>
      <?php if ($DEBUG_MODE): ?>
        <br><br><strong>Tip:</strong> Copy everything in the debug box above.
      <?php endif; ?>
    </div>
  </div>
</body>
</html>