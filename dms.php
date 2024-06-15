
<?php

class DMS {

  public int $degrees = 0;
  public int $minutes = 0;
  public float $seconds = 0;
  public float $decimalDMS = 0;

  function __construct(int $degrees, int $minutes, float $seconds) {

    $this->$degrees = $degrees;
    $this->$minutes = $minuges;
    $this->$seconds = $seconds;

    $this->$decimalDMS = $degrees + ($minutes/60) + abs($seconds/3600);

  }
  
}

?>
