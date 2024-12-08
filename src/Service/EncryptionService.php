<?php
namespace App\Service;

use Psr\Log\LoggerInterface;

class EncryptionService
{
    private string $cipher = 'aes-256-cbc';
    private string $key;
    private int $ivLength;

    public function __construct(string $encryption_key, private LoggerInterface $logger)
    {
        // La clé de chiffrement doit avoir 256 bits (32 caractères pour AES-256)
        $this->key = hash('sha256', $encryption_key, true);
        $this->ivLength = openssl_cipher_iv_length($this->cipher);
    }

    public function encrypt(?string $data): string
    {
        $iv = openssl_random_pseudo_bytes($this->ivLength);
        $encryptedData = openssl_encrypt($data, $this->cipher, $this->key, 0, $iv);
        
        if ($encryptedData === false) {
            throw new \RuntimeException('Chiffrement des données échoué.');
        }

        return base64_encode($iv . $encryptedData);
    }

    public function decrypt(string $encryptedData): ?string
{
    // 1. Décoder les données encodées en base64
    $encryptedData = base64_decode($encryptedData);

    // Journaliser la longueur des données décodées
    // $this->logger->error('Taille des données décodées : ' . strlen($encryptedData));
    
    // 2. Si la chaîne décodée est invalide ou trop courte, retourner null
    if ($encryptedData === false || strlen($encryptedData) < $this->ivLength) {
        // $this->logger->error('Données chiffrées invalides ou trop courtes');
        return null;  // Les données ne sont pas valides ou l'IV n'est pas complet
    }

    // 3. Extraire l'IV des premiers $this->ivLength octets
    $iv = substr($encryptedData, 0, $this->ivLength);
    // $this->logger->error('IV extrait : ' . bin2hex($iv));

    // 4. Extraire le texte chiffré à partir du reste des données
    $cipherText = substr($encryptedData, $this->ivLength);
    // $this->logger->error('Texte chiffré extrait : ' . bin2hex($cipherText));

    // 5. Déchiffrer le texte chiffré en utilisant l'IV
    $decryptedData = openssl_decrypt($cipherText, $this->cipher, $this->key, 0, $iv);
    
    // Journaliser le résultat du déchiffrement
    if ($decryptedData === false) {
        // $this->logger->error('Déchiffrement échoué');
        return null;
    }

    // $this->logger->error('Données déchiffrées : ' . $decryptedData);

    // 7. Retourner les données déchiffrées
    return $decryptedData;
}

    
}