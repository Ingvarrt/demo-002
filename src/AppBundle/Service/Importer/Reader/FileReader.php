<?php

namespace AppBundle\Service\Importer\Reader;


abstract class FileReader implements ReaderInterface
{
    /**
     * @var string
     */
    private $filePath;

    /** @var resource */
    protected $fileHandler = null;

    /**
     * @param string $filePath
     * @throws ReaderException
     */
    public function setFilePath($filePath)
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ReaderException(sprintf('"%s" is not readable'), $filePath);
        }

        $this->filePath = $filePath;
    }

    /**
     *
     */
    protected function open()
    {
        $this->fileHandler = fopen($this->filePath, 'r');
    }

    /**
     *
     */
    protected function close()
    {
        if (!$this->isOpen()) {
            return;
        }

        fclose($this->fileHandler);
    }

    /**
     * @return bool
     */
    protected function isOpen()
    {
        return $this->fileHandler !== null && is_resource($this->fileHandler);
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->close();
    }
}
