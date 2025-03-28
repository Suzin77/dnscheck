<?php

class DNSRecord {
    protected $domain;
    protected $records = [];

    public function __construct(string $domain) {
        $this->domain = $domain;
    }

    public function getRecords(): array {
        return $this->records;
    }

    protected function validateDomain(): bool {
        return !empty($this->domain);
    }
} 