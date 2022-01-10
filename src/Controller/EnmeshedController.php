<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Client;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class EnmeshedController extends AppController
{
    public function access() {

        header('Content-Type: image/png');

        $user = $this->Users->loggedinUser();

        $url = env('LOCAL_ENMESHED_URL') . '/api/v1/RelationshipTemplates/Own/' . $user->relationshiptemplates[0]['token'] . '/Token';

        $http = new Client();
        $response = $http->post($url, [],
            [
                'headers' => ['X-API-KEY' => env('ENMESHED_API_KEY'), 'accept' => 'image/png'],
            ]
        );

        $this->layout = false;
        $this->autoRender = false;

        echo $response->getBody();
        exit;

    }

    public function sync() {

        $url = env('LOCAL_ENMESHED_URL') . '/api/v1/Account/Sync';
        $http = new Client();
        $response = $http->post($url, [],
            [
                'headers' => ['X-API-KEY' => env('ENMESHED_API_KEY')],
            ]
        );

        $answer = $response->getJson();

        // save new request
        if(count($answer['result']['relationships']) > 0 || count($answer['result']['messages']) > 0) {

            $usersTable = $this->getTableLocator()->get('Users');
            $relTable = $this->getTableLocator()->get('Relationshiptemplates');
            $msgTable = $this->getTableLocator()->get('Messages');

            // Handle messages
            foreach ($answer['result']['messages'] as $msg) {
                $new_msg = $msgTable->newEmptyEntity();
                $new_msg->message_id = $msg['id'];
                $new_msg->subject = $msg['content']['subject'];
                $new_msg->body = $msg['content']['body'];
                $new_msg->peer = $msg['createdBy'];
                $new_msg->attachment_id = implode(',', $msg['attachments']);
                $msgTable->save($new_msg);
            }

            // Handle relationships
            foreach ($answer['result']['relationships'] as $rel) {
                foreach ($rel['changes'] as $change) {
                    switch ($change['type']) {
                        case 'Creation':
                            $content = $change['request']['content'];

                            $user = $usersTable->find('all', ['conditions' => ['Users.daad_uid' => $content['metadata']['webSessionId']]])->first();
                            $relationship = $relTable->find('all', ['conditions' => ['Relationshiptemplates.user_id' => $user->id]])->first();

                            $user->nmshd_request = (string)json_encode($answer);
                            $user->nmshd_relationship_id = $rel['id'];
                            $user->nmshd_template_id = $rel['template']['id'];
                            $user->nmshd_rel_id = $rel['id'];
                            $user->nmshd_change_id = $change['id'];

                            $relationship->relationship_id = $rel['id'];
                            $relationship->change_id = $change['id'];
                            $relationship->peer = $rel['peer'];

                            if (!$relationship->accepted) {

                                $urlAccept = env('LOCAL_ENMESHED_URL') . '/api/v1/Relationships/' . $rel['id'] . '/Changes/' . $change['id'] . '/Accept';

                                $response = $http->put($urlAccept, '{"content": {}}',
                                    [
                                        'headers' => ['X-API-KEY' => env('ENMESHED_API_KEY'), 'accept' => 'application/json', 'Content-Type' => 'application/json'],
                                        'type' => 'json'
                                    ]
                                );

                                // check response status code
                                $user->nmshd_accept = 0;
                                $relationship->accepted = 'N';
                                if ($response->isOk()) {
                                    $user->nmshd_accept = 1;
                                    $relationship->accepted = 'Y';
                                }
                                $user->nmshd_accept_response = (string)json_encode($response->getBody());

                            }

                            $usersTable->save($user);
                            $relTable->save($relationship);

                            break;

                        default:
                            echo 'ERROR! Unknown change type';
                    }
                }
            }


        }

        $this->redirect(['controller' => 'Users', 'action' => 'my']);
    }

    public function send() {

        $url = env('LOCAL_ENMESHED_URL') . '/api/v1/Messages';
        $http = new Client();
        $response = $http->post($url, '{
              "recipients": [
                "id_________________________________"
              ],
              "content": {
                "@type": "Mail",
                "to": [
                  "id_________________________________"
                ],
                "cc": [
                  "id_________________________________"
                ],
                "subject": "Subject",
                "body": "Body"
              },
              "attachments": [
                "FIL_________________"
              ]
            }',
            [
                'headers' => ['X-API-KEY' => env('ENMESHED_API_KEY'), 'accept' => 'application/json', 'Content-Type' => 'application/json'],
                'type' => 'json'
            ]
        );

        $this->redirect(['controller' => 'Users', 'action' => 'my']);

    }
}
