<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CurriculumImport implements WithMultipleSheets
{
    private $sheetCount;

    public function __construct(int $sheetCount)
    {
        $this->sheetCount = $sheetCount;
    }

    public function sheets(): array
    {
        $sheetImports = [];

        for ($i = 0; $i < $this->sheetCount; $i++) {
            $sheetImports[] = new CurriculumSheetImport();
        }

        return $sheetImports;
    }
}
