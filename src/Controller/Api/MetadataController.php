<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Http\Client;
use App\Controller\AppController;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class MetadataController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->requireBasicAuth();
    }

    /*
     * Dynamic Database API JSON / XML Example
     * */
    public function messages() {

        $this->loadModel('Messages');

        $messages = $this->Messages->find('all');

        $this->set('messages', $messages);
        // Specify which view vars JsonView should serialize.
        $this->viewBuilder()->setOption('serialize', 'messages');

    }

    /*
     * Static API XML Example programs
     * */
    public function programs() {

        header('Content-Type: application/xml; charset=utf-8');

        readfile(ROOT . '/resources/programs.xml');

        exit;
    }


    /*
     * Static API XML Example academia
     * */
    public function academia() {

        header('Content-Type: application/xml; charset=utf-8');

        readfile(ROOT . '/resources/academia.xml');

        exit;
    }

}
