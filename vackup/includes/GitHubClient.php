<?php
/**
 * GitHubClient - Simple GitHub API wrapper for Vackup
 * Uses Personal Access Token for authentication (simple, no OAuth flow)
 */

class GitHubClient
{
    private $token;
    private $apiUrl = 'https://api.github.com';

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Create a release/tag on GitHub
     */
    public function createRelease($repo, $version, $name, $body = '')
    {
        $tagName = "v{$version}";
        
        $data = [
            'tag_name' => $tagName,
            'name' => "v{$version} - {$name}",
            'body' => $body ?: "Release {$version}: {$name}",
            'draft' => false,
            'prerelease' => false
        ];

        $response = $this->request("POST", "/repos/{$repo}/releases", $data);
        
        if (isset($response['id'])) {
            return [
                'success' => true,
                'release_id' => $response['id'],
                'tag_name' => $tagName,
                'html_url' => $response['html_url'] ?? ''
            ];
        }

        return [
            'success' => false,
            'error' => $response['message'] ?? 'Unknown error creating release'
        ];
    }

    /**
     * Get repository info
     */
    public function getRepo($repo)
    {
        return $this->request("GET", "/repos/{$repo}");
    }

    /**
     * List releases for a repository
     */
    public function listReleases($repo, $perPage = 10)
    {
        return $this->request("GET", "/repos/{$repo}/releases?per_page={$perPage}");
    }

    /**
     * Verify token is valid
     */
    public function verifyToken()
    {
        $response = $this->request("GET", "/user");
        if (isset($response['login'])) {
            return [
                'valid' => true,
                'username' => $response['login'],
                'name' => $response['name'] ?? $response['login']
            ];
        }
        return ['valid' => false, 'error' => $response['message'] ?? 'Invalid token'];
    }

    /**
     * List user's repositories
     */
    public function listRepos($perPage = 30)
    {
        return $this->request("GET", "/user/repos?per_page={$perPage}&sort=updated");
    }

    /**
     * Make API request
     */
    private function request($method, $endpoint, $data = null)
    {
        $url = $this->apiUrl . $endpoint;
        
        $headers = [
            'Accept: application/vnd.github.v3+json',
            'Authorization: Bearer ' . $this->token,
            'User-Agent: Vackup-Platform'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'error' => $error];
        }

        $decoded = json_decode($response, true);
        
        // Add HTTP code for debugging
        if (is_array($decoded)) {
            $decoded['_http_code'] = $httpCode;
        }

        return $decoded;
    }
}
