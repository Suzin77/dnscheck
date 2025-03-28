<?php

class ARecord extends DNSRecord {
    public function check(): array {
        if (!$this->validateDomain()) {
            return [];
        }

        $records = dns_get_record($this->domain, DNS_A);
        if ($records) {
            foreach ($records as $record) {
                $this->records[] = $record['ip'];
            }
        }

        return $this->records;
    }
} 
