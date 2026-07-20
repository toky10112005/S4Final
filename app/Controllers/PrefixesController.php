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

    public function supprimer($id)
    {
        $this->prefixesModel->delete($id);
        return redirect()->to('/prefixes');
    }

    public function ajouter()
    {
        $prefixe = $this->request->getPost('prefixe');

        
        if (empty($prefixe)) {
            return redirect()->back()->with('error', 'Le préfixe est requis.');
        }

        
        $existingPrefix = $this->prefixesModel->where('prefixe', $prefixe)->first();
        if ($existingPrefix) {
            return redirect()->back()->with('error', 'Le préfixe existe déjà.');
        }

        
        $this->prefixesModel->insert(['prefixe' => $prefixe]);

        return redirect()->to('/prefixes')->with('success', 'Préfixe ajouté avec succès.');
    }

    public function list_prefixes()
    {
        $prefixes = $this->prefixesModel->findAll();
        return view('operateur/prefixes', ['prefixes' => $prefixes]);
    }
}