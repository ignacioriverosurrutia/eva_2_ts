<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;
use Illuminate\Http\Client\RequestException;

class Uf extends Component
{
    public $valor;

    public function __construct()
    {
        try {
            $response = Http::timeout(5)->get('https://mindicador.cl/api');
            $this->valor = $response->successful()
                ? $response->json()['uf']['valor']
                : 'No disponible';
        } catch (\Exception $e) {
            $this->valor = 'Error de conexión';
        }
    }

    public function render()
    {
        return view('components.uf');
    }
}