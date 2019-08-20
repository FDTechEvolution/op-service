<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrgsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrgsTable Test Case
 */
class OrgsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrgsTable
     */
    public $Orgs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Orgs',
        'app.Bpartners',
        'app.Brands',
        'app.Customers',
        'app.OrgSettings',
        'app.ProductCategories',
        'app.Products',
        'app.ShipmentInouts',
        'app.Users',
        'app.Warehouses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Orgs') ? [] : ['className' => OrgsTable::class];
        $this->Orgs = TableRegistry::getTableLocator()->get('Orgs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Orgs);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
