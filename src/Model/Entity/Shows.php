<?php

namespace App\Model\Entity;

use Cake\Datasource\ConnectionManager;

class Shows extends \Cake\ORM\Entity
{
    private \Cake\Datasource\ConnectionInterface $connection;

    public function __construct(array $config = ['connection'])
    {
        parent::__construct($config);
        $this->connection = ConnectionManager::get('default');
    }
}
