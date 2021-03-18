<h1>&lt;?= $headline ?&gt; <span class="smaller">(Record ID: &lt;?= $update_id ?&gt;)</span></h1>
&lt;?= flashdata() ?&gt;
<div class="card">
    <div class="card-heading">
        Options
    </div>
    <div class="card-body">
        <a href="&lt;?= BASE_URL ?&gt;drivers/manage"><button class="alt" id="view-all-btn">View All Drivers</button></a>
        <a href="&lt;?= BASE_URL ?&gt;drivers/create/&lt;?= $update_id ?&gt;"><button id="update-btn">Update Details</button></a>
        <button class="danger float-right" id="delete-btn" onclick="openModal('delete-modal')">Delete</button>
    </div>
</div>

<div class="two-col">
    <div class="card record-details">
        <div class="card-heading">
            Driver Details
        </div>
        <div class="card-body">
            <div><span>First Name</span><span>&lt;?= $first_name ?&gt;</span></div>
            <div><span>Last Name</span><span>&lt;?= $last_name ?&gt;</span></div>
        </div>
    </div>
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

<div class="modal" id="delete-modal" style="display: none;">
    <div class="modal-heading danger"><i class="fa fa-trash"></i> Delete Record</div>
    <div class="modal-body">
        &lt;?= form_open('drivers/submit_delete/'.$update_id) ?&gt;        
        <p>Are you sure?</p>
        <p>You are about to delete a driver record. This cannot be undone.  
        Do you really want to do this?</p>
        <p>
            <button class="alt" type="button" onclick="closeModal()">Cancel</button>
            <button class="danger" type="submit" name="submit" value="Submit">Yes - Delete Now</button>
        </p>
        &lt;?= form_close() ?&gt;
    </div>
</div>

