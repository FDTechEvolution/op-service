<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductsFixture
 */
class ProductsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'org_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'product_category_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'name' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'code' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'cost' => ['type' => 'decimal', 'length' => 10, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => '0.00', 'comment' => ''],
        'price' => ['type' => 'decimal', 'length' => 10, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => '0.00', 'comment' => ''],
        'brand_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'description' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'createdby' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modifiedby' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'isactive' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => 'Y', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'status' => ['type' => 'string', 'length' => 3, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'MyISAM',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => '4abac5b3-d714-4e4b-b573-ddf235068f9f',
                'org_id' => '1881df8c-b981-4484-8cfb-4c72fdd1a869',
                'product_category_id' => '3453bcc4-d1d2-40ca-9b9f-3a6f2f1ead51',
                'name' => 'Lorem ipsum dolor sit amet',
                'code' => 'Lorem ipsum dolor sit amet',
                'cost' => 1.5,
                'price' => 1.5,
                'brand_id' => 'cfa02291-2200-45f8-bbb4-a854f59dafb4',
                'description' => 'Lorem ipsum dolor sit amet',
                'created' => '2019-09-10 05:12:45',
                'modified' => '2019-09-10 05:12:45',
                'createdby' => '6b093d31-3deb-41da-a614-92afdf5131a7',
                'modifiedby' => '9a41663b-ac45-447b-a1fb-0f61878e6f08',
                'isactive' => 'Lorem ipsum dolor sit amet',
                'status' => 'L'
            ],
        ];
        parent::init();
    }
}
