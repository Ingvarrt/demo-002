<?php

namespace AppBundle\Service\Importer\Writer;


interface WriterInterface
{
    /**
     * @return void
     */
    public function prepare();

    /**
     * @param $data
     * @return bool
     */
    public function process($data);

    /**
     * @return void
     */
    public function finish();
}