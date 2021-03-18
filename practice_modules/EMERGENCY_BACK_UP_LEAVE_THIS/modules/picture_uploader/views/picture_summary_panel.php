<div class="card">
    <div class="card-heading">
        Picture
    </div>
    <div class="card-body">

        <?php
        if ($draw_picture_uploader == true) {

            echo '<div>';
            echo validation_errors();
            echo '<p>Please choose a picture from your computer and then press \'Upload\'.</p>';
            echo form_open_upload($form_location);
            echo form_file_select('picture');
            echo form_submit('submit', 'Upload');
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
            echo '<p style="text-align: center;">';
            echo '<img src="'.$picture_path.'" alt="main item picture" style="height: auto; max-width: 450px;">';
            echo '</p>';
            include('delete_picture_modal.php');
        }  
        ?>
    </div>
</div>