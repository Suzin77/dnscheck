<?php
header('Content-Type: application/json');

require_once 'classes/DNSRecord.php';
require_once 'classes/ARecord.php';
require_once 'classes/MXRecord.php';
require_once 'classes/NSRecord.php';
require_once 'classes/TXTRecord.php';
require_once 'classes/CNAMERecord.php';
require_once 'classes/DomainInfo.php';
require_once 'classes/DNSChecker.php';
require_once 'log_search.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = $_POST['domain'] ?? 'unknown';
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $referrer = $_SERVER['HTTP_REFERER'] ?? 'unknown';
    $date = date('Y-m-d H:i:s');

    $logEntry = "Data: $date, IP: $ipAddress, Referrer: $referrer, Domena: $domain\n";
    file_put_contents('logs/log.txt', $logEntry, FILE_APPEND);

    $checker = new DNSChecker($domain);
    echo json_encode($checker->checkAll());
} else {
    echo json_encode(['error' => 'Nieprawidłowa metoda żądania']);
} 