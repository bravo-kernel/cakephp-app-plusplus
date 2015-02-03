<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    use \Crud\Controller\ControllerTrait;

    /**
     * @var Components available to all views
     */
     public $components = [
         'RequestHandler',
         'Flash',
         'Crud.Crud' => [
            'actions' => [
                'Crud.Index',
                'Crud.Add',
                'Crud.Edit',
                'Crud.View',
                'Crud.Delete'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ],
        'Paginator' => [
            'settings' => [
                'page' => 1,
                'limit' => 25,
                'maxLimit' => 100,
                'whitelist' => ['limit', 'sort', 'page', 'direction']
            ]
        ]
     ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {

    }

    /**
     * beforeFilter hook method.
     *
     * @return void
     */
    public function beforeFilter(Event $event) {

    }

}
