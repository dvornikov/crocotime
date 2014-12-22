<?php
class Model_Jedi extends ORM
{
    protected $_table_name = 'jedis'; // Необязательно, Kohana сама подразумевает таблицу с этим именем.
    protected $_table_columns = array(
        'id' => NULL,
        'name' => NULL,
        'strain' => NULL,
        'rank' => NULL,
    );

    public function delete_by_ids($ids)
    {
    	DB::delete('jedis')->where('id', 'IN', $ids)->execute();
    }
}