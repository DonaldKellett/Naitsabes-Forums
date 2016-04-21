<?php
class VigenèreCipher {
  public $key;
  public $alphabet;
  public function __construct($key, $alphabet) {
    $this->key = $key;
    $this->alphabet = $alphabet;
  }
  public function encrypt($string) {
    $result = str_split($string);
    $key_text = $this->key;
    $abc = str_split($this->alphabet);
    while (strlen($key_text) < count($result)) {
      $key_text .= $this->key;
    }
    $key_text = str_split($key_text);
    $temp = array();
    for ($i = 0; $i < count($result); $i++) {
      $temp[$i] = $key_text[$i];
    }
    # $temp = implode($temp);
    $key_text = $temp;
    // echo "<code style='font-weight:bold'>";
    // echo implode($result) . "<br>";
    // echo $key_text;
    // echo "</code>";
    for ($i = 0; $i < count($result); $i++) {
      if (array_search($result[$i], $abc) !== false) {
        $result[$i] = $abc[(array_search($result[$i], $abc) + array_search($key_text[$i], $abc)) % count($abc)];
      }
    }
    $result = implode($result);
    return $result;
  }
  public function decrypt($string) {
    $result = str_split($string);
    $key_text = $this->key;
    $abc = str_split($this->alphabet);
    while (strlen($key_text) < count($result)) {
      $key_text .= $this->key;
    }
    $key_text = str_split($key_text);
    $temp = array();
    for ($i = 0; $i < count($result); $i++) {
      $temp[$i] = $key_text[$i];
    }
    # $temp = implode($temp);
    $key_text = $temp;
    // echo "<code style='font-weight:bold'>";
    // echo implode($result) . "<br>";
    // echo $key_text;
    // echo "</code>";
    for ($i = 0; $i < count($result); $i++) {
      if (array_search($result[$i], $abc) !== false) {
        $key_text_location = array_search($key_text[$i], $abc);
        while ($key_text_location > array_search($result[$i], $abc)) $key_text_location -= strlen($this->alphabet);
        $result[$i] = $abc[(array_search($result[$i], $abc) - $key_text_location) % count($abc)];
      }
    }
    $result = implode($result);
    return $result;
  }
}
?>
