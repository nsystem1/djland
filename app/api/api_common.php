<?php

//building api endpoints: just populate $error if there's an error
// populate $data to return
// call finish()

error_reporting(E_ALL);

require_once('../../headers/db_header.php');

date_default_timezone_set('America/Vancouver');

$error = '';
$blame_request = false;

function finish(){

  global $error;
  global $blame_request;
  global $data;
  global $query;
  global $db;

  if($error != ''){
            mysqli_close($db['link']);
            echo $error;
            if($blame_request){
              header('HTTP/1.0 400 '.$error);
            } else {
              header('HTTP/1.0 500' .$error);
            }
  } else {

    //if ( is_array($data) && sizeof($data) == 1 ) $data = $data[0];

    $data = convertEntities($data);

//    header('Access-Control-Allow-Origin: *',false);
    header("Content-Type:application/json; charset=utf-8",true);

/*    if( defined('JSON_PRETTY_PRINT') ){

      echo utf8_json_encode($data, JSON_PRETTY_PRINT);

    } else {*/
      echo utf8_json_encode( $data );
    //}
  }
  mysqli_close($db['link']);
  die();
}

function utf8_json_encode($arr, $option = false)
{
  //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
  array_walk_recursive($arr, function (&$item, $key) {
    if (is_string($item)) $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
  });
  return mb_decode_numericentity(json_encode($arr, $option), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
}


function convertEntities($data){
  if( is_string($data)){
    if( defined('ENT_HTML5')) {
       return html_entity_decode(html_entity_decode($data, ENT_QUOTES), ENT_HTML5);
    } else {

      return   html_entity_decode($data,ENT_QUOTES);
    }
  } else if(is_array($data)){
      foreach($data as $i => $v){

        $data[$i] = convertEntities($data[$i]);
      }
    }
  return $data;
}

function get_array($table, $idfield = 'id', $fields = 'basic'){
  global $_GET;
  global $db;
  global $error;

  if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
  if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;

  if($fields == 'basic') {
    $query = 'SELECT ' . $idfield . ', edit_date FROM ' . $table . ' ORDER BY edit_date DESC limit ' . $limit . ' OFFSET ' . $offset;
  } else {
    $query = 'SELECT * FROM ' . $table . ' ORDER BY edit_date DESC limit ' . $limit . ' OFFSET ' . $offset;
  }
  $array = array();
  if ($result = mysqli_query($db['link'], $query) ) {

    while ($row = mysqli_fetch_assoc($result)) {

      $array [] = $row;

    }
  } else {
    $error .= mysqli_error($db['link']);
  }
  return $array;

}

function singleRowByID($table, $id){

  global $db;
  return singleRowFromDB($db['link'], $table, $id);
}

function singleRowByIDFromSam($table,$id){
  global $mysqli_sam;
  return singleRowFromDB($mysqli_sam, $table, $id);
}

function singleRowFromDB($db, $table, $id){
  global $error;

  $q = 'SELECT * from '.$table.' where id ='.$id;
  if ($error == '' && $result = mysqli_query($db['link'], $q)){
    return mysqli_fetch_assoc($result);
  } else {
    $error .= mysqli_error($db['link']);
    return false;
  }
}

function getIDbyRow($table,$array){
  global $db;
  global $error;

  $q = 'SELECT id from '.$table.' WHERE ';

  $q_values = array();

  foreach ($array as $key => $val ){
    $q_values [] = $key.' = "'.$val.'" ';
  }
  $q_values = implode(' AND ', $q_values);

  $q .= $q_values;

  if ($error == '' && $result = mysqli_query($db['link'], $q)){
    if (mysqli_num_rows($result) == 0) {
      return false;
    }
    $row = mysqli_fetch_assoc($result);
    if (array_key_exists('id', $row)){
      return $row['id'];
    } else {
      $error .= 'key id not found ';
      return false;
    }

  }
}
$incoming_data =  (array) json_decode(file_get_contents('php://input'));
