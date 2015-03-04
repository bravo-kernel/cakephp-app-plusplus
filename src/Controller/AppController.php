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

        // no special action neeeded
        if ($this->request->is('api')) {
            return;
        }

        // Disallow access to actions that don't make sense after being logged in.
        $allowed = [
            'Accounts' => ['login', 'lost_password', 'register']
        ];
        if (!$this->AuthUser->id()) {
            return;
        }

        // disallow access to actions that don't make sense after being logged in
        foreach ($allowed as $controller => $actions) {
            if ($this->name === $controller && in_array($this->request->action, $actions)) {
                 $this->Flash->message('The page you tried to access is not relevant if you are already logged in. Redirected to main page.', 'info');
                 return $this->redirect($this->Auth->config('loginRedirect'));
            }
        }
    }

    /**
     * Used to configure authentication
     *
     * @return void
     */
    protected function setupAuth() {

        $authConfig = [
            'authenticate' => [
                'FOC/Authenticate.MultiColumn' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ],
                    'columns' => Configure::read('Security.Authentication.identificationColumns'),
                    'userModel' => 'Users',
                    'passwordHasher' => Configure::read('Passwordable.passwordHasher')
                    //'scope' => array('User.email_confirmed' => 1)
                ]
            ],
            // page to redirect to after user logout
            'logoutRedirect' => [
                'plugin' => false,
                'admin' => false,
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ],
            // page to redirect to after succesful authentication/login
            'loginRedirect' => [
                'plugin' => false,
                'admin' => false,
                'controller' => 'Users',
                'action' => 'index',
                #'page' => 'home'
            ],
            // page shown for unauthenticated user accessing non-public action
            'loginAction' => [
                'plugin' => false,
                'admin' => false,
                'prefix' => false,      // true will break /api
                'controller' => 'Accounts',
                'action' => 'login'
            ],
            // page shown for authenticated user without page authorization
            'unauthorizedRedirect' => [
                'plugin' => false,
                'admin' => false,
                'prefix' => false,
                'controller' => 'Pages',
                'action' => 'display',
                'unauthorized'
            ]
        ];

        // Optionally enable JSON Web Token (JWT) authentication and set it as
        // the primary authentication mechanism, FoC used as fallback.
        if (Configure::read('Api.jwt')) {
            $authConfig['authenticate'] = array_merge([
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => '_token',
                    'userModel' => 'Users',
                    //'scope' => ['Users.active' => 1],
                    'fields' => [
                        'id' => 'id'
                    ]
                ]
            ], $authConfig['authenticate']);
        }

        // Enable Authorization using app_custom.php setting
        if (Configure::read('Security.Authorization.enabled')) {
            $authConfig['authorize'] = [
                'TinyAuth.Tiny' => [
                    'autoClearCache' => true,  // true to generate new acl on every request
                    'allowUser' => false,      // true to allow user access to all non-admin-prefixed resources
                    'allowAdmin' => false,     // true to allow adminRole (id) access to all admin-prefixed resources
                    'adminRole' => 'admin',    // required in combination with allowAdmin
                    'superAdminRole' => null,   // id of role with access to ALL resources
                    'rolesTable' => Configure::read('Security.Authorization.rolesTable')
                ]
            ];

            // Use non-default role column using app_custom.php setting (single-role only)
            if (Configure::read('Security.Authorization.roleColumn')) {
                $authConfig['authorize']['TinyAuth.Tiny']['roleColumn'] = Configure::read('Security.Authorization.roleColumn');
            }

            // Enable multirole using app_custom.php setting
            if (Configure::read('Security.Authorization.multiRole')) {
                $authConfig['authorize']['TinyAuth.Tiny']['multiRole'] = true;
            }
        }

        // Load Auth and/or Authorize
        $this->loadComponent('Auth', $authConfig);
    }

    /**
     * Catch unauthenticated or unauthorized requests so we can render 403
     * json/xml errors for api requests.
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
