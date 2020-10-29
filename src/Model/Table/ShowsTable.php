<?php

namespace App\Model\Table;

use Cake\Datasource\ConnectionInterface;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class ShowsTable extends \Cake\ORM\Table
{
    private ConnectionInterface $connection;

    public function __construct(array $config = ['connection'])
    {
        parent::__construct($config);
        $this->connection = ConnectionManager::get('default');
        $this->urlStart = 'http://www.brilliantbutcancelled.com/show';
    }

    public function initialize(array $config): void
    {
        $this->setPrimaryKey('sid');
        $this->belongsToMany('Episodes');
    }

    public function getTotalSeasons($sid)
    {
        $sql = "SELECT season FROM episodes WHERE sid = ? AND season IS NOT NULL GROUP BY season";
        $result = $this->connection->execute($sql, [$sid]);
        return $result->count();
    }

    public function getTotalReviews($sid)
    {
        $sql = "SELECT sid FROM episodes WHERE sid = ?";
        $result = $this->connection->execute($sql, [$sid]);
        return $result->count();
    }
}
