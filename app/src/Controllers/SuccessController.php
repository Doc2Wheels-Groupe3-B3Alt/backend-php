<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class SuccessController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->render('success', get_defined_vars());
    }
}
