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

function flash($name = '', $message = '', $class = 'alert-success')
{
  if (!empty($name)) {
    if (!empty($message) && empty($_SESSION[$name])) {
      $_SESSION[$name] = $message;
      $_SESSION[$name . '_class'] = $class;
    } elseif (empty($message) && !empty($_SESSION[$name])) {
      $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
      $styling = ($class == 'alert-success')
        ? 'bg-green-100 border-green-500 text-green-700'
        : 'bg-red-100 border-red-500 text-red-700';
      echo '<div class="' . $styling . ' px-4 py-3 mb-6 rounded border-l-4" id="msg-flash">' . $_SESSION[$name] . '</div>';
      echo '<script>
              setTimeout(function() {
                document.getElementById("msg-flash").style.display = "none";
              }, 1500);
            </script>';
      unset($_SESSION[$name]);
      unset($_SESSION[$name . '_class']);
    }
  }
}
