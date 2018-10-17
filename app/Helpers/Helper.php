<?php
namespace App\Helpers;

/**
 * Helper Class
 */
class Helper
{

  public static function number_format($num)
  {
    return str_replace(".00","",number_format(max($num,0),2));
  }
}

?>
