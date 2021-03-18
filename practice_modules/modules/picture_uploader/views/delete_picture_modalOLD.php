<p class="w3-center">
    <button onclick="openDeletePicModal()" class="w3-button w3-red w3-hover-black w3-border">
        <i class="fa fa-trash-o"></i> DELETE PICTURE
    </button>
</p>

<div id="delete-picture-modal" class="w3-modal w3-center" style="padding-top: 7em;">
    <div class="w3-modal-content w3-animate-bottom w3-card-4" style="width: 30%;">
        <header class="w3-container w3-red w3-text-white">
            <h4><i class="fa fa-trash-o"></i> DELETE PICTURE</h4>
        </header>
        <div class="w3-container">
            <?= form_open($form_location) ?>                          
                <h5>Are you sure?</h5>
                <p>You are about to delete the picture.  This cannot be undone. <br>
                    Do you really want to do this?</p>
                <p class="w3-right modal-btns">
                    <button onclick="document.getElementById('delete-picture-modal').style.display='none'" type="button" name="submit" value="Submit" class="w3-button w3-small 3-white w3-border">CANCEL</button> 
                    <button type="submit" name="submit" value="Submit" class="w3-button w3-small w3-red w3-hover-black">
                        YES - DELETE NOW!
                    </button> 
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
</div>

<script>
    function openDeletePicModal() {
        document.getElementById("delete-picture-modal").style.display = 'block';
    }
</script>