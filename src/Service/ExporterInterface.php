<?php

namespace App\Service;

interface ExporterInterface
{
    public function export(array $data): string;

}
