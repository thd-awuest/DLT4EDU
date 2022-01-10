<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Routing\Router;
use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Error;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\UnauthorizedException;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class UsersController extends AppController
{
    public function login() {

        Configure::load('saml_settings', 'default');

        $settingsInfo = Configure::read("saml2");
        //debug($settingsInfo);

        $auth = new \OneLogin\Saml2\Auth($settingsInfo);

        $ssoBuiltUrl = $auth->login(null, array(), false, false, true);

        $session = $this->request->getSession();
        $session->write('AuthNRequestID', $auth->getLastRequestID());

        header('Pragma: no-cache');
        header('Cache-Control: no-cache, must-revalidate');
        header('Location: ' . $ssoBuiltUrl);
        exit();


    }


    public function metadata() {

        Configure::load('saml_settings', 'default');

        $settingsInfo = Configure::read("saml2");

        try {
            $auth = new Auth($settingsInfo);
            $settings = $auth->getSettings();
            $metadata = $settings->getSPMetadata();
            $errors = $settings->validateMetadata($metadata);
            if (empty($errors)) {
                header('Content-Type: text/xml');
                echo $metadata;
            } else {
                throw new Error(
                    'Invalid SP metadata: '.implode(', ', $errors),
                    Error::METADATA_SP_INVALID
                );
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        exit;
    }

    public function acs() {

        if ($this->request->is('post')) {
            Configure::load('saml_settings', 'default');

            $settingsInfo = Configure::read("saml2");

            $auth = new \OneLogin\Saml2\Auth($settingsInfo);

            $session = $this->request->getSession();

            $requestID = $session->read('AuthNRequestID');
            unset($_SESSION['AuthNRequestID']);

            $auth->processResponse($requestID);

            $errors = $auth->getErrors();

            if (!empty($errors)) {
                echo '<p>' . implode(', ', $errors) . '</p>';
                exit();
            }

            if (!$auth->isAuthenticated()) {
                throw new UnauthorizedException("Unauthorized");
            }


            $session->write('samlUserdata', $auth->getAttributes());
            $session->write('samlNameId', $auth->getNameId());
            $session->write('samlNameIdFormat', $auth->getNameIdFormat());
            $session->write('samlNameidNameQualifier', $auth->getNameIdNameQualifier());
            $session->write('samlNameidSPNameQualifier', $auth->getNameIdSPNameQualifier());
            $session->write('samlSessionIndex', $auth->getSessionIndex());

            // Check if user already exists
            // if not, persist user
            $userExists = $this->Users->find('all', ['conditions' => ['Users.email' => $auth->getAttributes()['urn:oid:0.9.2342.19200300.100.1.3'][0]]])->first();

            $usersTable = $this->getTableLocator()->get('Users');
            $user = $usersTable->newEmptyEntity();

            if (!$userExists) {
                $user->daad_uid = $auth->getNameId();
                $user->email = $auth->getAttributes()['urn:oid:0.9.2342.19200300.100.1.3'][0];
                $user->family_name = $auth->getAttributes()['urn:oid:2.5.4.4'][0];
                $user->given_name = $auth->getAttributes()['urn:oid:2.5.4.42'][0];

                $usersTable = $this->getTableLocator()->get('Users');
                $usersTable->save($user);

                // Request Relationshiptemplate and store it to
                $this->fetchTable('Relationshiptemplates')->ownRelationshiptemplateForUser($user);
            }

            // redirect to profile
            return $this->redirect(['controller' => 'Users', 'action' => 'my']);

        } else {
            throw new ForbiddenException("not allowed");
        }


    }

    public function logout() {

        Configure::load('saml_settings', 'default');

        $settingsInfo = Configure::read("saml2");

        $auth = new \OneLogin\Saml2\Auth($settingsInfo);

        $session = $this->request->getSession();

        $this->layout = false;
        $this->autoRender = false;

        $requestID = $session->read('LogoutRequestID');

        $auth->processSLO(false, $requestID);

        $errors = $auth->getErrors();

        if (empty($errors)) {
            $this->Flash->success('Sucessfully logged out');
        } else {
            $this->Flash->error(implode(', ', $errors));
        }

        header('Pragma: no-cache');
        header('Cache-Control: no-cache, must-revalidate');
        header('Location: ' . Router::url('/', true));
        exit();
    }

    public function logout2() {

        Configure::load('saml_settings', 'default');

        $settingsInfo = Configure::read("saml2");

        $auth = new \OneLogin\Saml2\Auth($settingsInfo);

        $session = $this->request->getSession();
        $session->write('LogoutRequestID', $auth->getLastRequestID());
        $nameId = $session->read('samlNameId');
        $sessionIndex = $session->read('samlSessionIndex');

        $sloBuiltUrl = $auth->logout(null, array(), $nameId, $sessionIndex, true);

        header('Pragma: no-cache');
        header('Cache-Control: no-cache, must-revalidate');
        header('Location: ' . $sloBuiltUrl);
        exit();

    }

    public function my() {
        $user = $this->Users->loggedinUser();
        $messages = null;
        if ($user->relationshiptemplates[0]->peer) {
            $msgTable = $this->getTableLocator()->get('Messages');
            $messages = $msgTable->find('all', ['conditions' => ['Messages.peer' => $user->relationshiptemplates[0]->peer]])->all()->toArray();
        }
        $this->set('messages', $messages);
    }
}
