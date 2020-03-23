<?php
function gen_pow()
{
  assert(isset($_SESSION));
  $ans = ""; 
  $cand = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
  for ($i=0; $i<5; $i++) 
  {
      $t = mt_rand(0, 61); 
      $ans .= $cand[$t]; 
  } 
  $_SESSION["pow_chal"] = substr(sha1($ans), 0, 5);
  $_SESSION["pow_answer"] = $ans;
  return $_SESSION["pow_chal"];
}
function get_pow()
{
  assert(isset($_SESSION));
  return gen_pow();
}
function check_pow($ans)
{
  if(!isset($_SESSION) || !isset($_SESSION["pow_chal"]))
    return false;
  
  if(substr(sha1($ans), 0, 5) === $_SESSION["pow_chal"]) {
    unset($_SESSION["pow_chal"]);
    return true;
  }
  unset($_SESSION["pow_chal"]);
  return false;
}

$domain = trim(file_get_contents("/tmp/domain"));
$DEBUG = false;
?>
