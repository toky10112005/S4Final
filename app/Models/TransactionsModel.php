<?php
namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_type_operation', 'id_expediteur', 'id_destinataire', 'montant', 'frais', 'date_transaction'];

    public function getClientHistory(int $clientId, int $limit = 50)
    {
        $builder = $this->db->table($this->table . ' t');
        $builder->select('t.*, to.nom AS type_nom');
        $builder->join('type_operations to', 'to.id = t.id_type_operation', 'left');
        $builder->where('(t.id_expediteur = '.$this->db->escape($clientId).' OR t.id_destinataire = '.$this->db->escape($clientId).')');
        $builder->orderBy('t.date_transaction', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }
}
