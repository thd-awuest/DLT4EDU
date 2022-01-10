<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Hello <?= $user->given_name ?> <?= $user->family_name ?></h1>
        <p class="col-md-8 fs-4">
            Your E-Mail is <?= $user->email ?>
            <?php

            /*if (!empty($samlUserdata)) {
                echo '<h2>Attributes</h2>';
                echo '<table><thead><th>Name</th><th>Values</th></thead><tbody>';
                foreach ($samlUserdata as $attributeName => $attributeValues) {
                    echo '<tr><td>' . htmlentities($attributeName) . '</td><td><ul>';
                    foreach ($attributeValues as $attributeValue) {
                        echo '<li>' . htmlentities($attributeValue) . '</li>';
                    }
                    echo '</ul></td></tr>';
                }
                echo '</tbody></table>';
            } else {
                echo 'No attributes found.';
            }*/

            ?>
        </p>
        <button class="btn btn-primary btn-lg" type="button">Example button</button>
    </div>
</div>
<div class="row align-items-md-stretch">
    <div class="col-md-6">
        <div class="h-100 p-5 text-white bg-dark rounded-3">
            <h2>Messages</h2>
            <?php if (count($messages) > 0) { ?>
                <table class="table table-dark table-striped table-hover messages">
                    <thead>
                    <tr>
                        <th scope="col">Sent</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Message</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($messages as $msg) { ?>
                    <tr>
                        <td><?= $msg->created ?></td>
                        <td><?= $msg->subject ?></td>
                        <td><?= (strlen($msg->body) > 20? substr($msg->body, 0, 17) . '...': $msg->body) ?></td>
                        <td><?= ($msg->attachment_id !== ''?'<i class="bi bi-paperclip"></i>':'&nbsp;') ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No messages so far. Hit the sync button below to refresh your Inbox!</p>
                <div class="col text-center">
                    <a href="<?= $this->Url->build([
                        'controller' => 'Enmeshed',
                        'action' => 'sync'
                    ]);?>" class="btn btn-primary">Sync Messages</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="h-100 p-5 bg-light border rounded-3">
            <div class="container text-center">
                <?php if (count($user->relationshiptemplates) === 0 || $user->relationshiptemplates[0]->accepted !== 'Y') { ?>
                <div class="row">
                    <div class="col text-center">
                        <h1>1. Authentication</h1>
                        <p>Open your enmeshed App and scan the QR code below.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center" id="qrcode">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <h1>2. Sync Profile</h1>
                        <p>After authentication, press Sync-Button.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center" id="qrcode">
                        <a href="<?= $this->Url->build([
                            'controller' => 'Enmeshed',
                            'action' => 'sync'
                        ]);?>" class="btn btn-primary">Sync Profile</a>
                    </div>
                </div>
                <?php } else { ?>
                    <?php
                    if (count($user->relationshiptemplates) > 1) {
                        // TODO: Create profile selection
                    }   else {
                    ?>
                <div class="row">
                    <div class="col text-center">
                        <h1>Onboarding already completed</h1>
                        <p>Open your enmeshed App and send/ receive messages.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <a href="<?= $this->Url->build([
                            'controller' => 'Enmeshed',
                            'action' => 'sync'
                        ]);?>" class="btn btn-primary">Sync Profile</a>
                    </div>
                </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script(['nmshd-connector']) ?>
<?= $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css') ?>
