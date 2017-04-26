<?php

namespace AppBundle\Service\Importer\Strategy;

use AppBundle\Service\Importer\Reader\ReaderInterface;
use AppBundle\Service\Importer\Writer\WriterInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractStrategy implements StrategyInterface
{
    /** @var LoggerInterface|null */
    private $logger = null;

    /** @var ReaderInterface */
    protected $reader;

    /** @var WriterInterface */
    protected $writer;

    /**
     * @param ReaderInterface $reader
     * @param WriterInterface $writer
     */
    public function __construct(ReaderInterface $reader, WriterInterface $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * @param LoggerInterface|null $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @return ReaderInterface
     */
    public function getReader(): ReaderInterface
    {
        return $this->reader;
    }

    /**
     * @return WriterInterface
     */
    public function getWriter(): WriterInterface
    {
        return $this->writer;
    }

    /**
     * @param string $message
     * @param array $context
     */
    protected function logError($message, array $context = [])
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->error($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    protected function logDebug($message, array $context = [])
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->debug($message, $context);
    }
}
