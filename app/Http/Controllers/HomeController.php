<?php

namespace App\Http\Controllers;


use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{

//QKPsyZjTrda8v4bJ4ik7jIasUbApUkSb/aEipKK1CuwgrLr/g+wYAx7ke3s/VD1E3Gcz+7ROaK6c0gvpXFrO6Q==
    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('home');
    }
}
