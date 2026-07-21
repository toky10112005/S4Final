<?php
namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\TransactionsModel;
use App\Libraries\FeeCalculator;
use App\Models\PrefixesModel;
use App\Models\EpargneModel;

class ClientsController extends BaseController
{
    protected $clientsModel;
    protected $transactionsModel;
    protected $feeCalculator;
    protected $prefixesModel;

    public function __construct()
    {
        $this->clientsModel = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->feeCalculator = new FeeCalculator();
        $this->prefixesModel = new PrefixesModel();
    }

    public function index()
    {
        return view('login');
    }

    // Login (automatic by telephone). Creates client if not exists.
    public function loginClient()
    {
        $telephone = $this->normalizeTelephone($this->request->getPost('telephone'));
        if (empty($telephone)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numéro de téléphone');
        }

        $client = $this->clientsModel->where('telephone', $telephone)->first();
        if (empty($client)) {
            // create client on the fly
            $this->clientsModel->insert(['telephone' => $telephone, 'credit_retrait' => 0]);
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

    protected function normalizeTelephone(?string $telephone): string
    {
        return preg_replace('/\D+/', '', (string) $telephone) ?? '';
    }

    protected function getKnownPrefixes(): array
    {
        $prefixes = $this->prefixesModel->findAll();
        $values = array_values(array_filter(array_map(static fn ($row) => trim((string) ($row['prefixe'] ?? '')), $prefixes)));
        usort($values, static fn (string $left, string $right) => strlen($right) <=> strlen($left));

        return $values;
    }

    protected function getPhonePrefix(string $telephone): ?string
    {
        $telephone = $this->normalizeTelephone($telephone);
        if ($telephone === '') {
            return null;
        }

        foreach ($this->getKnownPrefixes() as $prefix) {
            if (str_starts_with($telephone, $prefix)) {
                return $prefix;
            }
        }

        return null;
    }

    protected function isSameOperatorTelephone(string $telephone): bool
    {
        return $this->getPhonePrefix($telephone) !== null;
    }

    protected function parseRecipientTelephones(?string $rawRecipients): array
    {
        $rawRecipients = trim((string) $rawRecipients);
        if ($rawRecipients === '') {
            return [];
        }

        $parts = preg_split('/[\r\n,;]+/', $rawRecipients) ?: [];
        $normalized = [];

        foreach ($parts as $part) {
            $telephone = $this->normalizeTelephone($part);
            if ($telephone !== '') {
                $normalized[] = $telephone;
            }
        }

        return array_values(array_unique($normalized));
    }

    protected function splitAmount(float $amount, int $recipientCount): array
    {
        if ($recipientCount <= 0) {
            return [];
        }

        $baseShare = round($amount / $recipientCount, 2);
        $shares = [];
        $allocated = 0.0;

        for ($index = 0; $index < $recipientCount; $index++) {
            if ($index === $recipientCount - 1) {
                $share = round($amount - $allocated, 2);
            } else {
                $share = $baseShare;
                $allocated += $share;
            }

            $shares[] = $share;
        }

        return $shares;
    }

    protected function resolveClientByTelephone(string $telephone): ?array
    {
        return $this->clientsModel->where('telephone', $telephone)->first();
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
            WHEN id_type_operation = ? AND id_expediteur = ? THEN COALESCE(frais_retrait_prepaye, 0)
            ELSE 0
        END), 0)
    - COALESCE(SUM(CASE
        WHEN id_type_operation = ? AND id_expediteur = ? THEN montant + frais
        ELSE 0
    END), 0) AS balance
FROM transactions
WHERE (id_expediteur = ? OR id_destinataire = ?)
SQL;

            $row = $db->query($sql, [$depositId, $transferId, $clientId, $withdrawId, $clientId, $transferId, $clientId, $transferId, $clientId, $clientId, $clientId])->getRowArray();
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
            'frais' => 0,
            'frais_retrait_prepaye' => 0
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
        $availableCredit = $this->clientsModel->getWithdrawalCredit((int) $client['id']);
        $paidFromCredit = min($availableCredit, $fee);
        $actualFee = max(0, $fee - $paidFromCredit);

        // Check balance
        $solde = $this->getClientBalance($client['id']);

        if ($solde < ($amount + $actualFee)) {
            return redirect()->back()->with('error', 'Solde insuffisant');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        if ($paidFromCredit > 0) {
            $this->clientsModel->consumeWithdrawalCredit((int) $client['id'], $paidFromCredit);
        }

        $this->transactionsModel->insert([
            'id_type_operation' => $operationId,
            'id_expediteur' => $client['id'],
            'id_destinataire' => null,
            'montant' => $amount,
            'frais' => $actualFee,
            'frais_retrait_prepaye' => 0
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('error', 'Impossible d’enregistrer le retrait');
        }

        return redirect()->to('/client/dashboard')->with('success', 'Retrait effectué');
    }
//eeeeeeeeeeeeeeee
    public function transfer()
    {
        $client = session()->get('client');
        if (empty($client)) return redirect()->to('/')->with('error', 'Veuillez vous connecter');

        $amount = floatval($this->request->getPost('amount'));
        $includeWithdrawalFee = filter_var($this->request->getPost('include_withdraw_fee'), FILTER_VALIDATE_BOOLEAN);
        $recipients = $this->parseRecipientTelephones($this->request->getPost('beneficiaires') ?: $this->request->getPost('telephone_to'));

        if ($amount <= 0 || empty($recipients)) return redirect()->back()->with('error', 'Données invalides');

        if (count($recipients) > 1) {
            foreach ($recipients as $recipientTelephone) {
                if (! $this->isSameOperatorTelephone($recipientTelephone)) {
                    return redirect()->back()->with('error', 'L’envoi multiple est réservé aux numéros du même opérateur.');
                }
            }
        }

        if ($includeWithdrawalFee) {
            foreach ($recipients as $recipientTelephone) {
                if (! $this->isSameOperatorTelephone($recipientTelephone)) {
                    return redirect()->back()->with('error', 'L’option inclure les frais de retrait n’est disponible que pour un destinataire du même opérateur.');
                }
            }
        }

        $operationId = $this->feeCalculator->getOperationId('transfert');
        $recipientShares = $this->splitAmount($amount, count($recipients));

        $recipientData = [];

        foreach ($recipients as $index => $telephoneTo) {
            $recipient = $this->resolveClientByTelephone($telephoneTo);
            if (empty($recipient)) {
                $this->clientsModel->insert(['telephone' => $telephoneTo, 'credit_retrait' => 0]);
                $recipient = $this->clientsModel->find($this->clientsModel->getInsertID());
            }

            $shareAmount = $recipientShares[$index] ?? 0.0;
            $transferFee = $this->feeCalculator->getFeeByName('transfert', $shareAmount);
            $prepaidWithdrawalFee = $includeWithdrawalFee ? $this->feeCalculator->getFeeByName('retrait', $shareAmount) : 0.0;

            $recipientData[] = [
                'id' => (int) $recipient['id'],
                'amount' => $shareAmount,
                'transfer_fee' => $transferFee,
                'prepaid_withdrawal_fee' => $prepaidWithdrawalFee,
            ];
        }

        // Check balance
        $solde = $this->getClientBalance($client['id']);
        $totalCost = array_reduce($recipientData, static fn (float $carry, array $item) => $carry + $item['amount'] + $item['transfer_fee'] + $item['prepaid_withdrawal_fee'], 0.0);

        if ($solde < $totalCost) {
            return redirect()->back()->with('error', 'Solde insuffisant');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($recipientData as $item) {

            $epargne=new EpargneModel;
            $Cliep=$epargne->getClient($item['id']);//Client dans epargne
            //maka valeur epargne
            //analana an ilay $item['amount'],
            $valeurInsert=$item['amount']-($item['amount']*$Cliep['valeur']/100);

            $this->transactionsModel->insert([
                'id_type_operation' => $operationId,
                'id_expediteur' => $client['id'],
                'id_destinataire' => $item['id'],
                //'montant' => $item['amount'],
                'montant' =>$valeurInsert,
                'frais' => $item['transfer_fee'],
                'frais_retrait_prepaye' => $item['prepaid_withdrawal_fee']
            ]);
            
            
        

//eto
            if ($item['prepaid_withdrawal_fee'] > 0) {
                $this->clientsModel->addWithdrawalCredit($item['id'], $item['prepaid_withdrawal_fee']);
            }
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('error', 'Impossible d’enregistrer le transfert');
        }

        return redirect()->to('/client/dashboard')->with('success', 'Transfert effectué');
    }

    public function logout()
    {
        session()->remove('client');
        return redirect()->to('/');
    }
}