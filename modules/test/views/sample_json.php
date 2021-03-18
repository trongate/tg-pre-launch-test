{"action":"generateModuleRelation","allLocalStorage":{"bridgingTblRequired":"false","localFrameworkPath":"C:\\xampp\\htdocs\\football","secondModule":"licenses","firstModule":"drivers","apiBaseUrl":"http://localhost/trongate_live3/","relationshipType":"one to many","firstModuleIdentifierColumn":"last_name","dbSettings":"{\"host\":\"localhost\",\"user\":\"root\",\"password\":\"\",\"database\":\"football\"}","secondModuleIdentifierColumn":"license_number"},"localFrameworkPath":"C:\\xampp\\htdocs\\football","showFileContentsFirstModule":"<h1><?= $headline ?> <span class=\"smaller\">(Record ID: <?= $update_id ?>)</span></h1>\n<?= flashdata() ?>\n<div class=\"card\">\n    <div class=\"card-heading\">\n
Options\n    </div>\n    <div class=\"card-body\">\n        <a href=\"<?= BASE_URL ?>drivers/manage\"><button class=\"alt\" id=\"view-all-btn\">View All Drivers</button></a>\n        <a href=\"<?= BASE_URL ?>drivers/create/<?= $update_id ?>\"><button id=\"update-btn\">Update Details</button></a>\n        <button class=\"danger float-right\" id=\"delete-btn\" onclick=\"openModal('delete-modal')\">Delete</button>\n    </div>\n</div>\n\n<div class=\"two-col\">\n    <div class=\"card record-details\">\n        <div class=\"card-heading\">\n            Driver Details\n        </div>\n        <div class=\"card-body\">\n            <div><span>First Name</span><span><?= $first_name ?></span></div>\n            <div><span>Last Name</span><span><?= $last_name ?></span></div>\n        </div>\n    </div>\n    <div class=\"card\">\n        <div class=\"card-heading\">\n            Comments\n        </div>\n        <div class=\"card-body\">\n
  <div class=\"text-center\">\n                <p><button class=\"alt\" onclick=\"openModal('comment-modal')\" id=\"comment-btn\">Add New Comment</button></p>\n                <div id=\"comments-block\"><table></table></div>\n
</div>\n        </div>\n    </div>\n</div>\n\n<div class=\"modal\" id=\"comment-modal\">\n    <div class=\"modal-heading\"><i class=\"fa fa-commenting-o\"></i> Add New Comment</div>\n    <div class=\"modal-body\"><p><textarea placeholder=\"Enter comment here...\"></textarea></p><p class=\"float-right\"><button class=\"alt\" type=\"button\" onclick=\"closeModal()\">Cancel</button><button type=\"submit\" name=\"submit\" value=\"Submit\" onclick=\"submitComment()\">Add Comment</button></p></div>\n</div>\n\n<div class=\"modal\" id=\"delete-modal\" style=\"display: none;\">\n    <div class=\"modal-heading danger\"><i class=\"fa fa-trash\"></i> Delete Record</div>\n    <div class=\"modal-body\">\n        <?= form_open('drivers/submit_delete/'.$update_id) ?>        \n        <p>Are you sure?</p>\n        <p>You are about to delete a driver record. This cannot be undone.  \n        Do you really want to do this?</p>\n        <p>\n            <button class=\"alt\" type=\"button\" onclick=\"closeModal()\">Cancel</button>\n            <button class=\"danger\" type=\"submit\" name=\"submit\" value=\"Submit\">Yes - Delete Now</button>\n        </p>\n        <?= form_close() ?>\n    </div>\n</div>\n\n<script>\nvar token = '<?= $token ?>';\nvar segment1 = '<?= segment(1) ?>';\nvar updateId = '<?= $update_id ?>';\nvar baseUrl = '<?= BASE_URL ?>';\n</script>","showFileContentsSecondModule":"<h1><?= $headline ?> <span class=\"smaller\">(Record ID: <?= $update_id ?>)</span></h1>\n<?= flashdata() ?>\n<div class=\"card\">\n    <div class=\"card-heading\">\n        Options\n    </div>\n    <div class=\"card-body\">\n        <a href=\"<?= BASE_URL ?>licenses/manage\"><button class=\"alt\" id=\"view-all-btn\">View All LICENSES</button></a>\n        <a href=\"<?= BASE_URL ?>licenses/create/<?= $update_id ?>\"><button id=\"update-btn\">Update Details</button></a>\n        <button class=\"danger float-right\" id=\"delete-btn\" onclick=\"openModal('delete-modal')\">Delete</button>\n    </div>\n</div>\n\n<div class=\"two-col\">\n    <div class=\"card record-details\">\n        <div class=\"card-heading\">\n            License Details\n        </div>\n        <div class=\"card-body\">\n            <div><span>License Number</span><span><?= $license_number ?></span></div>\n        </div>\n    </div>\n    <div class=\"card\">\n        <div class=\"card-heading\">\n
      Comments\n        </div>\n        <div class=\"card-body\">\n            <div class=\"text-center\">\n                <p><button class=\"alt\" onclick=\"openModal('comment-modal')\" id=\"comment-btn\">Add New Comment</button></p>\n
               <div id=\"comments-block\"><table></table></div>\n            </div>\n        </div>\n    </div>\n</div>\n\n<div class=\"modal\" id=\"comment-modal\">\n    <div class=\"modal-heading\"><i class=\"fa fa-commenting-o\"></i> Add New Comment</div>\n    <div class=\"modal-body\"><p><textarea placeholder=\"Enter comment here...\"></textarea></p><p class=\"float-right\"><button class=\"alt\" type=\"button\" onclick=\"closeModal()\">Cancel</button><button type=\"submit\" name=\"submit\" value=\"Submit\" onclick=\"submitComment()\">Add Comment</button></p></div>\n</div>\n\n<div class=\"modal\" id=\"delete-modal\" style=\"display: none;\">\n    <div class=\"modal-heading danger\"><i class=\"fa fa-trash\"></i> Delete Record</div>\n    <div class=\"modal-body\">\n        <?= form_open('licenses/submit_delete/'.$update_id) ?>        \n        <p>Are you sure?</p>\n        <p>You are about to delete a license record. This cannot be undone.  \n        Do you really want to do this?</p>\n        <p>\n            <button class=\"alt\" type=\"button\" onclick=\"closeModal()\">Cancel</button>\n            <button class=\"danger\" type=\"submit\" name=\"submit\" value=\"Submit\">Yes - Delete Now</button>\n        </p>\n        <?= form_close() ?>\n    </div>\n</div>\n\n<script>\nvar token = '<?= $token ?>';\nvar segment1 = '<?= segment(1) ?>';\nvar updateId = '<?= $update_id ?>';\nvar baseUrl = '<?= BASE_URL ?>';\n</script>","firstModuleSingular":"driver","firstModulePlural":"drivers","secondModuleSingular":"license","secondModulePlural":"licenses","relationshipType":"one to many","firstModuleName":"drivers","secondModuleName":"licenses","firstModuleIdentifierColumn":"last_name","secondModuleIdentifierColumn":"license_number","bridgingTblRequired":"true","dbSettings":{"host":"localhost","user":"root","password":"","database":"football"},"oldContent":"<h1><?= $headline ?> <span class=\"smaller\">(Record ID: <?= $update_id ?>)</span></h1>\n<?= flashdata() ?>\n<div class=\"card\">\n    <div class=\"card-heading\">\n        Options\n    </div>\n    <div class=\"card-body\">\n        <a href=\"<?= BASE_URL ?>drivers/manage\"><button class=\"alt\" id=\"view-all-btn\">View All Drivers</button></a>\n        <a href=\"<?= BASE_URL ?>drivers/create/<?= $update_id ?>\"><button id=\"update-btn\">Update Details</button></a>\n        <button class=\"danger float-right\" id=\"delete-btn\" onclick=\"openModal('delete-modal')\">Delete</button>\n    </div>\n</div>\n\n<div class=\"two-col\">\n    <div class=\"card record-details\">\n        <div class=\"card-heading\">\n            Driver Details\n        </div>\n        <div class=\"card-body\">\n            <div><span>First Name</span><span><?= $first_name ?></span></div>\n            <div><span>Last Name</span><span><?= $last_name ?></span></div>\n        </div>\n    </div>\n
    <div class=\"card\">\n        <div class=\"card-heading\">\n            Comments\n        </div>\n        <div class=\"card-body\">\n            <div class=\"text-center\">\n                <p><button class=\"alt\" onclick=\"openModal('comment-modal')\" id=\"comment-btn\">Add New Comment</button></p>\n                <div id=\"comments-block\"><table></table></div>\n            </div>\n        </div>\n    </div>\n</div>\n\n<div class=\"modal\" id=\"comment-modal\">\n
   <div class=\"modal-heading\"><i class=\"fa fa-commenting-o\"></i> Add New Comment</div>\n    <div class=\"modal-body\"><p><textarea placeholder=\"Enter comment here...\"></textarea></p><p class=\"float-right\"><button class=\"alt\" type=\"button\" onclick=\"closeModal()\">Cancel</button><button type=\"submit\" name=\"submit\" value=\"Submit\" onclick=\"submitComment()\">Add Comment</button></p></div>\n</div>\n\n<div class=\"modal\" id=\"delete-modal\" style=\"display: none;\">\n    <div class=\"modal-heading danger\"><i class=\"fa fa-trash\"></i> Delete Record</div>\n    <div class=\"modal-body\">\n        <?= form_open('drivers/submit_delete/'.$update_id) ?>        \n        <p>Are you sure?</p>\n
    <p>You are about to delete a driver record. This cannot be undone.  \n        Do you really want to do this?</p>\n        <p>\n            <button class=\"alt\" type=\"button\" onclick=\"closeModal()\">Cancel</button>\n            <button class=\"danger\" type=\"submit\" name=\"submit\" value=\"Submit\">Yes - Delete Now</button>\n        </p>\n        <?= form_close() ?>\n    </div>\n</div>\n\n<script>\nvar token = '<?= $token ?>';\nvar segment1 = '<?= segment(1) ?>';\nvar updateId = '<?= $update_id ?>';\nvar baseUrl = '<?= BASE_URL ?>';\n</script>","firstModule":"drivers","secondModule":"licenses","targetModule":"firstModule","code":"MRL_PLEASE_REWRITE"}