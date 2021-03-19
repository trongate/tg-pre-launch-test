<h1><?= $headline ?></h1>

<div class="bg-marker">x</div>

<?php
flashdata();
$btn_attr['class'] = 'button';
$btn_attr['id'] = 'create-btn';
echo '<p>'.anchor('cars/create', 'Create New Car Record', $btn_attr).'</p>'; 
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
                        echo form_submit('submit', 'Search', $search_btn_attr);
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
                <th style="width: 20px;">Action</th>            
            </tr>
        </thead>
        <tbody>
            <?php 
            $attr['class'] = 'button alt';
            foreach($rows as $row) { ?>
            <tr>
                <td><?= $row->car_make ?></td>
                <td><?= anchor('cars/show/'.$row->id, 'View', $attr) ?></td>        
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
<script>
var resultsTbl = document.querySelector("#results-tbl");
if (resultsTbl !== null) {
    var perPageSelector = document.querySelector("#results-tbl > thead > tr:nth-child(1) > th > div > div:nth-child(2) > select");
    var searchBtn = document.querySelector("#results-tbl > thead > tr:nth-child(1) > th > div > div:nth-child(1) > form > button");
    searchBtn.innerHTML = '<i class="fa fa-search"></i> Search';
    searchBtn.style.minWidth = '7em';

    function adjustPerPageWidth() {
        var perPage = perPageSelector.options[perPageSelector.selectedIndex].text;
        var elLength = 5;
        if (perPage>99) {
            elLength = elLength + 1;
        }
        perPageSelector.style.width = elLength + 'em';
    }

    adjustPerPageWidth();

}
</script>