<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Provider SMS (twilio, vonage, infobip, ou local pour debug)
     */
    protected $provider;
    
    /**
     * URL de base pour le tracking
     */
    protected $trackingUrl;

    public function __construct()
    {
        $this->provider = config('services.sms.provider', 'log');
        $this->trackingUrl = config('app.url', 'http://127.0.0.1:8000') . '/tracking';
    }

    /**
     * Envoyer un SMS de tracking à l'expéditeur
     */
    public function sendToExpediteur(string $phone, string $qrCode, string $produitNom): bool
    {
        $message = "📦 مرحبا! الكولي متاعك \"{$produitNom}\" تسجلت.\n\n" .
                   "🔑 كود التتبع: {$qrCode}\n\n" .
                   "📍 تابع الكولي متاعك من هنا:\n{$this->trackingUrl}?code={$qrCode}\n\n" .
                   "- Delivery Platform";

        return $this->send($phone, $message);
    }

    /**
     * Envoyer un SMS de tracking au destinataire
     */
    public function sendToDestinataire(string $phone, string $qrCode, string $produitNom, string $expediteurNom = null): bool
    {
        $fromText = $expediteurNom ? " من {$expediteurNom}" : "";
        
        $message = "📦 مرحبا! فما كولي جايتك{$fromText}: \"{$produitNom}\"\n\n" .
                   "🔑 كود التتبع: {$qrCode}\n\n" .
                   "📍 تابع الكولي متاعك من هنا:\n{$this->trackingUrl}?code={$qrCode}\n\n" .
                   "- Delivery Platform";

        return $this->send($phone, $message);
    }

    /**
     * Envoyer un SMS générique
     */
    public function send(string $phone, string $message): bool
    {
        // Nettoyer le numéro de téléphone
        $phone = $this->cleanPhoneNumber($phone);
        
        if (empty($phone)) {
            Log::warning('SMS non envoyé: numéro de téléphone invalide');
            return false;
        }

        try {
            switch ($this->provider) {
                case 'twilio':
                    return $this->sendViaTwilio($phone, $message);
                    
                case 'vonage':
                    return $this->sendViaVonage($phone, $message);
                    
                case 'infobip':
                    return $this->sendViaInfobip($phone, $message);
                
                case 'orange_tn':
                    return $this->sendViaOrangeTn($phone, $message);

                case 'textflow':
                    return $this->sendViaTextFlow($phone, $message);
                    
                case 'log':
                default:
                    // Mode debug - juste logger le SMS
                    return $this->logSms($phone, $message);
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS: ' . $e->getMessage(), [
                'phone' => $phone,
                'provider' => $this->provider
            ]);
            return false;
        }
    }

    /**
     * Liste des codes pays
     */
    protected function getCountryCodes(): array
    {
        return [
            // Afrique du Nord
            'TN' => '+216',  // Tunisie
            'DZ' => '+213',  // Algérie
            'MA' => '+212',  // Maroc
            'LY' => '+218',  // Libye
            'EG' => '+20',   // Égypte
            
            // Moyen-Orient
            'SA' => '+966',  // Arabie Saoudite
            'AE' => '+971',  // Émirats Arabes Unis
            'QA' => '+974',  // Qatar
            'KW' => '+965',  // Koweït
            'BH' => '+973',  // Bahreïn
            'OM' => '+968',  // Oman
            'JO' => '+962',  // Jordanie
            'LB' => '+961',  // Liban
            'SY' => '+963',  // Syrie
            'IQ' => '+964',  // Irak
            'PS' => '+970',  // Palestine
            'YE' => '+967',  // Yémen
            
            // Europe
            'FR' => '+33',   // France
            'DE' => '+49',   // Allemagne
            'IT' => '+39',   // Italie
            'ES' => '+34',   // Espagne
            'GB' => '+44',   // Royaume-Uni
            'BE' => '+32',   // Belgique
            'CH' => '+41',   // Suisse
            'NL' => '+31',   // Pays-Bas
            'PT' => '+351',  // Portugal
            'AT' => '+43',   // Autriche
            'PL' => '+48',   // Pologne
            'SE' => '+46',   // Suède
            'NO' => '+47',   // Norvège
            'DK' => '+45',   // Danemark
            'FI' => '+358',  // Finlande
            'GR' => '+30',   // Grèce
            'TR' => '+90',   // Turquie
            
            // Amérique
            'US' => '+1',    // États-Unis
            'CA' => '+1',    // Canada
            'MX' => '+52',   // Mexique
            'BR' => '+55',   // Brésil
            'AR' => '+54',   // Argentine
            
            // Afrique
            'SN' => '+221',  // Sénégal
            'CI' => '+225',  // Côte d'Ivoire
            'CM' => '+237',  // Cameroun
            'NG' => '+234',  // Nigeria
            'ZA' => '+27',   // Afrique du Sud
            'KE' => '+254',  // Kenya
            'GH' => '+233',  // Ghana
            'ML' => '+223',  // Mali
            'MR' => '+222',  // Mauritanie
            
            // Asie
            'CN' => '+86',   // Chine
            'JP' => '+81',   // Japon
            'KR' => '+82',   // Corée du Sud
            'IN' => '+91',   // Inde
            'PK' => '+92',   // Pakistan
            'MY' => '+60',   // Malaisie
            'SG' => '+65',   // Singapour
            'TH' => '+66',   // Thaïlande
            'ID' => '+62',   // Indonésie
            'PH' => '+63',   // Philippines
            'VN' => '+84',   // Vietnam
        ];
    }

    /**
     * Détecter le code pays à partir du numéro
     */
    protected function detectCountryCode(string $phone): ?string
    {
        $countryCodes = $this->getCountryCodes();
        
        // Trier par longueur décroissante pour matcher les codes les plus longs d'abord
        $codes = array_values($countryCodes);
        usort($codes, fn($a, $b) => strlen($b) - strlen($a));
        
        foreach ($codes as $code) {
            $codeWithoutPlus = ltrim($code, '+');
            if (str_starts_with($phone, $code) || str_starts_with($phone, $codeWithoutPlus)) {
                return $code;
            }
        }
        
        return null;
    }

    /**
     * Nettoyer le numéro de téléphone
     */
    protected function cleanPhoneNumber(string $phone): string
    {
        // Supprimer tous les caractères sauf les chiffres et le +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Si le numéro a déjà un code pays valide, le retourner tel quel
        if (substr($phone, 0, 1) === '+') {
            $detectedCode = $this->detectCountryCode($phone);
            if ($detectedCode) {
                return $phone;
            }
        }
        
        // Vérifier si le numéro commence par un code pays sans le +
        $detectedCode = $this->detectCountryCode($phone);
        if ($detectedCode) {
            return '+' . ltrim($phone, '+');
        }
        
        // Si le numéro commence par 0, c'est un numéro local
        // Par défaut, on utilise la Tunisie (+216)
        if (substr($phone, 0, 1) === '0') {
            $phone = '+216' . substr($phone, 1);
        }
        // Si pas de code pays détecté, ajouter +216 pour la Tunisie
        elseif (substr($phone, 0, 1) !== '+') {
            // Vérifier la longueur pour les numéros tunisiens (8 chiffres)
            if (strlen($phone) === 8) {
                $phone = '+216' . $phone;
            } else {
                // Supposer que c'est tunisien par défaut
                $phone = '+216' . $phone;
            }
        }
        
        return $phone;
    }

    /**
     * Mode debug - Logger le SMS au lieu de l'envoyer
     */
    protected function logSms(string $phone, string $message): bool
    {
        Log::info('📱 SMS (mode debug)', [
            'to' => $phone,
            'message' => $message,
            'tracking_url' => $this->trackingUrl
        ]);
        
        // Aussi stocker en session pour affichage dans l'interface admin
        $smsLogs = session('sms_logs', []);
        $smsLogs[] = [
            'phone' => $phone,
            'message' => $message,
            'sent_at' => now()->format('d/m/Y H:i:s')
        ];
        session(['sms_logs' => array_slice($smsLogs, -10)]); // Garder les 10 derniers
        
        return true;
    }

    /**
     * Envoyer via Twilio
     */
    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');

        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To' => $phone,
                'Body' => $message,
            ]);

        if ($response->successful()) {
            Log::info('SMS envoyé via Twilio', ['to' => $phone]);
            return true;
        }

        Log::error('Erreur Twilio', ['response' => $response->json()]);
        return false;
    }

    /**
     * Envoyer via Vonage (Nexmo)
     */
    protected function sendViaVonage(string $phone, string $message): bool
    {
        $apiKey = config('services.vonage.key');
        $apiSecret = config('services.vonage.secret');
        $from = config('services.vonage.from', 'DeliveryApp');

        $response = Http::post('https://rest.nexmo.com/sms/json', [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'from' => $from,
            'to' => $phone,
            'text' => $message,
        ]);

        if ($response->successful() && ($response->json()['messages'][0]['status'] ?? '1') === '0') {
            Log::info('SMS envoyé via Vonage', ['to' => $phone]);
            return true;
        }

        Log::error('Erreur Vonage', ['response' => $response->json()]);
        return false;
    }

    /**
     * Envoyer via Infobip
     */
    protected function sendViaInfobip(string $phone, string $message): bool
    {
        $apiKey = config('services.infobip.key');
        $baseUrl = config('services.infobip.base_url');
        $from = config('services.infobip.from', 'DeliveryApp');

        $response = Http::withHeaders([
            'Authorization' => 'App ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$baseUrl}/sms/2/text/advanced", [
            'messages' => [
                [
                    'from' => $from,
                    'destinations' => [['to' => $phone]],
                    'text' => $message,
                ]
            ]
        ]);

        if ($response->successful()) {
            Log::info('SMS envoyé via Infobip', ['to' => $phone]);
            return true;
        }

        Log::error('Erreur Infobip', ['response' => $response->json()]);
        return false;
    }

    /**
     * Envoyer via Orange Tunisie (SMS API)
     */
    protected function sendViaOrangeTn(string $phone, string $message): bool
    {
        // Configuration spécifique Orange Tunisie
        $apiUrl = config('services.orange_tn.api_url');
        $apiKey = config('services.orange_tn.api_key');
        $senderId = config('services.orange_tn.sender_id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'outboundSMSMessageRequest' => [
                'address' => 'tel:' . $phone,
                'senderAddress' => 'tel:' . $senderId,
                'outboundSMSTextMessage' => [
                    'message' => $message
                ]
            ]
        ]);

        if ($response->successful()) {
            Log::info('SMS envoyé via Orange TN', ['to' => $phone]);
            return true;
        }

        Log::error('Erreur Orange TN', ['response' => $response->json()]);
        return false;
    }

    /**
     * Envoyer via TextFlow (textflow.me)
     * API directe: POST https://textflow.me/api/send-sms
     */
    protected function sendViaTextFlow(string $phone, string $message): bool
    {
        $apiKey = config('services.textflow.api_key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://textflow.me/api/send-sms', [
            'phone_number' => $phone,
            'text' => $message,
        ]);

        $data = $response->json();

        if ($response->successful() && ($data['ok'] ?? false) === true) {
            Log::info('SMS envoyé via TextFlow', [
                'to' => $phone,
                'price' => $data['data']['price'] ?? 'N/A',
            ]);
            return true;
        }

        Log::error('Erreur TextFlow', [
            'response' => $data,
            'status' => $response->status(),
        ]);
        return false;
    }
}
