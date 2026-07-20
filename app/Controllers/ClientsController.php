<?php
namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\TransactionsModel;
use App\Libraries\FeeCalculator;

class ClientsController extends BaseController
{
    protected $clientsModel;
    protected $transactionsModel;
    protected $feeCalculator;

    public function __construct()
    {
        $this->clientsModel = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->feeCalculator = new FeeCalculator();
    }

    public function index()
    {
        return view('login');
    }

    // Login (automatic by telephone). Creates client if not exists.
    public function loginClient()
    {
        $telephone = $this->request->getPost('telephone');
        if (empty($telephone)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numéro de téléphone');
        }

        $client = $this->clientsModel->where('telephone', $telephone)->first();
        if (empty($client)) {
            // create client on the fly
            $this->clientsModel->insert(['telephone' => $telephone]);
            $id = $this->clientsModel->getInsertID();
            $client = $this->clientsModel->find($id);
        }

        session()->set('client', [
            'id' => $client['id'],
            'telephone' => $client['telephone']
        ]);

        return redirect()->to('/client/dashboard');
    }

    protected function ensureClient()
    {
        $client = session()->get('client');
        if (empty($client)) {
            return redirect()->to('/')->with('error', 'Veuillez vous connecter');
        }
        return $client;
    }

    public function dashboard()
    {
        $client = session()->get('client');
        if (empty($client)) {
            return redirect()->to('/')->with('error', 'Veuillez vous connecter');
        }

        // Get balance from view v_solde_clients if present, fallback to calculation
        $db = \Config\Database::connect();
        $query = $db->query('SELECT solde FROM v_solde_clients WHERE client_id = ?', [$client['id']]);
        $row = $query->getRowArray();
        $solde = $row ? $row['solde'] : 0;

        // Recent history (sent or received)
        $history = $this->transactionsModel->getClientHistory($client['id']);

        return view('client/dashboard', [
            'client' => $client,
            'solde' => $solde,
            'history' => $history,
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error')
        ]);
    }

    public function deposit()
    {
        $client = session()->get('client');
        if (empty($client)) return redirect()->to('/')->with('error', 'Veuillez vous connecter');

        $amount = floatval($this->request->getPost('amount'));
        if ($amount <= 0) return redirect()->back()->with('error', 'Montant invalide');

        // Deposits have type 1 and no fees
        $this->transactionsModel->insert([
            'id_type_operation' => 1,
            'id_expediteur' => $client['id'],
            'id_destinataire' => null,
            'montant' => $amount,
            'frais' => 0
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Dépôt effectué');
    }

    public function withdraw()
    {
        $client = session()->get('client');
        if (empty($client)) return redirect()->to('/')->with('error', 'Veuillez vous connecter');

        $amount = floatval($this->request->getPost('amount'));
        if ($amount <= 0) return redirect()->back()->with('error', 'Montant invalide');

        $fee = $this->feeCalculator->getFee(2, $amount);

        // Check balance
        $db = \Config\Database::connect();
        $query = $db->query('SELECT solde FROM v_solde_clients WHERE client_id = ?', [$client['id']]);
        $row = $query->getRowArray();
        $solde = $row ? $row['solde'] : 0;

        if ($solde < ($amount + $fee)) {
            return redirect()->back()->with('error', 'Solde insuffisant');
        }

        $this->transactionsModel->insert([
            'id_type_operation' => 2,
            'id_expediteur' => $client['id'],
            'id_destinataire' => null,
            'montant' => $amount,
            'frais' => $fee
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Retrait effectué');
    }

    public function transfer()
    {
        $client = session()->get('client');
        if (empty($client)) return redirect()->to('/')->with('error', 'Veuillez vous connecter');

        $amount = floatval($this->request->getPost('amount'));
        $telephoneTo = $this->request->getPost('telephone_to');

        if ($amount <= 0 || empty($telephoneTo)) return redirect()->back()->with('error', 'Données invalides');

        // Ensure recipient exists (auto-create)
        $recipient = $this->clientsModel->where('telephone', $telephoneTo)->first();
        if (empty($recipient)) {
            $this->clientsModel->insert(['telephone' => $telephoneTo]);
            $recipientId = $this->clientsModel->getInsertID();
        } else {
            $recipientId = $recipient['id'];
        }

        $fee = $this->feeCalculator->getFee(3, $amount);

        // Check balance
        $db = \Config\Database::connect();
        $query = $db->query('SELECT solde FROM v_solde_clients WHERE client_id = ?', [$client['id']]);
        $row = $query->getRowArray();
        $solde = $row ? $row['solde'] : 0;

        if ($solde < ($amount + $fee)) {
            return redirect()->back()->with('error', 'Solde insuffisant');
        }

        $this->transactionsModel->insert([
            'id_type_operation' => 3,
            'id_expediteur' => $client['id'],
            'id_destinataire' => $recipientId,
            'montant' => $amount,
            'frais' => $fee
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Transfert effectué');
    }

    public function logout()
    {
        session()->remove('client');
        return redirect()->to('/');
    }
}