<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table            = 'bareme_frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    
    protected $allowedFields    = [
        'id_type_operation',
        'montant_min',
        'montant_max',
        'frais'
    ];

    protected $useTimestamps    = false;
    
    protected $validationRules  = [
        'id_type_operation' => 'required|integer',
        'montant_min'        => 'required|numeric',
        'montant_max'        => 'required|numeric',
        'frais'              => 'required|numeric',
    ];

    /**
     * Méthode pour obtenir le barème avec le nom du type d'opération associé
     */
    public function getBaremesAvecType()
    {
        return $this->select('bareme_frais.*, type_operations.nom as type_operation_nom')
                    ->join('type_operations', 'type_operations.id = bareme_frais.id_type_operation')
                    ->orderBy('bareme_frais.id_type_operation', 'ASC')
                    ->orderBy('bareme_frais.montant_min', 'ASC')
                    ->findAll();
    }

    /**
     * Méthode métier : Trouver les frais correspondant à un type d'opération et un montant donné
     *
     * @param int $idTypeOperation (2 = Retrait, 3 = Transfert)
     * @param float $montant
     * @return float Montant des frais trouvés (0.0 si aucun barème ne correspond)
     */
    public function getFraisPourMontant(int $idTypeOperation, float $montant): float
    {
        $bareme = $this->where('id_type_operation', $idTypeOperation)
                       ->where('montant_min <=', $montant)
                       ->where('montant_max >=', $montant)
                       ->first();

        return $bareme ? (float)$bareme['frais'] : 0.0;
    }
}