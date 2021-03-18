<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Member Details
    </div>
    <div class="card-body">
        <form action="<?= $form_location ?>" method="post">
            
        <p>
            <label>First Name</label>
            <input type="text" name="first_name" value="<?= $first_name ?>" autocomplete="off" placeholder="Enter First Name">
        </p>
        <p>
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?= $last_name ?>" autocomplete="off" placeholder="Enter Last Name">
        </p>
            <p>
                <a href="<?= $cancel_url ?>" class="button alt">Cancel</a>
                <button type="submit" name="submit" value="Submit">Submit</button>
            </p>
        </form>
    </div>
</div>