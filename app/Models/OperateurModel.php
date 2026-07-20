<?php
    namespace App\Models;

    use CodeIgniter\Model;

    class OperateurModel extends Model
    {
        protected $table = 'operateur';
        protected $primaryKey = 'id';
        protected $allowedFields = ['username', 'password', 'role'];

        protected $validationRules = [
            'username' => 'required|is_unique[operateur.username]',
            'password' => 'required',
        ];

        public function getgainsretrait(){
            return $this->db->table('gains_retrait')//anaran ilay vue
                        ->get()
                        ->getRowArray();
        }

        public function getgainstransfert(){
            return $this->db->table('gains_transfert')
                        ->get()
                        ->getRowArray();
        }

        public function getGainsInterne()
        {
            return $this->db->table('v_gains_interne')
                ->get()
                ->getRowArray();
        }

        public function getGainsAutresOperateurs()
        {
            return $this->db->table('v_gains_autres_operateurs')
                ->orderBy('operateur', 'ASC')
                ->get()
                ->getResultArray();
        }

        public function getCompensationOperateurs()
        {
            return $this->db->table('v_compensation_operateurs')
                ->orderBy('operateur_nom', 'ASC')
                ->get()
                ->getResultArray();
        }

    }
