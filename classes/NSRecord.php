<?php

class NSRecord extends DNSRecord {
    public function check(): array {
        if (!$this->validateDomain()) {
            return [];
        }

        $records = dns_get_record($this->domain, DNS_NS);
        if ($records) {
            foreach ($records as $record) {
                $this->records[] = $record['target'];
            }
        }

        return $this->records;
    }
} 