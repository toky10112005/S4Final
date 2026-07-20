<?php
namespace App\Libraries;

/**
 * Simple fee calculator that reads `bareme_frais` table.
 * This provides a stable interface for the client side to call.
 */
class FeeCalculator
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Get fee for a given operation type and amount.
     * idType: 2 => retrait, 3 => transfert, 1 => depot (should return 0)
     */
    public function getFee(int $idType, float $amount): float
    {
        if ($idType === 1) return 0.0;

        $sql = 'SELECT frais FROM bareme_frais WHERE id_type_operation = ? AND montant_min <= ? AND montant_max >= ? LIMIT 1';
        $res = $this->db->query($sql, [$idType, $amount, $amount])->getRowArray();
        if ($res && isset($res['frais'])) {
            return floatval($res['frais']);
        }

        // fallback: no matching bracket -> 0
        return 0.0;
    }
}
