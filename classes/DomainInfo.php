<?php

class DomainInfo {
    private $domain;
    private $registrar = '';
    private $registrant = '';
    private $creationDate = '';
    private $expirationDate = '';

    public function __construct(string $domain) {
        $this->domain = $domain;
    }

    public function check(): array {
        if (!$this->validateDomain()) {
            return [];
        }

        // Pobieranie informacji WHOIS
        $command = "whois " . escapeshellarg($this->domain);
        exec($command, $output, $return_var);

        if ($return_var === 0 && !empty($output)) {
            $whois = implode("\n", $output);
            
            // Próba wyciągnięcia informacji o rejestratorze
            if (preg_match('/Registrar:\s*(.+)$/m', $whois, $matches)) {
                $this->registrar = trim($matches[1]);
            }
            
            // Próba wyciągnięcia informacji o właścicielu
            if (preg_match('/Registrant Name:\s*(.+)$/m', $whois, $matches)) {
                $this->registrant = trim($matches[1]);
            }
            
            // Data utworzenia domeny
            if (preg_match('/Creation Date:\s*(.+)$/m', $whois, $matches)) {
                $this->creationDate = trim($matches[1]);
            }
            
            // Data wygaśnięcia domeny
            if (preg_match('/Expiration Date:\s*(.+)$/m', $whois, $matches)) {
                $this->expirationDate = trim($matches[1]);
            }
        }

        return [
            'registrar' => $this->registrar,
            'registrant' => $this->registrant,
            'creation_date' => $this->creationDate,
            'expiration_date' => $this->expirationDate
        ];
    }

    private function validateDomain(): bool {
        return !empty($this->domain) && 
               preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/', $this->domain);
    }
} 