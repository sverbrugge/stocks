<?php

namespace App\Http\Controllers;

use App\Models\Share;
use Illuminate\Contracts\Support\Renderable;

class GainController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    public function index(): Renderable
    {
        return view('gains')
            ->with(
                [
                    'shares' => Share::sold()->paginate(),
                ]
            );
    }
}
