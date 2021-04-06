<?php

namespace Amasty\KrexModule\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable($setup->getTable('amasty_krexmodule_blacklist'))
            ->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Product ID'
            )
            ->addColumn(
                'sku',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                    'default' => ''
                ],
                'Product SKU'
            )
            ->addColumn(
                'qty',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Product quantity'
            )
            ->setComment('Product blacklist');
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
