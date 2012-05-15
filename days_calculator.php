<?

class days_calculator{
  function __construct($args = array()){
    $this->args = $args;
    $this->neutral_periods = array();
    $this->weekdays = array(0 => true, 1 => true, 2 => true, 3 => true, 4 => true, 5 => true, 6 => true);
    
    if (array_key_exists("debug", $args)){
      $this->debug = $args["debug"];
    }else{
      $this->debug = false;
    }
  }
  
  /** Adds a neutral period to the calculation. */
  function add_neutral_period($args){
    if (!$args["time_start"]){
      throw new exception("Invalid or no 'time_start' given: '" . $args["time_start"] . "'.");
    }
    
    if (!$args["time_end"]){
      throw new exception("Invalid or no 'time_end' given: '" . $args["time_end"] . "'.");
    }
    
    $this->neutral_periods[] = $args;
  }
  
  /** Sets a specific week-day to be ignored. */
  function set_weekday($day_no, $val){
    $this->weekdays[$day_no] = $val;
  }
  
  /** Calculates days between to given unix-timedays based on the added neutral periods. */
  function calculate_days($time_start, $time_end){
    $days = 0;
    $time_cur = $time_start;
    while(true){
      $neutral = false;
      
      //Test weekday.
      $weekday = date("w", $time_cur);
      if (!$this->weekdays[$weekday]){
        if ($this->debug){
          print "Neutral because of weekday (" . $weekday . ").\n";
        }
        
        $neutral = true;
      }
      
      
      //Test periods.
      if (!$neutral){
        foreach($this->neutral_periods as $np){
          if ($np["every_year"]){
            $np["time_start"] = strtotime(date("Y", $time_cur) . date("-m-d", $np["time_start"]));
            
            //This can happen if we are looping over years.
            if ($np["time_start"] > $time_cur){
              $np["time_start"] = strtotime("-1 year", $np["time_start"]);
            }
            
            $np["time_end"] = strtotime(date("Y", $np["time_start"]) . date("-m-d", $np["time_end"]));
            
            //This can happen if we are looping over years.
            while($np["time_end"] < $np["time_start"]){
              $np["time_end"] = strtotime("+1 year", $np["time_end"]);
            }
            
            if ($this->debug){
              print "Changed period to: " . date("Y-m-d", $np["time_start"]) . " - " . date("Y-m-d", $np["time_end"]) . ".\n";
            }
          }
          
          if ($time_cur >= $np["time_start"] and $time_cur <= $np["time_end"]){
            if ($this->debug){
              print "Neutral because of period (" . date("Y-m-d", $time_cur) . " <=> " . date("Y-m-d", $np["time_start"]) . " - " . date("Y-m-d", $np["time_end"]) . ").\n";
            }
            
            $neutral = true;
            break;
          }
        }
      }
      
      if (!$neutral){
        if ($this->debug){
          print "Not neutral: " . date("Y-m-d", $time_cur) . ".\n";
        }
        
        $days++;
      }
      
      if ($time_cur >= $time_end){
        break;
      }
      
      $time_cur = strtotime("+1 day", $time_cur);
    }
    
    return $days;
  }
}