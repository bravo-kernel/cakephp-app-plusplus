<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;

// if (!Configure::read('debug')):
//     throw new NotFoundException();
// endif;

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('simplegrid.css') ?>
    <?= $this->Html->css('app++.css') ?>
</head>
<body class="home">
    <header style="height:35%">
        <div class="header" style="text-align:center; padding-top: 30px;">
            <?= $this->Html->image('http://cakephp.org/img/cake-logo.png') ?>
            <h1>Application Skeleton++</h1>
        </div>
    </header>
    <!-- <div id="content"> -->
        <?php
        if (Configure::read('debug')):
            Debugger::checkSecurityKeys();
        endif;
        ?>
        <p id="url-rewriting-warning" style="background-color:#e32; color:#fff;display:none">
            URL rewriting is not properly configured on your server.
            1) <a target="_blank" href="http://book.cakephp.org/3.0/en/installation/url-rewriting.html" style="color:#fff;">Help me configure it</a>
            2) <a target="_blank" href="http://book.cakephp.org/3.0/en/development/configuration.html#general-configuration" style="color:#fff;">I don't / can't use URL rewriting</a>
        </p>

        <div class="grid grid-pad">
            <div class="col-1-2">
                <div class="platform checks">
                    <p class="caption">CakePHP</p>
                    <p class="success">Running CakePHP <?= Configure::version(); ?></p>
                    <?php if (version_compare(PHP_VERSION, '5.4.16', '>=')): ?>
                        <p class="success">Your version of PHP is 5.4.16 or higher.</p>
                    <?php else: ?>
                        <p class="problem">Your version of PHP is too low. You need PHP 5.4.16 or higher to use CakePHP.</p>
                    <?php endif; ?>
                    <?php if (extension_loaded('mbstring')): ?>
                        <p class="success">Your version of PHP has the mbstring extension loaded.</p>
                    <?php else: ?>
                        <p class="problem">Your version of PHP does NOT have the mbstring extension loaded.</p>;
                    <?php endif; ?>

                    <?php if (extension_loaded('openssl')): ?>
                        <p class="success">Your version of PHP has the openssl extension loaded.</p>
                    <?php elseif (extension_loaded('mcrypt')): ?>
                        <p class="success">Your version of PHP has the mcrypt extension loaded.</p>
                    <?php else: ?>
                        <p class="problem">Your version of PHP does NOT have the openssl or mcrypt extension loaded.</p>
                    <?php endif; ?>

                    <?php if (extension_loaded('intl')): ?>
                        <p class="success">Your version of PHP has the intl extension loaded.</p>
                    <?php else: ?>
                        <p class="problem">Your version of PHP does NOT have the intl extension loaded.</p>
                    <?php endif; ?>

                    <?php if (is_writable(TMP)): ?>
                        <p class="success">Your tmp directory is writable.</p>
                    <?php else: ?>
                        <p class="problem">Your tmp directory is NOT writable.</p>
                    <?php endif; ?>

                    <?php if (is_writable(LOGS)): ?>
                        <p class="success">Your logs directory is writable.</p>
                    <?php else: ?>
                        <p class="problem">Your logs directory is NOT writable.</p>
                    <?php endif; ?>

                    <?php $settings = Cache::config('_cake_core_'); ?>
                    <?php if (!empty($settings)): ?>
                        <p class="success">The <em><?= $settings['className'] ?>Engine</em> is being used for core caching.</p>
                    <?php else: ?>
                        <p class="problem">Your cache is NOT working. Please check the settings in config/app.php</p>
                    <?php endif; ?>

                    <?php
                        try {
                            $connection = ConnectionManager::get('default');
                            $connected = $connection->connect();
                        } catch (Exception $connectionError) {
                            $connected = false;
                            $errorMsg = $connectionError->getMessage();
                            if (method_exists($connectionError, 'getAttributes')):
                                $attributes = $connectionError->getAttributes();
                                if (isset($errorMsg['message'])):
                                    $errorMsg .= '<br />' . $attributes['message'];
                                endif;
                            endif;
                        }
                    ?>
                    <?php if ($connected): ?>
                        <p class="success">CakePHP is able to connect to the database.</p>
                    <?php else: ?>
                        <p class="problem">CakePHP is NOT able to connect to the database.<br /><br /><?= $errorMsg ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-1-2">
                <div class="filesystem checks">
                    <p class="caption">App++</p>

                        <?php if (Configure::read('Security.Authentication.enabled')): ?>
                            <p class="success">TinyAuth authentication is enabled</p>
                            <p class="success">TinyAuth is using identification column(s)
                                    <?php foreach (Configure::read('Security.Authentication.identificationColumns') as $column): ?>
                                        <?= $column ?>
                                    <?php endforeach; ?>
                            </p>

                            <?php if (Configure::read('Security.Authorization.enabled')): ?>
                                <p class="success">TinyAuth authorization is enabled</p>
                                <p class="success">TinyAuth authorization is using roles
                                <?php foreach (Configure::read('Roles') as $role => $id): ?>
                                    <?= $role ?>
                                <?php endforeach; ?>
                                </p>
                            <?php else: ?>
                                <p class="problem">TinyAuth authorization is not enabled.</p>
                            <?php endif; ?>

                        <?php else: ?>
                            <p class="problem">TinyAuth authentictaion is not enabled.</p>
                        <?php endif; ?>
                    </p>

                    <?php if (Configure::read('Admin.enabled')): ?>
                        <p class="success">ADMIN prefix route is enabled</p>
                    <?php else: ?>
                        <p class="problem">ADMIN prefix route is not enabled.</p>
                    <?php endif; ?>

                    <?php if (Configure::read('Api.enabled')): ?>
                        <p class="success">API prefix route is enabled</p>
                        <p class="success">API is serving
                                <?php foreach (Configure::read('Api.extensions') as $extension): ?>
                                    <?= $extension ?>
                                <?php endforeach; ?>
                        </p>
                    <?php else: ?>
                        <p class="problem">API prefix route is not enabled.</p>
                    <?php endif; ?>
                </div>

                <?php if (Configure::read('Security.Authentication.enabled')): ?>
                    <div class="filesystem checks">
                        <?php if (isset($authUser['id'])): ?>
                            <p class="caption">You are logged in.</p>
                            <div><?php echo $this->Html->link(__('Logout'), ['controller' => 'Accounts', 'action' => 'logout']); ?></div>
                        <?php else: ?>
                            <p class="caption">You are not logged in.</p>
                            <div><?php echo $this->Html->link(__('Login'), ['controller' => 'Accounts', 'action' => 'login']); ?></div>
                            <div><?php echo $this->Html->link(__('Register'), ['controller' => 'Accounts', 'action' => 'register']); ?></div>
                        <?php endif; ?>
                    </div>



                <?php endif; ?>

            </div>
        </div>
        <!-- </div> -->

    <!-- </div> -->

    <footer>
    </footer>
</body>
</html>
