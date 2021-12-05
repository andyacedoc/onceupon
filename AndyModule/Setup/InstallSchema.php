<?php

namespace Amasty\AndyModule\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()
            ->newTable($setup->getTable('amasty_andymodule_blacklist'))
            ->addColumn(
                'black_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Blacklist ID'
            )
            ->addColumn(
                'sku',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                    'default' => ''
                ],
                'Blacklist Sku'
            )
            ->addColumn(
                'qty',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'default' => 0
                ],
                'Blacklist Qty'
            )
            ->setComment('Blacklist table');
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
