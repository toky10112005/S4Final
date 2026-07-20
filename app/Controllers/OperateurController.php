<?php
namespace App\Controllers;


use App\Models\ClientsModel;
use App\Models\OperateurModel;
use App\Models\SituationClientModel;

class OperateurController extends BaseController
{

    protected $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        return view('operateur/LoginOperateur');
    }

    public function loginOperateur()
    {
        $mdp=$this->request->getPost('password');
        $Op=$this->operateurModel->where('password', $mdp)->first();

        if ($Op) {
            $session = session();
            // $session->set('operateur', $Op);
                 session()->set('Client',[
                    'id'=>$Op['id'],
                    'username'=>$Op['username'],
                    'role'=>$Op['role']
                ]);

                $gainretrais=$this->operateurModel->getgainsretrait();
                $gainstransfert=$this->operateurModel->getgainstransfert();



            return view('/operateur/dashboard',[
                'gainretrais'=>$gainretrais,
                'gainstransfert'=>$gainstransfert
            ]);
        } else {
            
            return redirect()->back()->with('error', 'Identifiants invalides');
        }

    }

    public function situationComptes()
    {
        $model = new SituationClientModel();

        $data = [
            'comptes' => $model->paginate(10),
            'pager'   => $model->pager,        
        ];

        return view('operateur/situation_clients', $data);
    }


}