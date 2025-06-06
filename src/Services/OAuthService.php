<?php

namespace Sumup\Laravel\Services;

use Illuminate\Support\Facades\Http;

class OAuthService
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct($clientId, $clientSecret, $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    public function getAuthUrl(array $scopes = ['payments'])
    {
        $query = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'scope' => implode(' ', $scopes),
        ]);

        return "https://web.sumup.com/oauth/authorize?{$query}";
    }

    public function getAccessToken($code)
    {
        return Http::asForm()->post('https://api.sumup.com/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
        ])->json();
    }

    public function refreshToken($refreshToken)
    {
        return Http::asForm()->post('https://api.sumup.com/token', [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
        ])->json();
    }
}
