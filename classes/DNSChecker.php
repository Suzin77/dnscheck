<?php

class DNSChecker {
    private $domain;

    public function __construct(string $domain) {
        $this->domain = $domain;
    }

    public function checkAll(): array {
        if (empty($this->domain)) {
            return ['error' => 'Nie podano domeny'];
        }

        $a_record = new ARecord($this->domain);
        $mx_record = new MXRecord($this->domain);
        $ns_record = new NSRecord($this->domain);
        $txt_record = new TXTRecord($this->domain);
        $cname_record = new CNAMERecord($this->domain);
        $domain_info = new DomainInfo($this->domain);

        return [
            'domain' => $this->domain,
            'domain_info' => $domain_info->check(),
            'a_records' => $a_record->check(),
            'mx_records' => $mx_record->check(),
            'ns_records' => $ns_record->check(),
            'txt_records' => $txt_record->check(),
            'cname_records' => $cname_record->check()
        ];
    }

    public function getRawWhois() {
        $command = "whois {$this->domain}";
        exec($command, $output, $returnVar);
        return implode("\n", $output);
    }
} 