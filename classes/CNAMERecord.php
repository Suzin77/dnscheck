<?php

class CNAMERecord extends DNSRecord {
    public function check(): array {
        if (!$this->validateDomain()) {
            return [];
        }

        // Sprawdzanie rekordu CNAME dla domeny
        $command = "dig +short CNAME " . escapeshellarg($this->domain);
        exec($command, $output, $return_var);

        if ($return_var === 0 && !empty($output)) {
            foreach ($output as $record) {
                $this->records[] = trim($record, '.');
            }
        }

        // Sprawdzanie rekordu CNAME dla domeny głównej
        $root_domain = preg_replace('/^www\./', '', $this->domain);
        if ($root_domain !== $this->domain) {
            $command = "dig +short CNAME " . escapeshellarg($root_domain);
            exec($command, $output, $return_var);

            if ($return_var === 0 && !empty($output)) {
                foreach ($output as $record) {
                    $this->records[] = trim($record, '.');
                }
            }
        }

        // Usuwanie duplikatów
        $this->records = array_unique($this->records);
        return array_values($this->records);
    }
} 