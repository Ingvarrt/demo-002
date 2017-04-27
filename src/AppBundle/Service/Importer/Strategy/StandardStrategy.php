<?php

namespace AppBundle\Service\Importer\Strategy;

class StandardStrategy extends AbstractStrategy
{
    /**
     * @inheritdoc
     */
    public function import()
    {
        $this->writer->prepare();
        foreach ($this->reader as $data) {
            if ($this->writer->process($data)) {
                $this->logDebug('Data process ok', $data);
            } else {
                $this->logError('Data process fail', $data);
                throw new ImportException('Data process fail');
            }
        }
        $this->writer->finish();

        return true;
    }
}
