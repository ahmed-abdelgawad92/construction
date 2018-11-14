<?php
namespace App\Helpers;

/**
 * Helper Class
 */
class Helper
{
  // show number with only 2 decimal points
  public static function number_format($num)
  {
    return str_replace(".00","",number_format(max($num,0),2));
  }

  //replace Arabic Letters that can confuse the MYSQL while searching
  public static function arabic_replace(string $string)
  {
    // All variations of letters
    $alef = ['أ','آ','إ'];
    $yeh = 'ى';
    $teh = 'ة';
    //replace with only this letters
    $replaceAlef = 'ا';
    $replaceYeh = 'ي';
    $replaceTeh = 'ه';
    //process
    return str_replace($alef,$replaceAlef,str_replace($yeh,$replaceYeh,str_replace($teh,$replaceTeh,$string)));
  }
}

?>
