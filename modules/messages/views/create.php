<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Messages Details
    </div>
    <div class="card-body">
        <form action="<?= $form_location ?>" method="post">
            <p>
                <label>Date Created</label>
                <input type="text" name="date_created" value="<?= $date_created ?>" autocomplete="off" placeholder="Enter Date Created">
            </p>
            <p>
                <label>Sent From</label>
                <input type="text" name="sent_from" value="<?= $sent_from ?>" autocomplete="off" placeholder="Enter Sent From">
            </p>
            <p>
                <label>Sent To</label>
                <input type="text" name="sent_to" value="<?= $sent_to ?>" autocomplete="off" placeholder="Enter Sent To">
            </p>
            <p>
                <label>Message Subject</label>
                <input type="text" name="message_subject" value="<?= $message_subject ?>" autocomplete="off" placeholder="Enter Message Subject">
            </p>
            <p>
                <label>Message Body</label>
                <textarea name="message_body" placeholder="Enter Message Body here..."><?= $message_body ?></textarea>
            </p>
            <p>
                <label><?= form_checkbox('opened', 1, $checked=$opened) ?>Opened</label>
            </p>
            <p>
                <label><?= form_checkbox('from_admin', 1, $checked=$from_admin) ?>From Admin</label>
            </p>
            <p>
                <a href="<?= $cancel_url ?>" class="button alt">Cancel</a>
                <button type="submit" name="submit" value="Submit">Submit</button>
            </p>
        </form>
    </div>
</div>