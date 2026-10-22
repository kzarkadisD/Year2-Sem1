<?php
// Declare two variables and assign values to them
$a = 20;
$b = 5;

// Compute the results of the four operations
$sum        = $a + $b;
$difference = $a - $b;
$product    = $a * $b;
$quotient   = ($b != 0) ? $a / $b : "Undefined (division by zero)";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            justify-content: center;
            padding: 40px 16px;
        }
        .card {
            background: #fff;
            padding: 24px 32px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-width: 280px;
        }
        h1 { margin-top: 0; font-size: 1.4rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        td, th { padding: 8px; border-bottom: 1px solid #e3e6ea; text-align: left; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Arithmetic Operations</h1>
        <p>Variable <strong>a</strong> = <?= $a ?> &nbsp;|&nbsp; Variable <strong>b</strong> = <?= $b ?></p>

        <table>
            <tr><th>Operation</th><th>Result</th></tr>
            <tr><td><?= $a ?> + <?= $b ?></td><td><?= $sum ?></td></tr>
            <tr><td><?= $a ?> - <?= $b ?></td><td><?= $difference ?></td></tr>
            <tr><td><?= $a ?> × <?= $b ?></td><td><?= $product ?></td></tr>
            <tr><td><?= $a ?> / <?= $b ?></td><td><?= $quotient ?></td></tr>
        </table>
    </div>
</body>
</html>