<?php
$a = "";
$b = "";
$error = "";
$results = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $a = trim($_POST["a"] ?? "");
    $b = trim($_POST["b"] ?? "");

    // Server-side validation: both values must be numeric
    $aNum = filter_var($a, FILTER_VALIDATE_FLOAT);
    $bNum = filter_var($b, FILTER_VALIDATE_FLOAT);

    if ($a === "" || $b === "") {
        $error = "Please enter both numbers.";
    } elseif ($aNum === false || $bNum === false) {
        $error = "Only numbers are allowed.";
    } else {
        $results = [
            "+" => $aNum + $bNum,
            "-" => $aNum - $bNum,
            "×" => $aNum * $bNum,
            "/" => ($bNum != 0) ? $aNum / $bNum : "Undefined (division by zero)",
        ];
    }
}

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8"); }
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
            min-width: 300px;
        }
        h1 { margin-top: 0; font-size: 1.4rem; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input[type=number] {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            box-sizing: border-box;
            border: 1px solid #c5cad1;
            border-radius: 6px;
        }
        button {
            margin-top: 16px;
            padding: 10px 16px;
            border: 0;
            border-radius: 6px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
        }
        .error { color: #b91c1c; margin-top: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { padding: 8px; border-bottom: 1px solid #e3e6ea; text-align: left; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Arithmetic Operations</h1>

        <form method="post" action="">
            <label for="a">First number</label>
            <input type="number" step="any" id="a" name="a" required value="<?= h($a) ?>">

            <label for="b">Second number</label>
            <input type="number" step="any" id="b" name="b" required value="<?= h($b) ?>">

            <button type="submit">Calculate</button>
        </form>

        <?php if ($error): ?>
            <p class="error"><?= h($error) ?></p>
        <?php endif; ?>

        <?php if ($results): ?>
            <table>
                <tr><th>Operation</th><th>Result</th></tr>
                <?php foreach ($results as $op => $value): ?>
                    <tr>
                        <td><?= h($a) ?> <?= h($op) ?> <?= h($b) ?></td>
                        <td><?= h($value) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>