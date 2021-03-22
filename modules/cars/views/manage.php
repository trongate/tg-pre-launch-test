<h1><?= $headline ?></h1>
<?php
flashdata();
echo '<p>'.anchor('cars/create', 'Create New Car Record', array('class' => 'button')).'</p>';
echo Pagination::display($pagination_data);
if (count($rows)>0) { ?>
    <table id="results-tbl">
        <thead>
            <tr>
                <th colspan="2">
                    <div>
                        <div><?php
                        $form_attr['method'] = 'get';
                        $searchphrase_attr = array( 
                            "autocomplete" => "off",
                            "placeholder" => "Search records..."
                        );
                        echo form_open('cars/manage/1/', $form_attr); 
                        echo form_input('searchphrase', '', $searchphrase_attr);
                        echo form_submit('submit', 'SEARCH', array("class" => "alt"));
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
                <td><?= anchor('cars/show/'.$row->id, 'View', array("class" => "button alt")) ?></td>
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