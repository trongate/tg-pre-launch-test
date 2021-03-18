<?php
class Tg_comments extends Trongate {

    function _prep_comments($output) {
        //return comments with nicely formatted date
        $body = $output['body'];

        $comments = json_decode($body);
        $data = [];
        foreach ($comments as $key=>$value) {

            $row_data['comment'] = nl2br($value->comment);
            $row_data['date_created'] = date('l jS \of F Y \a\t h:i:s A', $value->date_created);
            $row_data['user_id'] = $value->user_id;
            $row_data['target_table'] = $value->target_table;
            $row_data['update_id'] = $value->update_id;
            $row_data['code'] = $value->code;
            $data[] = $row_data;

        }

        $output['body'] = json_encode($data);
        return $output;
    }

    function _pre_insert($input) {
        //establish user_id, date_created and code before doing an insert
        $this->module('tg_tokens');
        $token = $input['token'];
        $user = $this->tg_tokens->_fetch_token_obj($token);

        $input['params']['user_id'] = $user->user_id;
        $input['params']['date_created'] = time();
        $input['params']['code'] = make_rand_str(6);

        return $input;
    }
    

}