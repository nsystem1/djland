<?php

$shift_minutes = 10;

echo '<pre>';

require_once('../api_common.php');

$q = 'SELECT id,start_time, status, show_id from playsheets where status =2 order by show_id asc';

$r = mysqli_query($db,$q);

$q2 = 'SELECT podcast_episodes.id as episode_id ,
        podcast_episodes.title,
        podcast_episodes.date,
        podcast_episodes.channel_id,
        podcast_episodes.url,
        podcast_episodes.summary,
        podcast_channels.show_id
        FROM podcast_episodes
        JOIN podcast_channels ON podcast_episodes.channel_id = podcast_channels.id
        JOIN shows ON shows.id = podcast_channels.show_id order by show_id asc';
$r2 = mysqli_query($db,$q2);

$episodes = array();
$playsheets = array();

while($row = mysqli_fetch_assoc($r)){

  $playsheets []= $row;

}

$total_playsheets = count($playsheets);

while($row = mysqli_fetch_assoc($r2)){

  $episodes []= $row;

}
$total_episodes = count($episodes);

$episodes_by_show = array();

foreach($episodes as $i => $ep){
  $episodes_by_show[$ep['show_id']] [] = $ep;
}

$playsheets_by_show = array();

foreach($playsheets as $i => $pl){
  $playsheets_by_show[$pl['show_id']] []= $pl;
}

$single_matches = 0;
$big_matches = 0;

foreach($playsheets_by_show as $i => $pl_group){

  $show_id = $i;
  echo "\nlooking for episodes with show id: ".$show_id;

  if(isset($episodes_by_show[$show_id])){

    foreach($pl_group as $j => $playsheet){
      $matches = array();
      foreach($episodes_by_show[$show_id] as $k => $episode){

        // foreach podcast episode under the matching show id

        $playsheet_date = strtotime($playsheet['start_time']);
        $episode_date = strtotime($episode['date']);

        $pl_date_max = $playsheet_date + $shift_minutes*60;
        $pl_date_min = $playsheet_date - $shift_minutes*60;
        $ep_date_max = $episode_date + $shift_minutes*60;
        $ep_date_min = $episode_date - $shift_minutes*60;

        if ($playsheet_date == $episode_date){
          $matches []= array('type' => 'exact', 'episode' => $episode, 'playsheet' => $playsheet);
        } else if (  ( ($pl_date_max >= $ep_date_min ) && ($pl_date_max <= $ep_date_max) ) || ( ($pl_date_min >= $ep_date_min ) && ($pl_date_min <= $ep_date_max ) ) ){
          $matches []= array('type' => 'approx','difference'=> $playsheet_date - $episode_date, 'episode' => $episode, 'playsheet' => $playsheet);
        }


      }

      $playsheets_by_show[$i][$j]['matches'] = $matches;
      if( count($matches) ==1) {
        $single_matches = $single_matches +1;
      } else
        if( count($matches) >1 ) {
          print_r($matches);
          $big_matches = $big_matches +1;
        }
      if(isset($matches[0])){
        connect($matches[0]['playsheet'], $matches[0]['episode']);
      }
    }


  } else {

    echo "\n no episodes found with this show id: ".$show_id;

  }




echo "\n";

//  print_r($playsheets_by_show);
}
echo '<h2>'.$total_playsheets.' playsheets. '.$total_episodes.' episodes. '.$single_matches.' single matches found.  '.$big_matches.' big matches found</h2>';


function connect($playsheet,$episode){

  global $db;
  $connect_q = 'UPDATE podcast_episodes SET playsheet_id ='.$playsheet['id'].' WHERE id = '.$episode['episode_id'].'';

  if($connect_r = mysqli_query($db,$connect_q)){

  } else {
    echo "\n db problem: ".mysqli_error($db).".  query was ".$connect_q."\n";
  }

}