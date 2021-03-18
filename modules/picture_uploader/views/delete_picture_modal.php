<p style="text-align: center;">
    <button onclick="openModal('delete-picture')" class="danger">
        <i class="fa fa-trash-o"></i> DELETE PICTURE
    </button>
</p>

<div class="modal" id="delete-picture" style="background-color: pink; display: none;">
	<div class="modal-heading danger">
		<i class="fa fa-trash"></i> Delete Picture
	</div>
	<div class="modal-body">
		<?= form_open($form_location) ?>
			<p>Are you sure?</p>
			<p>You are about to delete the picture.  This cannot be undone. <br>
                    Do you really want to do this?</p>
			<p>
				<button class="alt" type="button" onclick="closeModal()">Cancel</button>
				<button class="danger" type="submit" name="submit" value="Submit">Yes - Delete Now</button>
			</p>
        <?php 
        echo form_hidden('picture_name', $picture_name);
        echo form_hidden('calling_module', $calling_module);
        echo form_hidden('targetColumnName', $targetColumnName);
        echo form_hidden('destination', $destination);
        echo form_hidden('thumbnailDir', $thumbnailDir);
        echo form_close();
        ?>
	</div>
</div>