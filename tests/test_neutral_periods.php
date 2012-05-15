#!/usr/bin/env php5
<?

chdir(dirname(__FILE__));
require_once "../days_calculator.php";


//Test normal years.
$dc = new days_calculator();
$dc->add_neutral_period(array("time_start" => strtotime("2012-06-17"), "time_end" => strtotime("2012-06-19"), "every_year" => true));

$days = $dc->calculate_days(strtotime("2011-06-15"), strtotime("2011-06-22"));
if ($days != 5){
  throw new exception("Expected the days to be 5 but it wasnt: '" . $days . "'");
}


//Test over multiple years.
$dc = new days_calculator();
$dc->add_neutral_period(array("time_start" => strtotime("2012-12-28"), "time_end" => strtotime("2013-01-02"), "every_year" => true));

$days = $dc->calculate_days(strtotime("2011-12-26"), strtotime("2011-12-30"));
if ($days != 2){
  throw new exception("Expected the days to be 2 but it wasnt: '" . $days . "'");
}

$days = $dc->calculate_days(strtotime("2011-12-26"), strtotime("2012-01-04"));
if ($days != 4){
  throw new exception("Expected the days to be 4 but it wasnt: '" . $days . "'");
}