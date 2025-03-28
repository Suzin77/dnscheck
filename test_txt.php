<?php
$domain = 'google.com';
$records = dns_get_record($domain, DNS_TXT);
echo "Rekordy TXT dla $domain:\n";
print_r($records);

// Dodatkowe sprawdzenie przez nslookup
echo "\nSprawdzenie przez nslookup:\n";
$command = "nslookup -type=TXT " . escapeshellarg($domain);
exec($command, $output, $return_var);
print_r($output); 