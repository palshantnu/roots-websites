<?php

namespace App\Support;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/** Small fluent wrapper over OpenSpout for the Accounts Excel downloads. */
class XlsxExport
{
    private Writer $writer;

    private string $path;

    private bool $fresh = true;

    public function __construct()
    {
        $this->path = tempnam(sys_get_temp_dir(), 'xlsx').'.xlsx';
        $this->writer = new Writer();
        $this->writer->openToFile($this->path);
    }

    /** Starts a sheet; the first call names the default sheet. */
    public function sheet(string $name): static
    {
        $sheet = $this->fresh ? $this->writer->getCurrentSheet() : $this->writer->addNewSheetAndMakeItCurrent();
        $sheet->setName(mb_substr($name, 0, 31));
        $this->fresh = false;

        return $this;
    }

    public function row(array $values): static
    {
        $this->writer->addRow(Row::fromValues(array_values($values)));

        return $this;
    }

    public function rows(iterable $rows): static
    {
        foreach ($rows as $values) {
            $this->row($values);
        }

        return $this;
    }

    public function bold(array $values): static
    {
        $this->writer->addRow(Row::fromValues(array_values($values), (new Style())->setFontBold()));

        return $this;
    }

    /** Dark blue header row with white bold text. */
    public function header(array $values): static
    {
        $style = (new Style())->setFontBold()->setFontColor('FFFFFF')->setBackgroundColor('17365D');
        $this->writer->addRow(Row::fromValues(array_values($values), $style));

        return $this;
    }

    public function blank(): static
    {
        return $this->row([]);
    }

    public function download(string $filename): BinaryFileResponse
    {
        $this->writer->close();

        return response()->download($this->path, $filename)->deleteFileAfterSend();
    }
}
