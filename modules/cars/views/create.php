<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Car Details
    </div>
    <div class="card-body">
        <form action="<?= $form_location ?>" method="post">
            <p>
                <label>Car Make</label>
                <input type="text" name="car_make" value="<?= $car_make ?>" autocomplete="off" placeholder="Enter Car Make">
            </p>
            <p>
                <button type="submit" name="submit" value="Submit">Submit</button>
                <?= anchor($cancel_url, 'Cancel', array('class' => 'button alt')) ?>
            </p>
        </form>
    </div>
</div>