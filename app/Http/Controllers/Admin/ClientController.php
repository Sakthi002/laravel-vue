<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index()
    {
        $clients =  Client::latest('created_at')->get();

        $clients = $clients->map(function ($client){

            $client['name'] = $client->first_name . ' ' . $client->last_name;

            return $client;
        });

        return $clients;
    }
}
