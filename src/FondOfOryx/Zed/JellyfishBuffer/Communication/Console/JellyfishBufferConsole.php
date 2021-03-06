<?php

namespace FondOfOryx\Zed\JellyfishBuffer\Communication\Console;

use Exception;
use Generated\Shared\Transfer\ExportedOrderConfigTransfer;
use Generated\Shared\Transfer\JellyfishBufferTableFilterTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \FondOfOryx\Zed\JellyfishBuffer\Business\JellyfishBufferFacadeInterface getFacade()
 * @method \FondOfOryx\Zed\JellyfishBuffer\Persistence\JellyfishBufferRepositoryInterface getRepository()
 * @method \FondOfOryx\Zed\JellyfishBuffer\Communication\JellyfishBufferCommunicationFactory getFactory()
 */
class JellyfishBufferConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'jellyfish:buffer-table:export';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Exports buffered data again';

    /**
     * @var string
     */
    public const OPTION_STORE = 'store';

    /**
     * @var string
     */
    public const OPTION_STORE_SHORTCUT = 's';

    /**
     * @var string
     */
    public const OPTION_IDS = 'ids';

    /**
     * @var string
     */
    public const OPTION_IDS_SHORTCUT = 'i';

    /**
     * @var string
     */
    public const OPTION_RANGE = 'range';

    /**
     * @var string
     */
    public const OPTION_RANGE_SHORTCUT = 'rg';

    /**
     * @var string
     */
    public const OPTION_SYSTEM_CODE = 'system_code';

    /**
     * @var string
     */
    public const OPTION_SYSTEM_CODE_SHORTCUT = 'sc';

    /**
     * @var string
     */
    public const OPTION_DRY_RUN = 'dry_run';

    /**
     * @var string
     */
    public const OPTION_DRY_RUN_SHORTCUT = 'dr';

    /**
     * @var string
     */
    public const OPTION_FORCE_ALREADY_REEXPORTED = 'force';

    /**
     * @var string
     */
    public const OPTION_FORCE_ALREADY_REEXPORTED_SHORTCUT = 'f';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->addOption(
            static::OPTION_IDS,
            static::OPTION_IDS_SHORTCUT,
            InputOption::VALUE_OPTIONAL,
            'Run command only for given fk_sales_order ids',
        );

        $this->addOption(
            static::OPTION_STORE,
            static::OPTION_STORE_SHORTCUT,
            InputOption::VALUE_REQUIRED,
            'Run command only for given store',
        );

        $this->addOption(
            static::OPTION_RANGE,
            static::OPTION_RANGE_SHORTCUT,
            InputOption::VALUE_OPTIONAL,
            'Run command only for given fk_sales_order range. Example -rg|--range 1,20 to run from id 1 to id 20',
        );

        $this->addOption(
            static::OPTION_SYSTEM_CODE,
            static::OPTION_SYSTEM_CODE_SHORTCUT,
            InputOption::VALUE_OPTIONAL,
            'Override SystemCode in export data to hit correct target system',
        );

        $this->addOption(
            static::OPTION_DRY_RUN,
            static::OPTION_DRY_RUN_SHORTCUT,
            InputOption::VALUE_NONE,
            'Print export data to log instead of send data to middleware. Attention: the dry run writes customer data to log!',
        );

        $this->addOption(
            static::OPTION_FORCE_ALREADY_REEXPORTED,
            static::OPTION_FORCE_ALREADY_REEXPORTED_SHORTCUT,
            InputOption::VALUE_NONE,
            'Force export of already exported orders',
        );

        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filterTransfer = new JellyfishBufferTableFilterTransfer();

        if ($input->getOption(static::OPTION_STORE)) {
            $filterTransfer->setStore($input->getOption(static::OPTION_STORE));
        }

        if ($input->getOption(static::OPTION_IDS)) {
            $inputIds = $input->getOption(static::OPTION_IDS);
            $filterTransfer->setIds(explode(',', $inputIds));
        }

        if ($input->getOption(static::OPTION_RANGE)) {
            $inputRange = $input->getOption(static::OPTION_RANGE);
            $range = explode(',', $inputRange);
            if (count($range) !== 2) {
                throw new Exception(sprintf('The given range is mismatch... Please use format START,END - given "%s"', $inputRange));
            }
            $filterTransfer->setRangeFrom((int)$range[0]);
            $filterTransfer->setRangeFrom((int)$range[1]);
        }

        if ($input->getOption(static::OPTION_SYSTEM_CODE)) {
            $filterTransfer->setSystemCode($input->getOption(static::OPTION_SYSTEM_CODE));
        }

        $filterTransfer->setDryRun($input->getOption(static::OPTION_DRY_RUN));
        $filterTransfer->setForceReexport($input->getOption(static::OPTION_FORCE_ALREADY_REEXPORTED));

        return (int)$this->getFacade()->exportFromBufferTable($this->createConfig($filterTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishBufferTableFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ExportedOrderConfigTransfer
     */
    protected function createConfig(JellyfishBufferTableFilterTransfer $filterTransfer): ExportedOrderConfigTransfer
    {
        return (new ExportedOrderConfigTransfer())
            ->setFilter($filterTransfer)
            ->setUser((new UserTransfer())->setIdUser($this->getFactory()->getConfig()->getDefaultExportUserId()));
    }
}
