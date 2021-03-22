<h1><?= $headline ?></h1>

<?php
flashdata();
    
        $attr = array( 
            "btn-view-all" => array (
                "class" => "button alt",
                "id" => "btn-view-all"
            ),
            "btn-update" => array (
                "class" => "button",
                "id" => "btn-update"
            ),
            "btn-delete-modal" => array (
                "class" => "danger go-right",
                "id" => "btn-delete-modal",
                "onclick" => "openModal('delete-modal')"
            ),
            "btn-comment" => array (
                "class" => "alt",
                "id" => "btn-comment",
                "onclick" => "openModal('comment-modal')"
            ),
            "btn-close" => array (
                "class" => "alt",
                "onclick" => "closeModal()"
            ),
            "btn-submit-comment" => array (
                "onclick" => "submitComment()"
            ),
            "btn-delete" => array (
                "class" => "danger"
            )
        );

        echo '<p>'.anchor('cars/create', 'Create New Car Record', $attr['btn-update']).'</p>';

echo Pagination::display($pagination_data);
if (count($rows)>0) { ?>
    <table id="results-tbl">
        <thead>
            <tr>
                <th colspan="2">
                    <div>
                        <div><?php
                        $form_attr['method'] = 'get';
                        $searchphrase_attr['autocomplete'] = 'off';
                        $searchphrase_attr['placeholder'] = 'Search records...';
                        $search_btn_attr['class'] = 'alt';
                        echo form_open('cars/manage/1/', $form_attr); 
                        echo form_input('searchphrase', '', $searchphrase_attr);
                        echo form_submit('submit', 'SEARCH', $search_btn_attr);
                        echo form_close();
                        ?></div>
                        
                        <div>Records Per Page: <?php
                        echo form_dropdown('per_page', $per_page_options, $selected_per_page); 
                        ?></div>
                    </div>                    
                </th>
            </tr>
            <tr>
                <th>Car Make</th>
                <th>Action</th>            
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($rows as $row) { ?>
            <tr>
                <td><?= $row->car_make ?></td>
                <td><?= anchor('cars/show/'.$row->id, 'View', $attr['btn-view-all']) ?></td>        
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php 
    if(count($rows)>9) {
        unset($pagination_data['include_showing_statement']);
        echo Pagination::display($pagination_data);
    }
}
?>

<style>
    #results-tbl > thead > tr:nth-child(2) > th:nth-child(2) {
        width: 20px;
    }

    #results-tbl > tbody > tr > td:nth-child(2) > a {
        margin: 4px;
    }
</style>