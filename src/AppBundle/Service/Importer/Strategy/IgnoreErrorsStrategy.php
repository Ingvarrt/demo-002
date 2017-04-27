<?php

namespace AppBundle\Service\Importer\Strategy;

class IgnoreErrorsStrategy extends AbstractStrategy
{
    /**
     * @inheritdoc
     */
    public function import()
    {
        try {
            $this->writer->prepare();
            foreach ($this->reader as $data) {
                if ($this->writer->process($data)) {
                    $this->logDebug('Data process ok', $data);
                } else {
                    $this->logError('Data process fail', $data);
                }
            }
            $this->writer->finish();
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }

        return true;
    }
}
