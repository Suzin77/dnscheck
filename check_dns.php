<?php
header('Content-Type: application/json');

require_once 'classes/DNSRecord.php';
require_once 'classes/ARecord.php';
require_once 'classes/MXRecord.php';
require_once 'classes/NSRecord.php';
require_once 'classes/TXTRecord.php';
require_once 'classes/DNSChecker.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = $_POST['domain'] ?? '';
    $checker = new DNSChecker($domain);
    echo json_encode($checker->checkAll());
} else {
    echo json_encode(['error' => 'Nieprawidłowa metoda żądania']);
} 