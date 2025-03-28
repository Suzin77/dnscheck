<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprawdzanie DNS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sprawdzanie informacji DNS</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="domain">Wprowadź domenę:</label>
                <input type="text" id="domain" name="domain" placeholder="np. example.com" required>
            </div>
            <button type="submit">Sprawdź</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $domain = $_POST["domain"];
            
            // Sprawdzenie czy domena jest poprawna
            if (filter_var($domain, FILTER_VALIDATE_DOMAIN)) {
                echo "<div class='result'>";
                echo "<h2>Wyniki dla domenyy: $domain</h2>";
                
                // Pobieranie rekordu A
                $records = dns_get_record($domain, DNS_A);
                if ($records) {
                    echo "<h3>Rekordy A:</h3>";
                    echo "<ul>";
                    foreach ($records as $record) {
                        echo "<li>IP: " . $record['ip'] . "</li>";
                    }
                    echo "</ul>";
                }

                // Pobieranie rekordu MX
                $mx_records = dns_get_record($domain, DNS_MX);
                if ($mx_records) {
                    echo "<h3>Rekordy MX:</h3>";
                    echo "<ul>";
                    foreach ($mx_records as $record) {
                        echo "<li>Priorytet: " . $record['pri'] . ", Host: " . $record['target'] . "</li>";
                    }
                    echo "</ul>";
                }

                // Pobieranie rekordu NS
                $ns_records = dns_get_record($domain, DNS_NS);
                if ($ns_records) {
                    echo "<h3>Serwery nazw (NS):</h3>";
                    echo "<ul>";
                    foreach ($ns_records as $record) {
                        echo "<li>" . $record['target'] . "</li>";
                    }
                    echo "</ul>";
                }

                echo "</div>";
            } else {
                echo "<div class='error'>Nieprawidłowy format domeny!</div>";
            }
        }
        ?>
    </div>
</body>
</html> 