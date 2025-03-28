<?php

class MXRecord extends DNSRecord {
    public function check(): array {
        if (!$this->validateDomain()) {
            return [];
        }

        $records = dns_get_record($this->domain, DNS_MX);
        if ($records) {
            foreach ($records as $record) {
                $this->records[] = [
                    'host' => $record['target'],
                    'priority' => $record['pri']
                ];
            }
        }

        return $this->records;
    }
} 