<?php
namespace Consumewithadele\ExportOrder\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Magento\Framework\App\State;
use Consumewithadele\ExportOrder\Model\Export\Order as OrderExport;
use Consumewithadele\ExportOrder\Model\Converter\Csv as CsvConverter;
use Magento\Sales\Model\OrderRepository;

class ExportOrder extends Command
{
    const ORDER_ID = 'orderId';

    /**
     * @var OrderExport
     */
    private $export;
    /**
     * @var State
     */
    private $state;
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var CsvConverter
     */
    private $csvConverter;


    /**
     * @param OrderExport $export
     * @param State $state
     * @param CsvConverter $csvConverter
     *
     */
    public function __construct(
        OrderExport $export,
        State $state,
        OrderRepository $orderRepository,
        CsvConverter $csvConverter
    ){
        $this->export = $export;
        $this->state = $state;
        $this->orderRepository = $orderRepository;
        $this->csvConverter = $csvConverter;
        parent::__construct();
    }

    /**
     * @api
     */
    public function configure()
    {
        $options = [
            new InputOption(
                self::ORDER_ID,
                null,
                InputOption::VALUE_REQUIRED,
                'OrderId',
                null
            )
        ];

        $this->setName('consumewithadele:exportorder')
            ->setDescription('Export Order')
            ->setDefinition($options);
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
        $orderId = $input->getOption(self::ORDER_ID);
        if (!$orderId) {
            $output->writeln(self::ORDER_ID . ' is required.');
            return;
        }
        $order = $this->orderRepository->get($orderId);
        $orderData = $this->export->exportOrder($order);
        $file = $this->csvConverter->getCsv($orderData);
        $output->writeln('Order ' . $order->getIncrementId() . ' exported into file var/' . $file);
    }
}
