<?php
    namespace App\Models;

    use CodeIgniter\Model;

    class ClientsModel extends Model
    {
        protected $table = 'clients';
        protected $primaryKey = 'id';
        protected $allowedFields = ['telephone', 'nom', 'date_creation'];

        
        protected  $validationRules = [
                'telephone' => 'required|is_unique[clients.telephone]',
            ];

        
    }