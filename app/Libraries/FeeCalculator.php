<?php
namespace App\Libraries;

/**
 * Fee calculator backed by the SQLite `bareme_frais` table.
 */
class FeeCalculator
{
    protected $db;
    protected $operationCatalog;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->operationCatalog = new OperationCatalog();

        $this->ensureDefaultFees();
    }

    /**
     * Get fee for a given operation type and amount.
     * idType: 2 => retrait, 3 => transfert, 1 => depot (should return 0)
     */
    public function getFee(int $idType, float $amount): float
    {
        if ($idType <= 0) {
            return 0.0;
        }

        $this->ensureDefaultFees();

        if ($idType === $this->operationCatalog->getOperationId('depot')) {
            return 0.0;
        }

        $sql = 'SELECT frais FROM bareme_frais WHERE id_type_operation = ? AND montant_min <= ? AND montant_max >= ? LIMIT 1';
        $res = $this->db->query($sql, [$idType, $amount, $amount])->getRowArray();

        return $res && isset($res['frais']) ? floatval($res['frais']) : 0.0;
    }

    public function getFeeByName(string $operationName, float $amount): float
    {
        $operationId = $this->operationCatalog->getOperationId($operationName);

        return $this->getFee($operationId, $amount);
    }

    public function getOperationId(string $operationName): int
    {
        return $this->operationCatalog->getOperationId($operationName);
    }

    protected function ensureDefaultFees(): void
    {
        $operationIds = [
            'retrait' => $this->operationCatalog->getOperationId('retrait'),
            'transfert' => $this->operationCatalog->getOperationId('transfert'),
        ];

        foreach ($operationIds as $name => $operationId) {
            if ($operationId <= 0) {
                continue;
            }

            $countRow = $this->db->query('SELECT COUNT(*) AS count_rows FROM bareme_frais WHERE id_type_operation = ?', [$operationId])->getRowArray();
            if (!empty($countRow) && (int) $countRow['count_rows'] > 0) {
                continue;
            }

            $rules = [
                'retrait' => [
                    [100, 1000, 50],
                    [1001, 5000, 50],
                    [5001, 10000, 100],
                    [10001, 25000, 200],
                    [25001, 50000, 400],
                    [50001, 100000, 800],
                    [10001, 250000, 1500],
                    [250001, 500000, 1500],
                    [500001, 1000000, 2500],
                    [1000001, 2000000, 3000],
                ],
                'transfert' => [
                    [100, 1000, 50],
                    [1001, 5000, 50],
                    [5001, 10000, 100],
                    [10001, 25000, 200],
                    [25001, 50000, 400],
                    [50001, 100000, 800],
                    [10001, 250000, 1500],
                    [250001, 500000, 1500],
                    [500001, 1000000, 2500],
                    [1000001, 2000000, 3000],
                ],
            ];

            foreach ($rules[$name] as $rule) {
                $this->db->query(
                    'INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais) VALUES (?, ?, ?, ?)',
                    [$operationId, $rule[0], $rule[1], $rule[2]]
                );
            }
        }
    }
}
