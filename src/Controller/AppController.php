<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $session = $this->request->getSession();
        $samlUserdata = $session->read('samlUserdata');

        $this->set('samlUserdata', $samlUserdata);

        $this->loadModel('Users');

        if ($samlUserdata) {
            $user = $this->Users->loggedinUser();

            $this->set('user', $user);
            $this->set('isLoggedin', true);
            if ($this->request->getParam('action') === 'home') {
                return $this->redirect('/users/my');
            }
        } else {
            if (!in_array($this->request->getParam('action'), [
                'home',
                'login',
                'logout2',
                'logout',
                'acs',
                'metadata'
            ], true) && !in_array($this->request->getParam('prefix'), [
                    'Api'
                ], true)) {
                return $this->redirect('/');
            }

            $this->set('isLoggedin', false);
        }
    }

    protected function requireBasicAuth() {
        $AUTH_USER = env('API_USER');
        $AUTH_PASS = env('API_PASSWORD');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        if ($is_not_authenticated) {
            header('HTTP/1.1 403 Forbidden');
            //header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }
    }
}
