<?php

namespace AppBundle\Service\Importer\Strategy;

use AppBundle\Service\Importer\Reader\ReaderInterface;
use AppBundle\Service\Importer\Writer\WriterInterface;

interface StrategyInterface
{
    /**
     * @param ReaderInterface $reader
     * @param WriterInterface $writer
     */
    public function __construct(ReaderInterface $reader, WriterInterface $writer);

    /**
     * @return bool
     * @throws ImportException
     */
    public function import();
}