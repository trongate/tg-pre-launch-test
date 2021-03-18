<div class="w3-third w3-container">    
        <div class="w3-card-4 edit-block" style="margin-top: 1em;">
            <div class="w3-container primary">
                <h4>xPicture</h4>
            </div>
            <div class="edit-block-content">
                <?php
                if ($draw_picture_uploader == true) {

                    echo '<div style="padding: 1em;">';
                    echo validation_errors();
                    echo '<p>Please choose a picture from your computer and then press \'Upload\'.</p>';
                    echo form_open_upload($form_location);
                    echo form_file_select('picture');

                    $attributes['class'] = 'w3-button w3-medium primary';
                    echo form_submit('submit', 'Upload', $attributes);
                    echo form_hidden('update_id', $update_id);
                    echo form_hidden('targetModule', $targetModule);
                    echo form_hidden('maxFileSize', $maxFileSize);
                    echo form_hidden('maxWidth', $maxWidth);
                    echo form_hidden('maxHeight', $maxHeight);
                    echo form_hidden('resizedMaxWidth', $resizedMaxWidth);
                    echo form_hidden('resizedMaxHeight', $resizedMaxHeight);
                    echo form_hidden('destination', $destination);
                    echo form_hidden('targetColumnName', $targetColumnName);
                    echo form_hidden('thumbnailDir', $thumbnailDir);
                    echo form_hidden('thumbnailMaxWidth', $thumbnailMaxWidth);
                    echo form_hidden('thumbnailMaxHeight', $thumbnailMaxHeight);
                    echo form_close();
                    echo '</div>';
                } else {
                    include('delete_picture_modal.php'); 
                ?>
                    <p style="text-align: center; margin-top: 2.6em;">
                        <img src="<?= $picture_path ?>" alt="main item picture" style="max-width: 450px;">
                    </p>

                <?php
                }    
                ?>
            </div>
        </div>
    </div>