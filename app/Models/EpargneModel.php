<?php
    namespace App\Models;

    use CodeIgniter\Model;

    class EpargneModel extends Model
    {
        protected $table = 'epargne';
        protected $primaryKey = 'id';
        protected $allowedFields = ['valeur'];

        public function getValeur(){
            return $this->$db->getAll();
        }

        public function getClient($id){
            $query="SELECT * FROM clients JOIN epargne ON $id=epargne.id";
            return $this->$db->query();
        }

    }