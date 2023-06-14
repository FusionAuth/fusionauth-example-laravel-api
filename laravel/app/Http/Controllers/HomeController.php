<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\View\View;

use function auth, config, url, http_build_query, session, urlencode;

class HomeController extends Controller
{

    public function __invoke(): View
    {
        return view('welcome', $this->getViewParams());
    }

    protected function getViewParams(): array
    {
        $data = [
            'baseUrl' => config('app.fusionauth.url'),
        ];

        if (auth()->user()) {
            $data['logoutUrl'] = $this->buildUri($data['baseUrl'], '/app/logout/', [
                'redirect_uri' => url('/logout'),
            ]);
            return $data;
        }

        $state = Str::random(40);
        session()->put('state', $state);
        $data['loginUrl'] = $this->buildUri($data['baseUrl'], '/app/login/', [
            'redirect_uri' => url('/login'),
            'state'        => $state,
            'scope'        => 'openid offline_access',
        ]);
        return $data;
    }

    private function buildUri(string $baseUri, string $path, array $params): string
    {
        return $baseUri . $path . urlencode(config('app.fusionauth.client_id')) . '?' . http_build_query($params);
    }

}
