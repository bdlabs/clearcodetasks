<?
function group_by($stream, $field, $success = null)
{
    $results = array();
    //$wsk = array(13,27,15,31,26,9,23,16,33,5,-1);
    fgets($stream);
    fgets($stream);
    while (($line = fgets($stream)) !== false) {
        $date = trim(substr($line, 13, 27));

        //brak daty
        if ($date == "") {
            continue;
        }

        $suc = "N"; //moze sie okazac, ze jest brak wpisu w kolumnie Suc
        if (strlen($line) > 193) {
            $suc = trim(substr($line, 193, 5));
        }

        list($year, $month, $rest) = explode(" ", $date, 3);
        $recordtogroup = $month;
        if ($field == "year") {
            $recordtogroup = $year;
        }

        if (($success == true && $suc == "S") ||
            ($success == false && $suc == "F") ||
            (!is_bool($success) && is_null($success))) {

            if (!isset($results[$recordtogroup])) {
                $results[$recordtogroup] = 0;
            }
            $results[$recordtogroup]++;
        }
    }

    fclose($stream);
    ksort($results);

    return json_encode($results);
}

$stream = fopen("launchlog.txt", "rb");

$results = group_by($stream, "year", false);