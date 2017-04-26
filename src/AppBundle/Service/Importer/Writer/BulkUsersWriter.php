<?php

namespace AppBundle\Service\Importer\Writer;

use Doctrine\DBAL\Logging\SQLLogger;

class BulkUsersWriter extends UsersWriter
{
    /** @var int */
    private $counter = 0;

    /** @var int */
    private $batchSize = 0;

    /**
     * @var SQLLogger|null
     */
    private $bufLogger = null;

    /**
     * @var array
     */
    private $entities = [];

    /**
     * @param $batchSize
     */
    public function __construct($batchSize)
    {
        $this->batchSize = (int) $batchSize;

        if ($this->batchSize < 2) {
            throw new WriterException('Batch size is too small');
        }
    }

    public function prepare()
    {
        $this->bufLogger = $this->em->getConnection()->getConfiguration()->getSQLLogger();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
    }

    /**
     * @param $data
     * @return bool
     */
    public function process($data)
    {
        if (!is_array($data) || empty($data)) {
            throw new WriterException('Data is empty');
        }

        $user = $this->createUserFromArray($data);

        $this->em->persist($user);
        $this->entities = [];
        $this->counter++;

        if ($this->counter % $this->batchSize == 0) {
            $this->cleanup();
        }

        return true;
    }

    /**
     * @return void
     */
    public function finish()
    {
        $this->cleanup();
        $this->em->getConnection()->getConfiguration()->setSQLLogger($this->bufLogger);
    }

    private function cleanup()
    {
        $this->em->flush();
        $this->em->clear();
        foreach ($this->entities as $entity) {
            $this->em->detach($entity);
        }
        $this->entities = [];
        gc_enable();
        gc_collect_cycles();
    }
}
