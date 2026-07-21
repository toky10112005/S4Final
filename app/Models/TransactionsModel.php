<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\OperationCatalog;

class TransactionsModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_type_operation', 'id_expediteur', 'id_destinataire', 'montant', 'frais', 'frais_retrait_prepaye', 'date_transaction'];
    protected $operationCatalog;

    public function __construct()
    {
        parent::__construct();
        $this->operationCatalog = new OperationCatalog();
    }

    public function getClientHistory(int $clientId, int $limit = 50)
    {
        $ids = $this->operationCatalog->getOperationIds();
        $depotId = (int) ($ids['depot'] ?? 0);
        $withdrawId = (int) ($ids['retrait'] ?? 0);
        $transferId = (int) ($ids['transfert'] ?? 0);

        $builder = $this->db->table($this->table . ' t');
        $builder->select("t.*, CASE
            WHEN t.id_type_operation = {$depotId} THEN 'Dépôt'
            WHEN t.id_type_operation = {$withdrawId} THEN 'Retrait'
            WHEN t.id_type_operation = {$transferId} THEN 'Transfert'
            ELSE COALESCE(type_op.nom, 'Opération')
        END AS type_nom");
        $builder->join('type_operations type_op', 'type_op.id = t.id_type_operation', 'left');
        $builder->where('(t.id_expediteur = '.$this->db->escape($clientId).' OR t.id_destinataire = '.$this->db->escape($clientId).')');
        $builder->orderBy('t.date_transaction', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }
}
