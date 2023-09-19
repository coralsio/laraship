<?php

namespace Corals\Foundation\Classes;

use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\Creator\WriterFactory;
use OpenSpout\Writer\WriterInterface;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;

class ExcelWriter
{
    private $writer;

    private $path = '';

    private $processHeader = true;

    private $processingFirstRow = true;

    private $numberOfRows = 0;

    /**
     * @param string $file
     * @return static
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     */
    public static function create(string $file)
    {
        $simpleExcelWriter = new static($file);

        $simpleExcelWriter->getWriter()->openToFile($file);

        return $simpleExcelWriter;
    }

    /**
     * @param string $downloadName
     * @return static
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     */
    public static function streamDownload(string $downloadName)
    {
        $simpleExcelWriter = new static($downloadName);

        $simpleExcelWriter->getWriter()->openToBrowser($downloadName);

        return $simpleExcelWriter;
    }

    /**
     * ExcelWriter constructor.
     * @param string $path
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     */
    protected function __construct(string $path)
    {
        $this->writer = WriterFactory::createFromFile($path);

        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getWriter(): WriterInterface
    {
        return $this->writer;
    }

    public function getNumberOfRows(): int
    {
        return $this->numberOfRows;
    }

    public function noHeaderRow()
    {
        $this->processHeader = false;

        return $this;
    }

    /**
     * @param $row
     * @param Style|null $style
     * @return $this
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Writer\Exception\WriterNotOpenedException
     */
    public function addRow($row, Style $style = null)
    {
        if (is_array($row)) {
            if ($this->processHeader && $this->processingFirstRow) {
                $this->writeHeaderFromRow($row);
            }

            $cells = array_map(function ($cellValue) {
                return Cell::fromValue($cellValue);
            }, $row);

            $row = new Row($cells,$style);
        }

        $this->writer->addRow($row);
        $this->numberOfRows++;

        $this->processingFirstRow = false;

        return $this;
    }

    protected function writeHeaderFromRow(array $row)
    {
        $headerValues = array_keys($row);

        $cells = array_map(function ($cellValue) {
            return Cell::fromValue($cellValue);
        }, $headerValues);

        $headerRow = new Row($cells);

        $this->writer->addRow($headerRow);
        $this->numberOfRows++;
    }

    public function toBrowser()
    {
        $this->writer->close();

        exit;
    }

    public function close()
    {
        $this->writer->close();
    }

    public function __destruct()
    {
        $this->close();
    }
}
