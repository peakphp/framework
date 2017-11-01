<?php

namespace Peak\Climber\Cron;

use Peak\Climber\Cron\Exceptions\DatabaseNotFoundException;
use Peak\Climber\Cron\Exceptions\TablesNotFoundException;

class CronApi
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * CronApi constructor
     *
     * @param array $database_config
     * @throws DatabaseNotFoundException
     * @throws TablesNotFoundException
     */
    public function __construct(array $database_config)
    {
        $this->conn = CronSystem::connect($database_config);

        if (!CronSystem::hasDbConnection($this->conn)) {
            throw new DatabaseNotFoundException();
        } elseif ($this->conn->connect() && !CronSystem::isInstalled($this->conn)) {
            throw new TablesNotFoundException();
        }
    }

    /**
     * Get all crons
     *
     * @return array
     */
    public function getAll()
    {
        $qb = $this->conn->createQueryBuilder();

        $qb->select('*')
            ->from('climber_cron')
            ->orderBy('id');

        $result = $qb->execute()->fetchAll();

        return $result;
    }

    /**
     * Get all crons in an array of cron entities (CronEntity)
     *
     * @return array
     */
    public function getAllAsEntities()
    {
        $result = $this->getAll();

        foreach ($result as $index => $cron) {
            $result[$index] = new CronEntity($cron);
        }

        return $result;
    }

    /**
     * Get cron
     *
     * @param $id
     * @return mixed
     */
    public function getId($id)
    {
        $qb = $this->conn->createQueryBuilder();

        $qb->select('*')
            ->from('climber_cron')
            ->where('`id` = :id')
            ->setParameter('id', $id);

        $result = $qb->execute()->fetch();
        return $result;
    }

    /**
     * Get cron as CronEntity
     *
     * @param $id
     * @return CronEntity
     */
    public function getIdAsEntity($id)
    {
        $cron = $this->getId($id);
        return new CronEntity($cron);
    }

    /**
     * Add new cron
     *
     * @param CronBuilder $builder
     */
    public function add(CronBuilder $builder)
    {
        $cron = $builder->build();
        $insert = [];
        foreach ($cron as $key => $value) {
            $insert['`'.$key.'`'] = $value;
        }
        $this->conn->insert('climber_cron', $insert);
        return $this->conn->lastInsertId();
    }

    /**
     * Has cron id
     *
     * @param $id
     * @return bool
     */
    public function hasId($id)
    {
        return !empty($this->get($id));
    }

    /**
     * Update a cron
     *
     * @param $id
     * @param array $data
     */
    public function updateId($id, array $data)
    {
        $this->conn->update('climber_cron', $data, [
            'id' => $id
        ]);

    }

    /**
     * Delete a cron
     *
     * @param $id
     * @return int
     */
    public function deleteId($id)
    {
        $this->conn->delete('climber_cron', ['id' => $id]);
    }
}
