<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;
use Illuminate\Routing\Controller as BaseController;

class HomeBaseController extends BaseController
{
    protected $mejaId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$request->has('mejaId') || !$this->isValidMeja($request->mejaId)) {
                return response()->view('scan-qr-message');
            }
            // Store the valid mejaId for use in child controllers
            $this->mejaId = $request->mejaId;

            return $next($request);
        });
    }

    /**
     * Check if the table ID is valid
     *
     * @param mixed $mejaId
     * @return bool
     */
    protected function isValidMeja($mejaId)
    {
        return Meja::where('unique_code', $mejaId)->exists();
    }
}
