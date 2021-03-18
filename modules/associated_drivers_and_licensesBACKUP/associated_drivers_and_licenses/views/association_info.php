<div class="card">
    <div class="card-heading">
        Associated <?= ucwords($associated_plural) ?>
    </div>
    <div class="card-body">
        <p id="<?= $table_name ?>-create" style="display: none;">
            <button onclick="openModal('<?= $table_name ?>-modal')">
                <i class="fa fa-exchange"></i> Associate With <?= ucwords($associated_singular) ?>
            </button>
        </p>

        <p>
	        <table>
	        	<tbody id="<?= $table_name ?>-records"></tbody>
	        </table>
    	</p>
    </div>
</div>

<div class="modal" id="<?= $table_name ?>-modal" style="display: none;">
    <div class="modal-heading"><i class="fa fa-exchange"></i> Associate With <?= ucwords($associated_singular) ?></div>
    <div class="modal-body">
    	<p>Select <?= $associated_singular ?> and then hit 'Associate'.</p>
    	<p><select id="<?= $table_name ?>-dropdown" name="<?= $table_name ?>-dropdown"></select></p>
    	<p>
            <button class="alt" type="button" onclick="closeModal()">Cancel</button>
            <button onclick="submitAssoc_<?= $rand_str ?>()">Associate With <?= ucwords($associated_singular) ?></button>
        </p>
    </div>
</div>

<div class="modal" id="<?= $table_name ?>-disassociate-modal" style="display: none;">
    <div class="modal-heading danger"><i class="fa fa-ban"></i> Disassociate With <?= ucwords($associated_singular) ?></div>
    <div class="modal-body">
    	<h5>Confirm Disassociate</h5>
    	<p>You are about to remove an association.</p>
        <p>Do you really want to do this?</p>
    	<p>
            <button class="alt" type="button" onclick="closeModal()">Cancel</button>
            <button onclick="disassociate_<?= $rand_str ?>()" class="danger">
            	Yes - Disassociate Now!
            </button>
        </p>
    </div>
</div>

<style>
	p#<?= $table_name ?>-create {
		text-align: center;
		margin: 0 auto;
	}

	p#<?= $table_name ?>-create button {
		margin: 0;
	}
</style>

<script>
var selectedRecordId_<?= $rand_str ?> = '';

function fetchAssociatedRecords_<?= $rand_str ?>() {
	var fetchUrl = '<?= $fetch_url ?>';

	const http = new XMLHttpRequest();
	http.open('get', fetchUrl);
	http.setRequestHeader('Content-type', 'application/json');
	http.setRequestHeader('trongateToken', '<?= $token ?>');
	http.send();
	http.onload = function() {
		drawRecords_<?= $rand_str ?>(JSON.parse(http.responseText));
	}

}

function drawRecords_<?= $rand_str ?>(results) {

	var targetTblId = '<?= $table_name ?>-records';
	var targetTbl = document.getElementById(targetTblId);

	while (targetTbl.firstChild) {
	    targetTbl.removeChild(targetTbl.lastChild);
	}

	targetTbl.innerHTML = '';

	for (var i = 0; i < results.length; i++) {
		var recordId = results[i]["recordId"];
		var newTr = document.createElement("tr");
		var newTd = document.createElement("td");
		var tdText = document.createTextNode(results[i]["identifierColumn"]);
		newTd.appendChild(tdText);
		newTr.appendChild(newTd);
		var btnCell = document.createElement("td");

		var disBtn = document.createElement("button");
		disBtn.innerHTML = '<i class="fa fa-ban"></i> disassociate';
		disBtn.setAttribute("onclick", "openDisassociateModal_<?= $rand_str ?>(" + recordId + ")");
		disBtn.setAttribute("class", "danger");

		btnCell.appendChild(disBtn);
		newTr.appendChild(btnCell);
		targetTbl.appendChild(newTr);
	}

	populateDropdown_<?= $rand_str ?>(results);
}

function openDisassociateModal_<?= $rand_str ?>(recordId) {
	selectedRecordId_<?= $rand_str ?> = recordId;
	var targetModalId = '<?= $table_name ?>-disassociate-modal';
	openModal(targetModalId);
}

function disassociate_<?= $rand_str ?>() {
	closeModal();
	var disassociateUrl = '<?= BASE_URL ?>api/delete/associated_drivers_and_licenses/' + selectedRecordId_<?= $rand_str ?>;
	const http = new XMLHttpRequest();
	http.open('post', disassociateUrl);
	http.setRequestHeader('Content-type', 'application/json');
	http.setRequestHeader('trongateToken', '<?= $token ?>');
	http.send();
	http.onload = function() {
		//repopulate associated records
		selectedRecordId_<?= $rand_str ?> = '';
		fetchAssociatedRecords_<?= $rand_str ?>();
	}
}

function populateDropdown_<?= $rand_str ?>(results) {
	var fetchAvailableOptionsUrl = '<?= $fetch_available_options_url ?>';
	const http = new XMLHttpRequest();
	http.open('post', fetchAvailableOptionsUrl);
	http.setRequestHeader('Content-type', 'application/json');
	http.setRequestHeader('trongateToken', '<?= $token ?>');
	http.send(JSON.stringify(results));
	http.onload = function() {
		//repopulate available records
		var results = JSON.parse(http.responseText);
		var associateBtnId = '<?= $table_name ?>-create';
		var associateBtn = document.getElementById(associateBtnId);

		if (results.length>0) {
			associateBtn.style.display = 'block';
			var dropdownId = '<?= $table_name ?>-dropdown';
			var targetDropdown = document.getElementById(dropdownId);

			while (targetDropdown.firstChild) {
			    targetDropdown.removeChild(targetDropdown.lastChild);
			}

			for (var i = 0; i < results.length; i++) {
				var newOption = document.createElement("option");
				newOption.setAttribute("value", results[i]["key"]);
				newOption.innerHTML = results[i]["value"];
				targetDropdown.appendChild(newOption);
			}

		} else {
			associateBtn.style.display = 'none';
		}

	}
}

function submitAssoc_<?= $rand_str ?>() {
	var dropdownId = '<?= $table_name ?>-dropdown';
	var dropdown = document.getElementById(dropdownId);

	var params = {
		<?= $associated_module ?>_id: dropdown.value,
		<?= $calling_module ?>_id: <?= $update_id ?>
	}

	closeModal();
	var createUrl = '<?= $create_association_url ?>';
	const http = new XMLHttpRequest();
	http.open('post', createUrl);
	http.setRequestHeader('Content-type', 'application/json');
	http.setRequestHeader('trongateToken', '<?= $token ?>');
	http.send(JSON.stringify(params));
	http.onload = function() {
		fetchAssociatedRecords_<?= $rand_str ?>();
	}
}

//fetch associated records
window.onload = function() {
	fetchAssociatedRecords_<?= $rand_str ?>();
}
</script>