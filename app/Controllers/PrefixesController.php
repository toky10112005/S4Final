<?php
namespace App\Controllers;


use App\Models\PrefixesModel;

class PrefixesController extends BaseController
{
    protected $prefixesModel;

    public function __construct()
    {
        $this->prefixesModel = new PrefixesModel();
    }

    public function list_prefixes()
    {
        $prefixes = $this->prefixesModel->findAll();
        return view('operateur/prefixes', ['prefixes' => $prefixes]);
    }
}