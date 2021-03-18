<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Laptop Details
    </div>
    <div class="card-body">
        <form action="<?= $form_location ?>" method="post">
            
        <p>
            <label>Laptop Title</label>
            <input type="text" name="laptop_title" value="<?= $laptop_title ?>" autocomplete="off" placeholder="Enter Laptop Title">
        </p>
            <p>
                <a href="<?= $cancel_url ?>" class="button alt">Cancel</a>
                <button type="submit" name="submit" value="Submit">Submit</button>
            </p>
        </form>
    </div>
</div>