<?php
namespace App\Models;

use CodeIgniter\Model;

class PrefixesModel extends Model
{
    protected $table = 'prefixes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['prefixe', 'id_operateur'];

    protected $validationRules = [
        'prefixe' => 'required|is_unique[prefixes.prefixe]',
        'id_operateur' => 'required',
    ];

    public function list_prefixe()
    {
        return $this->db->table('prefixes')
            ->join('operateurs_partenaires', 'operateurs_partenaires.id = prefixes.id_operateur', 'left')
            ->select('prefixes.*, operateurs_partenaires.nom as operateur_nom')
            ->orderBy('prefixes.prefixe', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getOperateursPartenaires()
    {
        return $this->db->table('operateurs_partenaires')
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();
    }
}