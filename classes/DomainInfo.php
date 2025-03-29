<?php

class DomainInfo {
    private $domain;
    private $registrar = '';
    private $registrarInfo = '';
    private $registrant = '';
    private $creationDate = '';
    private $expirationDate = '';
    private $lastModified = '';
    private $status = '';

    public function __construct(string $domain) {
        $this->domain = $domain;
    }

    public function check(): array {
        if (!$this->validateDomain()) {
            return [];
        }

        // Pobieranie informacji WHOIS przez WSL
        $command = "wsl whois " . escapeshellarg($this->domain);
        exec($command, $output, $return_var);

        if ($return_var === 0 && !empty($output)) {
            $whois = implode("\n", $output);
            
            // Próba wyciągnięcia informacji o rejestratorze
            if (preg_match('/REGISTRAR:\s*(.+)$/m', $whois, $matches)) {
                $this->registrar = trim($matches[1]);
            }

            // Pobieranie całego bloku informacji o rejestratorze
            if (preg_match('/REGISTRAR:[\s\S]*?(?=\n\n|\Z)/m', $whois, $matches)) {
                $this->registrarInfo = trim($matches[0]);
            }
            
            // Próba wyciągnięcia informacji o właścicielu
            if (preg_match('/registrant type:\s*(.+)$/m', $whois, $matches)) {
                $this->registrant = trim($matches[1]);
            }
            
            // Data utworzenia domeny
            if (preg_match('/created:\s*(.+)$/m', $whois, $matches)) {
                $this->creationDate = trim($matches[1]);
            }
            
            // Data wygaśnięcia domeny
            if (preg_match('/renewal date:\s*(.+)$/m', $whois, $matches)) {
                $this->expirationDate = trim($matches[1]);
            }

            // Data ostatniej modyfikacji
            if (preg_match('/last modified:\s*(.+)$/m', $whois, $matches)) {
                $this->lastModified = trim($matches[1]);
            }

            // Status domeny
            if (preg_match('/status:\s*(.+)$/m', $whois, $matches)) {
                $this->status = trim($matches[1]);
            } else {
                // Jeśli nie znaleziono statusu, sprawdź czy domena jest aktywna
                if (strpos($whois, 'renewal date:') !== false) {
                    $this->status = 'Aktywna';
                } else {
                    $this->status = 'Status nieznany';
                }
            }
        }

        return [
            'registrar' => [
                'name' => $this->registrar,
                'info' => $this->registrarInfo
            ],
            'registrant' => $this->registrant,
            'dates' => [
                'creation' => $this->creationDate,
                'expiration' => $this->expirationDate,
                'last_modified' => $this->lastModified
            ],
            'status' => $this->status
        ];
    }

    private function validateDomain(): bool {
        return !empty($this->domain) && 
               preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/', $this->domain);
    }
} 