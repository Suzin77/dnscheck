<?php

class TXTRecord extends DNSRecord {
    public function check(): array {
        if (!$this->validateDomain()) {
            return [];
        }

        // Sprawdzanie rekordu TXT dla domeny
        $command = "dig +short TXT " . escapeshellarg($this->domain);
        exec($command, $output, $return_var);

        if ($return_var === 0 && !empty($output)) {
            foreach ($output as $record) {
                // Obsługa rekordów TXT z wieloma częściami
                $parts = explode('" "', $record);
                foreach ($parts as $part) {
                    // Usuwamy cudzysłowy z początku i końca każdej części
                    $part = trim($part, '"');
                    if (!empty($part)) {
                        $this->records[] = $part;
                    }
                }
            }
        }

        // Sprawdzanie rekordu TXT dla domeny głównej
        $root_domain = preg_replace('/^www\./', '', $this->domain);
        if ($root_domain !== $this->domain) {
            $command = "dig +short TXT " . escapeshellarg($root_domain);
            exec($command, $output, $return_var);

            if ($return_var === 0 && !empty($output)) {
                foreach ($output as $record) {
                    // Obsługa rekordów TXT z wieloma częściami
                    $parts = explode('" "', $record);
                    foreach ($parts as $part) {
                        // Usuwamy cudzysłowy z początku i końca każdej części
                        $part = trim($part, '"');
                        if (!empty($part)) {
                            $this->records[] = $part;
                        }
                    }
                }
            }
        }

        // Usuwanie duplikatów
        $this->records = array_unique($this->records);
        return array_values($this->records);
    }
} 