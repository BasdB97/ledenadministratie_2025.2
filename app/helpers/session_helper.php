<?php
session_start();

function setSession($name = '', $value = '')
{
  if (!empty($name) && !empty($value)) {
    if (isset($_SESSION[$name])) {
      unset($_SESSION[$name]);
      $_SESSION[$name] = $value;
    } else {
      $_SESSION[$name] = $value;
    }
  }
}

function isLoggedIn()
{
  if (isset($_SESSION['user_id'])) {
    return true;
  } else {
    return false;
  }
}
