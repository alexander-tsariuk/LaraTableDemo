<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Services\MessageService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $client = Auth::user();
        if (request()->ajax()) {
            return view('layouts.empty', compact('client'));
        }
        $route = Route::getCurrentRoute();
        $messages = new MessageService();
        $messages = $messages->getMessages();
        return view('layouts.app', compact('client', 'messages', 'route'));
    }
}

