<?php
/**
 * index.php
 * ---------------------------------------------------------------------------
 * A small PHP web app that:
 *   1. Shows a form asking the user for two numbers.
 *   2. Validates the input (numbers only), both in the browser and on the server.
 *   3. Displays the results of four arithmetic operations: +, -, ×, /.
 *
 * The same file handles both showing the form (GET request) and processing it
 * (POST request), so no other files are needed.
 * ---------------------------------------------------------------------------
 */

// ---------------------------------------------------------------------------
// 1. INITIAL VARIABLES
// ---------------------------------------------------------------------------
// $a and $b hold the raw text the user typed. They start empty so the form is
// blank the first time the page loads.
$a = "";
$b = "";

// $error holds a message to show the user if validation fails. An empty string
// means "no error".
$error = "";

// $results will become an array of operation => result once the input is valid.
// While it is null, the results table is not displayed.
$results = null;

// ---------------------------------------------------------------------------
// 2. HANDLE THE FORM SUBMISSION
// ---------------------------------------------------------------------------
// $_SERVER["REQUEST_METHOD"] tells us how the page was requested.
// "GET"  = the user just opened the page (show an empty form).
// "POST" = the user clicked the Calculate button (process the data).
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Read the submitted values. The "?? ''" part (null coalescing operator)
    // gives an empty string if the field is missing, which avoids PHP
    // "undefined index" warnings. trim() removes leading/trailing spaces.
    $a = trim($_POST["a"] ?? "");
    $b = trim($_POST["b"] ?? "");

    // -----------------------------------------------------------------------
    // 2a. SERVER-SIDE VALIDATION
    // -----------------------------------------------------------------------
    // Browser checks (type="number") can be bypassed, so we ALWAYS validate on
    // the server too. filter_var() with FILTER_VALIDATE_FLOAT returns:
    //   - the value converted to a float if it is a valid number
    //   - false if it is not a valid number
    $aNum = filter_var($a, FILTER_VALIDATE_FLOAT);
    $bNum = filter_var($b, FILTER_VALIDATE_FLOAT);

    if ($a === "" || $b === "") {
        // At least one field was left empty.
        $error = "Please enter both numbers.";

    } elseif ($aNum === false || $bNum === false) {
        // We use the strict comparison (===) with false because 0 is a valid
        // number but would count as "false" with a loose comparison (==).
        $error = "Only numbers are allowed.";

    } else {
        // -------------------------------------------------------------------
        // 2b. PERFORM THE FOUR OPERATIONS
        // -------------------------------------------------------------------
        // Both inputs are valid numbers, so we can calculate safely.
        // The results are stored in an associative array: the key is the
        // operator symbol, the value is the result. This lets us loop over
        // them later when building the table (no repeated HTML).
        $results = [
            "+" => $aNum + $bNum,   // Addition
            "-" => $aNum - $bNum,   // Subtraction
            "×" => $aNum * $bNum,   // Multiplication

            // Division: dividing by zero is not allowed (PHP would throw a
            // DivisionByZeroError), so we check $bNum first using the ternary
            // operator:  condition ? value_if_true : value_if_false
            "/" => ($bNum != 0) ? $aNum / $bNum : "Undefined (division by zero)",
        ];
    }
}

// ---------------------------------------------------------------------------
// 3. HELPER FUNCTION
// ---------------------------------------------------------------------------
/**
 * h() - short for "HTML escape".
 *
 * Converts special characters (< > & " ') into HTML entities before printing
 * them on the page. This prevents XSS (cross-site scripting) attacks, where
 * someone could type malicious HTML or JavaScript into a form field and have
 * it run in the browser. Any user-provided value printed in HTML should go
 * through this function.
 *
 * @param mixed $v The value to escape (cast to string first).
 * @return string  The safe, escaped string.
 */
function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Makes the page scale properly on phones and small screens. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator</title>

    <style>
        /* Page background and centering of the card. */
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            justify-content: center;   /* centers the card horizontally */
            padding: 40px 16px;
        }

        /* The white box that contains the form and results. */
        .card {
            background: #fff;
            padding: 24px 32px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-width: 300px;
        }

        h1 { margin-top: 0; font-size: 1.4rem; }

        /* Field labels sit above their inputs. */
        label { display: block; margin-top: 12px; font-weight: bold; }

        /* Number input boxes fill the full width of the card. */
        input[type=number] {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            box-sizing: border-box;    /* includes padding in the width */
            border: 1px solid #c5cad1;
            border-radius: 6px;
        }

        /* The Calculate button. */
        button {
            margin-top: 16px;
            padding: 10px 16px;
            border: 0;
            border-radius: 6px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
        }

        /* Validation error message (red text). */
        .error { color: #b91c1c; margin-top: 12px; }

        /* Results table. */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { padding: 8px; border-bottom: 1px solid #e3e6ea; text-align: left; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Arithmetic Operations</h1>

        <!--
            THE INPUT FORM
            method="post" : sends the data in the request body (not the URL).
            action=""     : submits back to this same page, which is why the
                            PHP block at the top of the file can process it.
        -->
        <form method="post" action="">

            <label for="a">First number</label>
            <!--
                type="number"  : browser only accepts numeric characters.
                step="any"     : allows decimals (default would only allow
                                 whole numbers).
                required       : browser blocks submitting an empty field.
                value="..."    : refills the field with the previous entry so
                                 the user doesn't have to retype it. Escaped
                                 with h() for safety.
            -->
            <input type="number" step="any" id="a" name="a" required value="<?= h($a) ?>">

            <label for="b">Second number</label>
            <input type="number" step="any" id="b" name="b" required value="<?= h($b) ?>">

            <button type="submit">Calculate</button>
        </form>

        <!--
            ERROR MESSAGE
            Only printed when $error is not empty (i.e., validation failed).
            "<?= ... ?>" is shorthand for "<?php echo ... ?>".
        -->
        <?php if ($error): ?>
            <p class="error"><?= h($error) ?></p>
        <?php endif; ?>

        <!--
            RESULTS TABLE
            Only printed after a successful calculation ($results is set).
        -->
        <?php if ($results): ?>
            <table>
                <tr><th>Operation</th><th>Result</th></tr>

                <!--
                    Loop over the results array. On each pass:
                      $op    = the operator symbol (+, -, ×, /)
                      $value = the result of that operation
                    This builds one table row per operation, so the HTML is
                    written only once instead of four times.
                -->
                <?php foreach ($results as $op => $value): ?>
                    <tr>
                        <!-- Shows the full expression, e.g. "20 + 5" -->
                        <td><?= h($a) ?> <?= h($op) ?> <?= h($b) ?></td>
                        <!-- Shows the computed result, e.g. "25" -->
                        <td><?= h($value) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>