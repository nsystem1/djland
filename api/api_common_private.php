<?php

session_start();

require_once('api_common.php');
require_once('../../headers/security_header.php');

$incoming_data =  (array) json_decode(file_get_contents('php://input'));

function update_row_in_table($tablename, $data, $id){
  global $pdo_db;
  global $error;

  $keys = array_keys($data);

  foreach ($keys as $i => $key){
    $keys[$i] .= ' =:'.$key;
  }

  $query = "UPDATE ".$tablename." SET ".implode(', ',$keys);

  $query .= " WHERE id=".$id.";";

  $statement = $pdo_db->prepare($query);

  foreach($data as $key => $value){
    $statement->bindValue($key,$value);
  }
  try{
    $statement->execute();
  }catch(PDOException $e){
    $error = $e->getMessage();
    return $error;
  }

  return 'save was successful';

}
