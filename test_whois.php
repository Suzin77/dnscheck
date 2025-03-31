<?php

// Domena do sprawdzenia
$domain = 'google.com';

// Wykonaj komendę whois
$command = "wsl whois $domain";
exec($command, $output, $returnVar);

// Zapisz wynik do pliku
$result = implode("\n", $output);
file_put_contents('whois_output.txt', $result);

echo "Wynik komendy whois został zapisany do pliku whois_output.txt.";

?> 