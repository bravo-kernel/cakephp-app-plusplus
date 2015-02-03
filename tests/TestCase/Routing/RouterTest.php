<?php
namespace App\Test\TestCase\Routing;

use Cake\Core\Plugin;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;

/**
 * RouterTest class
 *
 */
class RouterTest extends TestCase
{

    /**
     * Test API prefixed REST routes.
     *
     * @return void
     */
    public function testApiPrefixRoutes()
    {
        Router::extensions(['json', 'xml'], false);

        // Index
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $expected = [
            'pass' => [],
            'plugin' => null,
            'controller' => 'Users',
            'action' => 'index',
            'prefix' => 'api',
            '_method' => 'GET'
        ];

        $result = Router::parse('api/users');
        $this->assertEquals($expected, $result);

        $result = Router::parse('api/users.json');
        $expected['_ext'] = 'json';
        $this->assertEquals($expected, $result);

        $result = Router::parse('api/users.xml');
        $expected['_ext'] = 'xml';
        $this->assertEquals($expected, $result);

        // View
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $expected = [
            'pass' => ['13'],
            'plugin' => null,
            'controller' => 'Users',
            'action' => 'view',
            'prefix' => 'api',
            'id' => '13',
            '_method' => 'GET'
        ];
        $result = Router::parse('api/users/13');
        $this->assertEquals($expected, $result);

        $result = Router::parse('api/users/13.json');
        $expected['_ext'] = 'json';
        $this->assertEquals($expected, $result);

        $result = Router::parse('api/users/13.xml');
        $expected['_ext'] = 'xml';
        $this->assertEquals($expected, $result);

        // Add
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $expected = [
            'pass' => [],
            'plugin' => null,
            'controller' => 'Users',
            'action' => 'add',
            'prefix' => 'api',
            '_method' => 'POST'
        ];

        $result = Router::parse('/api/users');
        $this->assertEquals($expected, $result);

        $result = Router::parse('api/users.json');
        $expected['_ext'] = 'json';
        $this->assertEquals($expected, $result);

        $result = Router::parse('api/users.xml');
        $expected['_ext'] = 'xml';
        $this->assertEquals($expected, $result);

        // Delete
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $expected = [
            'pass' => ['13'],
            'plugin' => null,
            'controller' => 'Users',
            'action' => 'delete',
            'prefix' => 'api',
            'id' => '13',
            '_method' => 'DELETE'
        ];
        $result = Router::parse('/api/users/13');
        $this->assertEquals($expected, $result);

        $result = Router::parse('api/users/13.json');
        $expected['_ext'] = 'json';
        $this->assertEquals($expected, $result);

        $result = Router::parse('api/users/13.xml');
        $expected['_ext'] = 'xml';
        $this->assertEquals($expected, $result);

    }
}
