<?php
namespace App\Libraries;

class OperationCatalog
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function ensureDefaults(): array
    {
        $operations = ['depot', 'retrait', 'transfert'];
        $ids = [];

        foreach ($operations as $name) {
            $row = $this->db->query('SELECT id FROM type_operations WHERE nom = ?', [$name])->getRowArray();
            if (! $row) {
                $this->db->query('INSERT INTO type_operations (nom) VALUES (?)', [$name]);
                $id = (int) $this->db->insertID();
            } else {
                $id = (int) $row['id'];
            }

            $ids[$name] = $id;
        }

        return $ids;
    }

    public function getOperationId(string $name): int
    {
        $ids = $this->ensureDefaults();

        return $ids[$name] ?? 0;
    }
    
    public function getOperationIds(): array
    {
        return $this->ensureDefaults();
    }
}
