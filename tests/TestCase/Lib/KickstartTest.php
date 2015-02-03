<?php
namespace App\Test\TestCase\Routing;

use App\Lib\Kickstart;
use Cake\TestSuite\TestCase;

/**
 * RouterTest class
 *
 */
class KickstartTest extends TestCase
{

    /**
     * Test listing Controllers found in /src/Controller/Api
     *
     * @return void
     */
    public function testGetApiControllers()
    {
        // clear cache
        if (file_exists(TMP . 'cache' . DS . 'cake_api_controllers')) {
            unlink(TMP . 'cache' . DS . 'cake_api_controllers');
        }

        // create dummy controller files
        $controllerDir = APP . 'Controller' . DS . 'Api';
        touch ("$controllerDir/ApiTest1Controller.php");
        touch ("$controllerDir/ApiTest2Controller.php");

        $result = Kickstart::getApiControllers();
        $this->assertContains('ApiTest1', $result);
        $this->assertContains('ApiTest2', $result);

        // verify cache functioning properly
        unlink ("$controllerDir/ApiTest1Controller.php");
        $result = Kickstart::getApiControllers();
        $this->assertContains('ApiTest1', $result);

        unlink ("$controllerDir/ApiTest2Controller.php");
    }

}
