<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BaremeFraisModel;

class BaremeFraisController extends BaseController
{

    protected $baremeFraisModel;

    public function __construct()
    {
        $this->baremeFraisModel = new BaremeFraisModel();
    }

    public function list_barem()
    {

        
        $data['baremes'] = $this->baremeFraisModel->getBaremesAvecType();

        
        return view('operateur/list_barem', $data);
    }
}