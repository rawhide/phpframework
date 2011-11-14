<?php

function datetime_explode($datetime) {
  preg_match('/^([^\-]*)-([^\-]*)-([^ ]*) ([^:]*):([^:]*):(.*)$/', $datetime, $matches);
  array_shift($matches);
  return $matches;
}

function datetime_implode($year, $mon, $day, $hour, $min) {
  if (
         ($year === "" || is_null($year))
      && ($mon  === "" || is_null($mon))
      && ($day  === "" || is_null($day))
      && ($hour === "" || is_null($hour))
      && ($min  === "" || is_null($min))
      ) return null;
  return mb_convert_kana("$year-$mon-$day $hour:$min:0", "n",'UTF-8');
}
