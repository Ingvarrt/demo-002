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
            foreach ($this->reader as $data) {
                if ($this->writer->process($data)) {
                    $this->logDebug('Data process ok', $data);
                } else {
                    $this->logError('Data process fail', $data);
                }
            }
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }

        return true;
    }
}
