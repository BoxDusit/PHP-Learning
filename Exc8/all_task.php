<?php
  header('Content-Type: application/json');
  include "db_connect.php";

  $sql = "SELECT * FROM tasks ORDER By id DESC";
  $query = mysqli_query($conn, $sql);

  $resultArray = array();
  $i = 0;
  while($result = mysqli_fetch_assoc($query)){
    $resultArray[$i]['id'] = intval($result['id']);
    $resultArray[$i]['title'] = $result['title'];
    $resultArray[$i]['description'] = $result['description'];
    $i++;
  }

  mysqli_close($conn);
  echo json_encode($resultArray);
?>