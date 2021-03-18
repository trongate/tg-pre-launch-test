<div class="w3-third w3-container">    
        <div class="w3-card-4 edit-block">
            <div class="w3-container primary">
                <h4>Associated <?= ucwords($associated_records_plural) ?></h4>
            </div>
            <div class="w3-container w3-center edit-block-content">
                <p id="<?= $assoc_module ?>-create" style="display: none;">
                    <button onclick="openModal('<?= $assoc_module ?>-modal')" class="w3-button w3-white w3-border">
                        <i class="fa fa-exchange"></i> ASSOCIATE WITH <?= strtoupper($associated_records_singular) ?>
                    </button>
                </p>

                <div id="<?= $assoc_module ?>-modal" class="w3-modal">
                    <div class="w3-modal-content w3-animate-top w3-card-4">
                        <header class="w3-container primary w3-text-white">
                            <h4><i class="fa fa-exchange"></i> ASSOCIATE WITH <?= strtoupper($associated_records_singular) ?></h4>
                        </header>
                        <div class="w3-container">
                            <p class="w3-left">Please select a <?= $associated_records_singular ?> and then hit 'Associate'.</p>
                            <p>
                                <select id="<?= $assoc_module ?>-dropdown" name="<?= $assoc_module ?>-dropdown" class="w3-select w3-border w3-sand">
                                </select>        
                            </p>
                            <p class="w3-right modal-btns">
                                <button onclick="closeModal('<?= $assoc_module ?>-modal')" class="w3-button w3-small 3-white w3-border">
                                    CANCEL
                                </button>
                                <button onclick="submitAssoc('<?= $assoc_module ?>', '<?= $calling_module ?>')" class="w3-button w3-small primary">
                                    ASSOCIATE WITH <?= strtoupper($associated_records_singular) ?>
                                </button> 
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <div id="<?= $assoc_module ?>-summary"></div>
                    <div id="<?= $assoc_module ?>-disassociate-modal" class="w3-modal w3-center">
                        <div class="w3-modal-content w3-animate-bottom w3-card-4">
                            <header class="w3-container w3-red w3-text-white">
                                <h4><i class="fa fa-ban"></i> DISASSOCIATE RECORD</h4>
                            </header>
                            <div class="w3-container">                         
                                <h5>Confirm Disassociate</h5>
                                <p>You are about to remove an association.</p>
                                <p>Do you really want to do this?</p>
                                <p class="w3-right modal-btns">
                                    <button onclick="document.getElementById('<?= $assoc_module ?>-disassociate-modal').style.display='none'" type="button" name="submit" value="Submit" class="w3-button w3-small 3-white w3-border">CANCEL</button> 
                                    <button onclick="disassociate('<?= $assoc_module ?>')" id="<?= $assoc_module ?>-disassociateBtn" type="submit" name="submit" value="" class="w3-button w3-small w3-red w3-hover-black">YES - DISASSOCIATE NOW!</button> 
                                </p>
                            </div>
                        </div>
                    </div><!-- end of <?= $assoc_module ?>-disassociate-modal -->
                </div>
            </div>
        </div>
    </div>