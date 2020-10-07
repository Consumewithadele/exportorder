<?php
namespace Consumewithadele\ExportOrder\Model\Converter;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;

class Csv
{
    /**
     * @var WriteInterface
     */
    protected $directory;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $path = 'export';

    public function __construct(
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
        $this->directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    }

    /**
     * @param array $order
     */
    public function getCsv(array $order)
    {
        $name = sha1(microtime());
        $file = $this->path . '/' . $name . '.csv';
        $this->directory->create($this->path);
        $stream = $this->directory->openFile($file, 'w+');

        $stream->lock();
        foreach ($order as $row) {
            if ($row[0] == 'header-line') {
                array_splice($row, 0, 1);
            }
            $stream->writeCsv($row);
        }
        $stream->unlock();
        $stream->close();

        return $file;
    }
}
