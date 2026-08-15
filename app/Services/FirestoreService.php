<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirestoreService
{
    protected $projectId;
    protected $serviceAccountPath;
    protected $cachedToken = null;
    protected $tokenExpiry = 0;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id') ?: env('FIREBASE_PROJECT_ID');
        $this->serviceAccountPath = config('services.firebase.service_account_path') ?: env('FIREBASE_SERVICE_ACCOUNT_PATH');
    }

    protected function getAccessToken(): ?string
    {
        if ($this->cachedToken && time() < $this->tokenExpiry - 60) {
            return $this->cachedToken;
        }

        if (! $this->serviceAccountPath || ! file_exists($this->serviceAccountPath)) {
            Log::error('Firestore service account file not found: ' . $this->serviceAccountPath);
            return null;
        }

        $sa = json_decode(file_get_contents($this->serviceAccountPath), true);
        if (! $sa) {
            Log::error('Invalid service account JSON');
            return null;
        }

        $now = time();
        $jwtHeader = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claimSet = [
            'iss' => $sa['client_email'],
            'scope' => 'https://www.googleapis.com/auth/datastore https://www.googleapis.com/auth/cloud-platform',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $jwtClaim = $this->base64UrlEncode(json_encode($claimSet));
        $unsigned = $jwtHeader . '.' . $jwtClaim;
        $signature = null;
        openssl_sign($unsigned, $signature, $sa['private_key'], OPENSSL_ALGO_SHA256);
        $signed = $unsigned . '.' . $this->base64UrlEncode($signature);

        $resp = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $signed,
        ]);

        if (! $resp->ok()) {
            Log::error('Failed to exchange JWT for access token', ['status' => $resp->status(), 'body' => $resp->body()]);
            return null;
        }

        $data = $resp->json();
        $this->cachedToken = $data['access_token'] ?? null;
        $this->tokenExpiry = $now + ($data['expires_in'] ?? 3600);
        return $this->cachedToken;
    }

    protected function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function toFirestoreFields(array $data): array
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if (is_int($value) || (is_string($value) && ctype_digit($value))) {
                $fields[$key] = ['integerValue' => (string) $value];
            } elseif (is_bool($value)) {
                $fields[$key] = ['booleanValue' => $value];
            } elseif ($value instanceof \DateTimeInterface) {
                $fields[$key] = ['timestampValue' => $value->format('Y-m-d\TH:i:s.u\Z')];
            } elseif (is_null($value)) {
                $fields[$key] = ['nullValue' => null];
            } else {
                $fields[$key] = ['stringValue' => (string) $value];
            }
        }
        return $fields;
    }

    public function setDocument(string $collection, string $docId, array $data): bool
    {
        $token = $this->getAccessToken();
        if (! $token) return false;

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$docId}";
        $body = ['fields' => $this->toFirestoreFields($data)];

        $resp = Http::withToken($token)->patch($url, $body);
        if (! $resp->successful()) {
            Log::error('Failed to write Firestore document', ['url' => $url, 'status' => $resp->status(), 'body' => $resp->body()]);
            return false;
        }
        return true;
    }

    public function deleteDocument(string $collection, string $docId): bool
    {
        $token = $this->getAccessToken();
        if (! $token) return false;

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$docId}";
        $resp = Http::withToken($token)->delete($url);
        if (! $resp->successful()) {
            Log::error('Failed to delete Firestore document', ['url' => $url, 'status' => $resp->status(), 'body' => $resp->body()]);
            return false;
        }
        return true;
    }
}
