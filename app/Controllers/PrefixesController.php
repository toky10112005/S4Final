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
        return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe supprimé avec succès.');
    }

    public function ajouter()
    {
        $prefixe = trim($this->request->getPost('prefixe'));
        $idOperateur = (int) $this->request->getPost('id_operateur');

        if (empty($prefixe)) {
            return redirect()->back()->with('error', 'Le préfixe est requis.');
        }

        $existingPrefix = $this->prefixesModel->where('prefixe', $prefixe)->first();
        if ($existingPrefix) {
            return redirect()->back()->with('error', 'Le préfixe existe déjà.');
        }

        $this->prefixesModel->insert([
            'prefixe' => $prefixe,
            'id_operateur' => $idOperateur > 0 ? $idOperateur : 1,
        ]);

        return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe ajouté avec succès.');
    }

    public function list_prefixes()
    {
        $prefixes = $this->prefixesModel->list_prefixe();
        $operateurs = $this->prefixesModel->getOperateursPartenaires();

        return view('operateur/prefixes', [
            'prefixes' => $prefixes,
            'operateurs' => $operateurs,
        ]);
    }
}