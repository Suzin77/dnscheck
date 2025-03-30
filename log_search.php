<?php

// Pobierz dane z żądania
$domain = $_POST['domain'] ?? 'unknown';
$ipAddress = $_SERVER['REMOTE_ADDR'];
$referrer = $_SERVER['HTTP_REFERER'] ?? 'unknown';
$date = date('Y-m-d H:i:s');

// Przygotuj dane do zapisania
$logEntry = "Data: $date, IP: $ipAddress, Referrer: $referrer, Domena: $domain\n";

// Zapisz dane do pliku log.txt w katalogu /logs
file_put_contents('logs/log.txt', $logEntry, FILE_APPEND);

?> 