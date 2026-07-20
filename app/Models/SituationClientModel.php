<?php

namespace App\Models;

use CodeIgniter\Model;

class SituationClientModel extends Model
{
    protected $table      = 'situation_clients';//Nom de la vue 
    protected $primaryKey = 'client_id';
    protected $returnType = 'array';
}