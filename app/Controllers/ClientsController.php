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

    protected function getClientBalance(int $clientId): float
    {
        $db = \Config\Database::connect();

        try {
            $viewQuery = $db->query('SELECT solde FROM v_solde_clients WHERE client_id = ?', [$clientId]);
            $viewRow = $viewQuery->getRowArray();
            if ($viewRow && isset($viewRow['solde'])) {
                return floatval($viewRow['solde']);
            }
        } catch (\Throwable $e) {
            // Fall back to direct calculation if the balance view is missing or invalid.
        }

        $depositId = $this->feeCalculator->getOperationId('depot');
        $withdrawId = $this->feeCalculator->getOperationId('retrait');
        $transferId = $this->feeCalculator->getOperationId('transfert');

        $sql = <<<SQL
SELECT
    COALESCE(SUM(CASE
        WHEN id_type_operation = ? THEN montant
        ELSE 0
    END), 0)
    + COALESCE(SUM(CASE
        WHEN id_type_operation = ? AND id_destinataire = ? THEN montant
        ELSE 0
    END), 0)
    - COALESCE(SUM(CASE
        WHEN id_type_operation = ? AND id_expediteur = ? THEN montant + frais
        ELSE 0
    END), 0)
    - COALESCE(SUM(CASE
        WHEN id_type_operation = ? AND id_expediteur = ? THEN montant + frais
        ELSE 0
    END), 0) AS balance
FROM transactions
WHERE (id_expediteur = ? OR id_destinataire = ?)
SQL;

        $row = $db->query($sql, [$depositId, $transferId, $clientId, $withdrawId, $clientId, $transferId, $clientId, $clientId, $clientId])->getRowArray();
        $balance = $row && isset($row['balance']) ? floatval($row['balance']) : 0.0;

        return $balance;
    }

    public function dashboard()
    {
        $client = session()->get('client');
        if (empty($client)) {
            return redirect()->to('/')->with('error', 'Veuillez vous connecter');
        }

        $solde = $this->getClientBalance($client['id']);

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
            'id_type_operation' => $this->feeCalculator->getOperationId('depot'),
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

        $operationId = $this->feeCalculator->getOperationId('retrait');
        $fee = $this->feeCalculator->getFeeByName('retrait', $amount);

        // Check balance
        $solde = $this->getClientBalance($client['id']);

        if ($solde < ($amount + $fee)) {
            return redirect()->back()->with('error', 'Solde insuffisant');
        }

        $this->transactionsModel->insert([
            'id_type_operation' => $operationId,
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

        $operationId = $this->feeCalculator->getOperationId('transfert');
        $fee = $this->feeCalculator->getFeeByName('transfert', $amount);

        // Check balance
        $solde = $this->getClientBalance($client['id']);

        if ($solde < ($amount + $fee)) {
            return redirect()->back()->with('error', 'Solde insuffisant');
        }

        $this->transactionsModel->insert([
            'id_type_operation' => $operationId,
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