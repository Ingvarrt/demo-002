<?php

namespace AppBundle\Service\Importer;

use AppBundle\Service\Importer\Reader\FileReader;
use AppBundle\Service\Importer\Strategy\AbstractStrategy;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class UsersImporter implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function import($filePath)
    {
        // perform heavy operation
        set_time_limit(0);
        $this->container->get('profiler')->disable();

        /** @var AbstractStrategy $importer */
        $importer = $this->container->get('import.strategy.users.standard');

        /** @var FileReader $reader */
        $reader = $importer->getReader();
        $reader->setFilePath($filePath);

        try {
            $importer->import();

        } finally {
            $this->container->get('profiler')->enable();
        }
    }
}