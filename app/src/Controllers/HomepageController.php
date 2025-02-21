<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Commands\ConnectDatabase;

class HomepageController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->startSessionIfNeeded();
        return $this->homepage();
    }

    private function homepage()
    {
        $db = (new ConnectDatabase())->execute();

        $services = $db->query("
            SELECT 
                id,
                nom,
                description
            FROM Services
            ORDER BY id DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);

        return $this->render('homepage', get_defined_vars());
    }
}
