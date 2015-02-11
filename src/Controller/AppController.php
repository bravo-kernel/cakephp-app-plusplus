<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Plugin;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Tools\Controller\Controller;

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
     * @var Components available to all controllers
     */
     public $components = [
         //'Shim.Session',
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
        ],
        'Tools.Common',
        'Tools.Flash',
        'Tools.AuthUser'
     ];

     /**
      * @var Helpers available to all views
      */
      public $helpers = [
          //'Html',
          'Tools.Form',
          'Tools.Common',
          'Tools.Flash',
          'Tools.Format',
          'Tools.Time',
          'Tools.Number',
          'Tools.AuthUser',
          'Tools.Obfuscate',
          'Tools.Js'
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
        parent::initialize();
        if (Configure::read('Security.Authentication.enabled')) {
            $this->setupAuth();
        }
    }

    /**
     * beforeFilter hook method.
     *
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // Only enable Tiny Authorization if enabled in app_custom.php
        if (Configure::read('Security.Authorization.enabled')) {
            $this->Auth->authorize = array('Tools.Tiny');
        }

        // Do not allow access to these public actions when already logged in
        $allowed = [
            'Accounts' => ['login', 'lost_password', 'register']
        ];
        if (!$this->AuthUser->id()) {
            return;
        }

        foreach ($allowed as $controller => $actions) {
            if ($this->name === $controller && in_array($this->request->action, $actions)) {
                $this->Flash->message('The page you tried to access is not relevant if you are already logged in. Redirected to main page.', 'info');
                return $this->redirect($this->Auth->config('loginRedirect'));
            }
        }
    }


    protected function setupAuth() {

        $authConfig = [
            'authenticate' => [
                'FOC/Authenticate.MultiColumn' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ],
                    //'columns' => ['username', 'email'],
                    'columns' => Configure::read('Security.Authentication.identificationColumns'),
                    'userModel' => 'Users',
                    'passwordHasher' => Configure::read('Passwordable.passwordHasher')
                    //'scope' => array('User.email_confirmed' => 1)
                ]
            ],
            'logoutRedirect' => [
                'plugin' => false,
                'admin' => false,
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ],
            'loginRedirect' => [
                'plugin' => false,
                'admin' => false,
                'controller' => 'Users',
                'action' => 'index',
                #'page' => 'home'
            ],
            'loginAction' => [
                'plugin' => false,
                'admin' => false,
                'prefix' => false,      // true will break /api
                'controller' => 'Accounts',
                'action' => 'login'
            ],
            // triggered when page is not authorized
            'unauthorizedRedirect' => [
                'plugin' => false,
                'admin' => false,
                'prefix' => false,
                'controller' => 'Pages',
                'action' => 'display',
                'unauthorized'
            ]
        ];

        if (Configure::read('Security.Authorization.enabled')) {
            $authConfig['authorize'] = [
                'TinyAuth.Tiny'
            ];
        }

        $this->loadComponent('Auth', $authConfig);
    }

    /**
     * Catch unauthenticated or unauthorized requests so we can throw 403
     * errors rendering json/xml for api requests.
     *
     * @param string $url
     * @param int $status
     * @param bool $exit
     * @return void
     */
     public function redirect($url, $status = null, $exit = true) {
         if ($this->request->is('api')) {
             throw new ForbiddenException();
         }
         return parent::redirect($url, $status, $exit);
     }

}
