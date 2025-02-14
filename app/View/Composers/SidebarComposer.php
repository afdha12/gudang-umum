<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    public function compose(View $view)
    {
        $role = Auth::check() ? Auth::user()->role : null;
        $view->with('role', $role);
    }
}