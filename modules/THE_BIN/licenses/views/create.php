<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        License Details
    </div>
    <div class="card-body">
        <form action="<?= $form_location ?>" method="post">
            <p>
                <label>License Number</label>
                <input type="text" name="license_number" value="<?= $license_number ?>" autocomplete="off" placeholder="Enter License Number">
            </p>
            <p>
	            <label>Drivers ID</label>
	            <?= form_dropdown('drivers_id', $drivers_options, $drivers_id) ?>
	        </p>
            <p>
                <a href="<?= $cancel_url ?>" class="button alt">Cancel</a>
                <button type="submit" name="submit" value="Submit">Submit</button>
            </p>
        </form>
    </div>
</div>