<div class="card">
    <div class="card-heading">
        Associated <?= ucwords($associated_plural) ?>
    </div>
    <div class="card-body">
        <p id="<?= $associated_module ?>-create" style="display: none;">
            <button onclick="openModal('<?= $associated_module ?>-modal')">
                <i class="fa fa-exchange"></i> Associate With <?= ucwords($associated_singular) ?>
            </button>
        </p>

        <p>
	        <table>
	        	<tbody id="<?= $associated_module ?>-records"></tbody>
	        </table>
    	</p>
    </div>
</div>

<div class="modal" id="<?= $associated_module ?>-modal" style="display: none;">
    <div class="modal-heading"><i class="fa fa-exchange"></i> Associate With <?= ucwords($associated_singular) ?></div>
    <div class="modal-body">
    	<p>Select <?= $associated_singular ?> and then hit 'Associate'.</p>
    	<p><select id="<?= $associated_module ?>-dropdown" name="<?= $associated_module ?>-dropdown"></select></p>
    	<p>
            <button class="alt" type="button" onclick="closeModal()">Cancel</button>
            <button onclick="submitAssoc_<?= $rand_str ?>()">Associate With <?= ucwords($associated_singular) ?></button>
        </p>
    </div>
</div>

<div class="modal" id="<?= $associated_module ?>-disassociate-modal" style="display: none;">
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
	p#<?= $associated_module ?>-create {
		text-align: center;
		margin: 0 auto;
	}

	p#<?= $associated_module ?>-create button {
		margin: 0;
	}
</style>

<script>
var selectedRecordId_<?= $rand_str ?> = '';

function fetchAssociatedRecords_<?= $rand_str ?>() {

	params = {
	    '<?= $foreign_key ?>':<?= $update_id ?>,
        'orderBy': '<?= $identifier_column ?>'
	}

	var fetchUrl = '<?= $fetch_url ?>';

	const http = new XMLHttpRequest();
	http.open('post', fetchUrl);
	http.setRequestHeader('Content-type', 'application/json');
	http.setRequestHeader('trongateToken', '<?= $token ?>');
	http.send(JSON.stringify(params));

	http.onload = function() {
		drawRecords_<?= $rand_str ?>(JSON.parse(http.responseText));
	}

}

function drawRecords_<?= $rand_str ?>(results) {

	var targetTblId = '<?= $associated_module ?>-records';
	var targetTbl = document.getElementById(targetTblId);

	while (targetTbl.firstChild) {
	    targetTbl.removeChild(targetTbl.lastChild);
	}

	targetTbl.innerHTML = '';

	for (var i = 0; i < results.length; i++) {

		var recordId = results[i]["id"];
		var newTr = document.createElement("tr");
		var newTd = document.createElement("td");

		var tdText = document.createTextNode(results[i]["<?= $identifier_column ?>"]);
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

	populateDropdown_<?= $rand_str ?>();
}

function openDisassociateModal_<?= $rand_str ?>(recordId) {
	selectedRecordId_<?= $rand_str ?> = recordId;
	var targetModalId = '<?= $associated_module ?>-disassociate-modal';
	openModal(targetModalId);
}

function disassociate_<?= $rand_str ?>() {
	closeModal();

	var params = {
		<?= segment(1) ?>_id: 0
	}

	var disassociateUrl = '<?= $update_url ?>';
	disassociateUrl = disassociateUrl.replace('{id}', selectedRecordId_<?= $rand_str ?>);

	const http = new XMLHttpRequest();
	http.open('post', disassociateUrl);
	http.setRequestHeader('Content-type', 'application/json');
	http.setRequestHeader('trongateToken', '<?= $token ?>');
	http.send(JSON.stringify(params));
	http.onload = function() {
		fetchAssociatedRecords_<?= $rand_str ?>();
	}
}

function populateDropdown_<?= $rand_str ?>() {

	params = {
	    '<?= $foreign_key ?>':0,
        'orderBy': '<?= $identifier_column ?>'
	}

	var fetchAvailableOptionsUrl = '<?= $fetch_url ?>';

	const http = new XMLHttpRequest();
	http.open('post', fetchAvailableOptionsUrl);
	http.setRequestHeader('Content-type', 'application/json');
	http.setRequestHeader('trongateToken', '<?= $token ?>');
	http.send(JSON.stringify(params));

	http.onload = function() {
		//populate dropdown
		var results = JSON.parse(http.responseText);
		var associateBtnId = '<?= $associated_module ?>-create';
		var associateBtn = document.getElementById(associateBtnId);

		if (results.length>0) {
			associateBtn.style.display = 'block';
			var dropdownId = '<?= $associated_module ?>-dropdown';
			var targetDropdown = document.getElementById(dropdownId);

			while (targetDropdown.firstChild) {
			    targetDropdown.removeChild(targetDropdown.lastChild);
			}

			for (var i = 0; i < results.length; i++) {
				var newOption = document.createElement("option");
				newOption.setAttribute("value", results[i]["id"]);
				newOption.innerHTML = results[i]["<?= $identifier_column ?>"];
				targetDropdown.appendChild(newOption);
			}

		} else {
			associateBtn.style.display = 'none';
		}

	}
}

function submitAssoc_<?= $rand_str ?>() {
	closeModal();
	var dropdownId = '<?= $associated_module ?>-dropdown';
	var dropdown = document.getElementById(dropdownId);
	var dropdownValue = dropdown.value;

	var params = {
		<?= segment(1) ?>_id: <?= $update_id ?>
	}

	var createUrl = '<?= $update_url ?>';
	createUrl = createUrl.replace('{id}', dropdownValue);

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