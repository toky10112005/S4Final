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

        

    }
