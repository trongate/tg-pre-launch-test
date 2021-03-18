<h1><?= $headline ?> <span class="smaller">(Record ID: <?= $update_id ?>)</span></h1>
<?= flashdata() ?>
<div class="card">
    <div class="card-heading">
        Options
    </div>
    <div class="card-body">
        <a href="<?= BASE_URL ?>members/manage"><button class="alt" id="view-all-btn">View All MEMBERS</button></a>
        <a href="<?= BASE_URL ?>members/create/<?= $update_id ?>"><button id="update-btn">Update Details</button></a>
        <button class="danger float-right" id="delete-btn" onclick="openModal('delete-modal')">Delete</button>
    </div>
</div>

<div class="three-col">
    <div class="card record-details">
        <div class="card-heading">
            Member Details
        </div>
        <div class="card-body">
            <div><span>First Name</span><span><?= $first_name ?></span></div>
            <div><span>Last Name</span><span><?= $last_name ?></span></div>
        </div>
    </div>
    <?= Modules::run('picture_uploader_multi/_draw_summary_panel', $update_id, $picture_uploader_multi_settings) ?>
<div class="card">
        <div class="card-heading">
            Comments
        </div>
        <div class="card-body">
            <div class="text-center">
                <p><button class="alt" onclick="openModal('comment-modal')" id="comment-btn">Add New Comment</button></p>
                <div id="comments-block"><table></table></div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="comment-modal">
    <div class="modal-heading"><i class="fa fa-commenting-o"></i> Add New Comment</div>
    <div class="modal-body"><p><textarea placeholder="Enter comment here..."></textarea></p><p class="float-right"><button class="alt" type="button" onclick="closeModal()">Cancel</button><button type="submit" name="submit" value="Submit" onclick="submitComment()">Add Comment</button></p></div>
</div>

<div class="modal" id="delete-modal">
    <div class="modal-heading danger"><i class="fa fa-trash"></i> Delete Record</div>
    <div class="modal-body">
        <?= form_open('members/submit_delete/'.$update_id) ?>
        <p>Are you sure?</p>
        <p>You are about to delete a member record. This cannot be undone.  
        Do you really want to do this?</p>
        <p>
            <button class="alt" type="button" onclick="closeModal()">Cancel</button>
            <button class="danger" type="submit" name="submit" value="Submit">Yes - Delete Now</button>
        </p>
        </form>    </div>
</div>

<script>
var token = '<?= $token ?>';

var viewBtn = document.getElementById("view-all-btn");
var viewBtnContent = '<i class="fa fa-list-alt"></i> ' + viewBtn.innerHTML;
viewBtn.innerHTML = viewBtnContent;

var updateBtn = document.getElementById("update-btn");
var updateBtnContent = '<i class="fa fa-pencil"></i> ' + updateBtn.innerHTML;
updateBtn.innerHTML = updateBtnContent;

var deleteBtn = document.getElementById("delete-btn");
var deleteBtnContent = '<i class="fa fa-trash"></i> ' + deleteBtn.innerHTML;
deleteBtn.innerHTML = deleteBtnContent;

var updateBtn = document.getElementById("comment-btn");
var updateBtnContent = '<i class="fa fa-commenting-o"></i> ' + updateBtn.innerHTML;
updateBtn.innerHTML = updateBtnContent;

var commentsBlock = document.getElementById("comments-block");
var commentsTbl = document.querySelector("#comments-block > table");

function submitComment() {
    var textarea = document.querySelector("#comment-modal > div.modal-body > p:nth-child(1) > textarea");
    var comment = textarea.value.trim();
    
    if (comment == "") {
        return;
    } else {
        textarea.value = '';
        closeModal();

        var params = {
            comment,
            target_table: '<?= segment(1) ?>',
            update_id: '<?= $update_id ?>'
        }

        var targetUrl = '<?= BASE_URL ?>api/create/tg_comments';
        const http = new XMLHttpRequest();
        http.open('post', targetUrl);
        http.setRequestHeader('Content-type', 'application/json');
        http.setRequestHeader('trongateToken', '<?= $token ?>');
        http.send(JSON.stringify(params));

        http.onload = function() {

            if (http.status == 401) {
                //invalid token!
                window.location.href = '<?= BASE_URL ?>tg_administrators/login';
            } else if(http.status == 200) {
                fetchComments();
            }

        }

    }

}

function fetchComments() {

    var params = {
        target_table: '<?= segment(1) ?>',
        update_id: '<?= $update_id ?>',
        orderBy: 'date_created'
    }

    var targetUrl = '<?= BASE_URL ?>api/get/tg_comments';
    const http = new XMLHttpRequest();
    http.open('post', targetUrl);
    http.setRequestHeader('Content-type', 'application/json');
    http.setRequestHeader('trongateToken', '<?= $token ?>');
    http.send(JSON.stringify(params));

    http.onload = function() {
        if (http.status == 401) {
            //invalid token!
            window.location.href = '<?= BASE_URL ?>tg_administrators/login';
        } else if(http.status == 200) {

            while (commentsTbl.firstChild) {
                commentsTbl.removeChild(commentsTbl.lastChild);
            }

            var comments = JSON.parse(http.responseText);
            for (var i = 0; i < comments.length; i++) {
                var tblRow = document.createElement("tr");
                var tblCell = document.createElement("td");
                var pDate = document.createElement("p");
                var pText = document.createTextNode(comments[i]['date_created']);
                pDate.appendChild(pText);
                var pComment = document.createElement("p");
                var commentText = comments[i]['comment'];
                pComment.innerHTML = commentText;

                tblCell.appendChild(pDate);
                tblCell.appendChild(pComment);
                tblRow.appendChild(tblCell);
                commentsTbl.appendChild(tblRow);
                commentsBlock.appendChild(commentsTbl);
            }
        }
    }
}

fetchComments();

</script>