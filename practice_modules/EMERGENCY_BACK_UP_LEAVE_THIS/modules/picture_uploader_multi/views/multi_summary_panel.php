<div class="card picture-gallery-card">
    <div class="card-heading">
        Picture Gallery
    </div>
    <div class="card-body">
        <p>
        	<?php
        	$btn_attr['class'] = 'button alt';
        	$gallery_uploader_url = 'picture_uploader_multi/uploader/'.$target_module.'/'.$update_id;
        	echo anchor($gallery_uploader_url, '<i class="fa fa-image"></i> UPLOAD PICTURES', $btn_attr) ?>
        </p>
        <div>
            <?php
            if (count($pictures) == 0) {
                echo '<p style="margin-top: 3em;">There are currently no gallery pictures for this record.</p>';
            } else {
            ?>
            <div id="gallery-pics">
                <?php
                foreach ($pictures as $picture) {
                    $picture_path = $target_directory.$picture;
                    echo '<div onclick="previewPic(\''.$picture_path.'\')">';
                    echo '<img src="'.$picture_path.'" alt="<?= $picture ?>">';
                    echo '</div>';
                }
                ?>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div class="modal" id="preview-pic-modal" style="display: none;">
    <div class="modal-heading"><i class="fa fa-image"></i> Picture Preview</div>
    <div class="modal-body"><p id="preview-pic"></p>
        <p class="w3-right modal-btns">
            <button onclick="closeModal()" type="button" name="submit" value="Submit" class="alt">CLOSE</button>
            <button onclick="ditchPreviewPic()" class="danger">
                <i class="fa fa-trash"></i> DELETE THIS PICTURE
            </button>               
        </p>
    </div>
</div>

<script>
    var picturePath = '';
    var pictureName = '';

    function previewPic(clickedPicture) {
        openModal('preview-pic-modal');
        document.getElementById('preview-pic-modal').style.display='block';
        var imageCode = '<img src="' + clickedPicture + '" >';
        document.getElementById('preview-pic').innerHTML=imageCode;
        picturePath = clickedPicture;
        var segmentArray = clickedPicture.split('/');
        pictureName = segmentArray[segmentArray.length-1];
    }

    function ditchPreviewPic() {
        closeModal();
        var removePicUrl = '<?= BASE_URL ?>picture_uploader_multi/upload/<?= $target_module ?>/<?= $update_id ?>';
        const http = new XMLHttpRequest();
        http.open('DELETE', removePicUrl);
        http.setRequestHeader('Content-type', 'application/json');
        http.setRequestHeader('trongateToken', '<?= $token ?>');
        http.send(pictureName);
        http.onload = function() {
            refreshPictures(http.responseText);
        }
    }

    function refreshPictures(pictures) {
        var pics = JSON.parse(pictures);
        var currentPicsHtml = '';
        var imageCode = '';
        for (var i = 0; i < pics.length; i++) {
            imageCode = '<div onclick="previewPic(\'<?= $target_directory ?>' + pics[i] + '\')">';
            imageCode+= '<img src="<?= $target_directory ?>' + pics[i] + '" alt="' + pics[i] + '"></div>';
            currentPicsHtml+=imageCode;
        }

        if (currentPicsHtml == '') {
            currentPicsHtml = '<p>There are currently no gallery pictures for this record.</p>';
            document.getElementById('gallery-pics').style.gridTemplateColumns = 'repeat(1, 1fr)';
        }
        document.getElementById('gallery-pics').innerHTML=currentPicsHtml;
    }
</script>