<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModesController extends Controller
{
    /**
     * Display the Modes hub page.
     */
    public function index()
    {
        return view('modes.index');
    }

    /**
     * Display the Breathe mode page.
     */
    public function breathe()
    {
        return view('modes.breathe');
    }

    /**
     * Display the Meditate mode page.
     */
    public function meditate()
    {
        return view('modes.meditate');
    }

    /**
     * Display the Focus mode page.
     */
    public function focus()
    {
        return view('modes.focus');
    }

    /**
     * Display the Music mode page.
     */
    public function music()
    {
        return view('modes.music');
    }

    /**
     * Display the Sleep mode page.
     */
    public function sleep()
    {
        return view('modes.sleep');
    }
}
