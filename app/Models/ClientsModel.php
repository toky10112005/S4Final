<?php
    namespace App\Models;

    use CodeIgniter\Model;

    class ClientsModel extends Model
    {
        protected $table = 'clients';
        protected $primaryKey = 'id';
        protected $allowedFields = ['telephone', 'nom', 'date_creation', 'credit_retrait'];

        
        protected  $validationRules = [
                'telephone' => 'required|is_unique[clients.telephone]',
            ];

        public function getWithdrawalCredit(int $clientId): float
        {
            $row = $this->select('COALESCE(credit_retrait, 0) AS credit_retrait')
                ->where('id', $clientId)
                ->first();

            return $row && isset($row['credit_retrait']) ? (float) $row['credit_retrait'] : 0.0;
        }

        public function addWithdrawalCredit(int $clientId, float $amount): bool
        {
            if ($amount <= 0) {
                return true;
            }

            return $this->builder()
                ->set('credit_retrait', 'COALESCE(credit_retrait, 0) + ' . $this->db->escape($amount), false)
                ->where('id', $clientId)
                ->update();
        }

        public function consumeWithdrawalCredit(int $clientId, float $amount): bool
        {
            if ($amount <= 0) {
                return true;
            }

            return $this->builder()
                ->set('credit_retrait', 'CASE WHEN COALESCE(credit_retrait, 0) > ' . $this->db->escape($amount) . ' THEN COALESCE(credit_retrait, 0) - ' . $this->db->escape($amount) . ' ELSE 0 END', false)
                ->where('id', $clientId)
                ->update();
        }

        
    }