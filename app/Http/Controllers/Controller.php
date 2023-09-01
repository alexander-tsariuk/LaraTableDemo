<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\MessageService;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    protected MessageService $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService();
    }


}
