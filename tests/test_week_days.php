#!/usr/bin/env php5
<?

chdir(dirname(__FILE__));
require_once "../days_calculator.php";


//Test weekends.
$dc = new days_calculator();
$dc->set_weekday(0, false);
$dc->set_weekday(6, false);

$days = $dc->calculate_days(strtotime("2011-01-01"), strtotime("2011-01-07"));
if ($days != 5){
  throw new exception("Expected the days to be 5 but it wasnt: '" . $days . "'");
}