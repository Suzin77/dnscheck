#!/bin/bash

# Sprawdzenie czy podano argument (domenę)
if [ $# -eq 0 ]; then
    echo "Użycie: $0 domena"
    echo "Przykład: $0 example.com"
    exit 1
fi

# Przypisanie domeny do zmiennej
DOMAIN=$1

# Sprawdzenie czy komenda dig jest zainstalowana
if ! command -v dig &> /dev/null; then
    echo "Błąd: Komenda 'dig' nie jest zainstalowana."
    echo "Zainstaluj ją używając: sudo apt-get install dnsutils"
    exit 1
fi

# Wykonanie zapytania DNS o rekord A
echo "Sprawdzanie rekordu A dla domeny: $DOMAIN"
echo "----------------------------------------"

dig +short A $DOMAIN | while read ip; do
    echo "Rekord A: $ip"
done

# Dodatkowe informacje o domenie
echo "----------------------------------------"
echo "Pełne informacje o domenie:"
dig +noall +answer $DOMAIN 