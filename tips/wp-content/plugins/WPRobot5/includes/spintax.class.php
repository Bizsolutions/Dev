<?php

class Spintax
{
public function process($text)
{
return preg_replace_callback(
'/\{(((?>[^\{\}]+)|(?R))*)\}/x',
array($this, 'replace'),
$text
);
}
 
public function replace($text)
{
$text = $this->process($text[1]);
$parts = explode('|', $text);
return $parts[array_rand($parts)];
}
}

/*
class Spintax {
   
   function spin($str, $test=false)
   {
      if(!$test){
         do {
            $str = $this->regex($str);
         } while ($this->complete($str));
         return $str;
      } else {
         do {
            echo "<b>PROCESS: </b>";var_dump($str = $this->regex($str));echo "<br><br>";
         } while ($this->complete($str));
         return false;
      }
   }
   
   function regex($str)
   {
      preg_match("/{[^{}]+?}/", $str, $match);
      // Now spin the first captured string
      $attack = explode("|", $match[0]);
      $new_str = preg_replace("/[{}]/", "", $attack[rand(0,(count($attack)-1))]);
      $str = str_replace($match[0], $new_str, $str);
      return $str;
   }
   
   function complete($str)
   {
      $complete = preg_match("/{[^{}]+?}/", $str, $match);
      return $complete;
   }
}*/