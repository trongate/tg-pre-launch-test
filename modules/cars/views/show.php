<h1><?= $headline ?> <span class="smaller hide-sm">(Record ID: <?= $update_id ?>)</span></h1>
<?= flashdata() ?>
<div class="card">
    <div class="card-heading">
        Options
    </div>
    <div class="card-body">
        <?php 
        echo anchor('cars/manage', 'View All Cars', array("class" => "button alt"));
        echo anchor('cars/create/'.$update_id, 'Update Details', array("class" => "button"));
		$attr_delete = array( 
		    "class" => "danger go-right",
		    "id" => "btn-delete-modal",
		    "onclick" => "openModal('delete-modal')"
		);
		echo form_button('delete', 'Delete', $attr_delete);
        ?>
    </div>
</div>
<div class="two-col">
    <div class="card record-details">
        <div class="card-heading">
            Car Details
        </div>
        <div class="card-body">
            <div><span>Car Make</span><span><?= $car_make ?></span></div>
        </div>
    </div>
    <div class="card">
        <div class="card-heading">
            Comments
        </div>
        <div class="card-body">
            <div class="text-center">
                <p><?php
				$attr_comment = array( 
				    "class" => "alt",
				    "onclick" => "openModal('comment-modal')"
				);
                echo form_button('comment', 'Add New Comment', $attr_comment) ?></p>
                <div id="comments-block"><table></table></div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="comment-modal" style="display: none;">
    <div class="modal-heading"><i class="fa fa-commenting-o"></i> Add New Comment</div>
    <div class="modal-body">
        <p><textarea placeholder="Enter comment here..."></textarea></p>
        <p>
            <?php
			$attr_close = array( 
			    "class" => "alt",
			    "onclick" => "closeModal()"
			);
            echo form_button('close', 'Cancel', $attr_close);
            echo form_button('submit', 'Submit Comment', array("onclick" => "submitComment()"));
            ?>
        </p>
    </div>
</div>
<div class="modal" id="delete-modal" style="display: none;">
    <div class="modal-heading danger"><i class="fa fa-trash"></i> Delete Record</div>
    <div class="modal-body">
        <?= form_open('cars/submit_delete/'.$update_id) ?>        
        <p>Are you sure?</p>
        <p>You are about to delete a car record. This cannot be undone.  
        Do you really want to do this?</p>
        <p>
            <?php 
            echo form_button('close', 'Cancel', $attr_close);
            echo form_submit('submit', 'Yes - Delete Now', array("class" => 'danger'));
            ?>
        </p>
        <?= form_close() ?>
    </div>
</div>
<script>
var token = '<?= $token ?>';
var updateId = '<?= $update_id ?>';
var baseUrl = '<?= BASE_URL ?>';
</script>