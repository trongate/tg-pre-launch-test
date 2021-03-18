<div class="card">
    <div class="card-heading">
        Associated <?= ucwords($associated_plural) ?>
    </div>
    <div class="card-body card-create-relation">
        <p id="<?= $relation_name ?>-create" style="display: none;">
            <button onclick="openModal('<?= $relation_name ?>-modal')">
                <i class="fa fa-exchange"></i> Associate With <?= ucwords($associated_singular) ?>
            </button>
        </p>
        <p>
            <table>
                <tbody id="<?= $relation_name ?>-records"></tbody>
            </table>
        </p>
    </div>
</div>

<div class="modal" id="<?= $relation_name ?>-modal" style="display: none;">
    <div class="modal-heading"><i class="fa fa-exchange"></i> Associate With <?= ucwords($associated_singular) ?></div>
    <div class="modal-body">
        <p>Select <?= $associated_singular ?> and then hit 'Associate'.</p>
        <p><select id="<?= $relation_name ?>-dropdown" name="<?= $relation_name ?>-dropdown"></select></p>
        <p>
            <button class="alt" type="button" onclick="closeModal()">Cancel</button>
            <button onclick="submitCreateAssociation('<?= $relation_name ?>')">Associate With <?= ucwords($associated_singular) ?></button>
        </p>
    </div>
</div>

<div class="modal" id="<?= $relation_name ?>-disassociate-modal" style="display: none;">
    <div class="modal-heading danger"><i class="fa fa-ban"></i> Disassociate With <?= ucwords($associated_singular) ?></div>
    <div class="modal-body">
        <h5>Confirm Disassociate</h5>
        <p>You are about to remove an association.</p>
        <p>Do you really want to do this?</p>
        <?php 
        $input_attr['id'] = $relation_name.'-record-to-go';
        echo form_hidden('record_id', '', $input_attr); 
        ?>
        <p>
            <button class="alt" type="button" onclick="closeModal()">Cancel</button>
            <button onclick="disassociate('<?= $relation_name ?>')" class="danger">
                Yes - Disassociate Now!
            </button>
        </p>
    </div>
</div>

<script>
window.onload = function() {
    fetchAssociatedRecords('<?= $relation_name ?>', '<?= $update_id ?>');
}
</script>