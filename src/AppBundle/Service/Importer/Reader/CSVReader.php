<?php

namespace AppBundle\Service\Importer\Reader;

class CSVReader extends FileReader
{
    /**
     * @var int
     */
    protected $counter = -1;

    /**
     * @var array|bool
     */
    protected $data = false;

    /**
     * @var array
     */
    protected $options = [
        // fgetcsv parameters
        'length' => 0,
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '\\',

        // skip headers
        'skipFirstLines' => 0,
    ];

    /**
     * @param array $options See description of fgetcsv function for allowed parameters.
     *                       string length
     *                       string delimiter
     *                       string enclosure
     *                       string escape
     *                       int skipFirstLines For skipping headers
     */
    public function setOptions(array $options = [])
    {
        $options = array_intersect_key($options, $this->options);
        $this->options = array_replace($this->options, $options);
    }

    /**
     * @return array
     */
    public function current()
    {
        return $this->data;
    }

    /**
     * @return void
     * @throws ReaderException
     */
    public function next()
    {
        if (!$this->isOpen()) {
            throw new ReaderException('Source is not readable');
        }

        $this->data = fgetcsv(
            $this->fileHandler,
            $this->options['length'],
            $this->options['delimiter'],
            $this->options['enclosure'],
            $this->options['escape']
        );

        if ($this->valid()) {
            $this->counter++;

        } else {
            $this->close();
        }
    }

    /**
     * @return integer
     */
    public function key()
    {
        return $this->counter;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->data !== false && $this->data !== null;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->counter = 0;
        $this->data = false;
        $this->close();
        $this->open();
        $this->next();

        $i = (int)$this->options['skipFirstLines'];
        while ($i > 0 && $this->valid()) {
            $this->next();
            $i--;
        }
    }
}
