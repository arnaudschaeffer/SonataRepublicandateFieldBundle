<?php

namespace Aschaeffer\SonataRepublicandateFieldBundle\Service;

/**
 * Class DateUtils
 * @package App\Service
 */
class DateService
{
    protected static array $frenchMonths = [
        "Vendémiaire",
        "Brumaire",
        "Frimaire",
        "Nivôse",
        "Pluviôse",
        "Ventôse",
        "Germinal",
        "Floréal",
        "Prairial",
        "Messidor",
        "Thermidor",
        "Fructidor",
        "Sansculottide"
    ];

    protected static array $roman = [
        1 => "I",
        4 => "IV",
        5 => "V",
        9 => "IX",
        10 => "X",
        14 => "XIV",
        40 => "XL",
        50 => "L",
        90 => "XC",
        100 => "C",
        400 => "CD",
        500 => "D",
        900 => "CM",
        1000 => "M",
    ];

    protected function frenchToNumber(string $name): string
    {
        return array_search($name, self::$frenchMonths) + 1;
    }
    protected function frenchMonthNames($mo): ?string
    {
        if ($mo < count(self::$frenchMonths) + 1) {
            return self::$frenchMonths[$mo - 1];
        }

        return null;
    }

    protected function romdec($rom)
    {
        $digits = self::$roman;
        krsort($digits);

        $dec = 0;
        foreach ($digits as $key => $value) {
            if (strpos($rom, $value) !== false) {
                $rom = str_replace($value, '', $rom, $count);

                $dec += ($key*$count);
            }
        }

        return (int)$dec;
    }

    protected function decrom($dec)
    {
        $digits = self::$roman;
        krsort($digits);
        $retval = "";
        foreach ($digits as $key => $value) {
            while ($dec >= $key) {
                $dec -= $key;
                $retval .= $value;
            }
        }
        return $retval;
    }

    /**
     * @param $str
     * @return bool|\DateTime
     * @throws \Exception
     */
    public function republicatinTodateTime($str): \DateTime
    {
        list ($monthName, $day, $strYear) = explode(' ', preg_replace('/\s+/', ' ', $str));
        $month = $this->frenchToNumber($monthName);
        $year = $this->romdec($strYear);

        $jdDate = \frenchtojd((int)$month , (int)$day , (int)$year);
        if ($jdDate == 0) {
            throw new \Exception("La date républicaine n'est pas au bon format.");
        }

        $gregorianDate = \jdtogregorian($jdDate);
        if ($gregorianDate == "0/0/0") {
            throw new \Exception("La date républicaine n'est pas au bon format.");
        }

        return \DateTime::createFromFormat('m/d/Y', $gregorianDate);
    }

    public function dateTimeToRepublicain(\DateTime $dt): ?string
    {
        list ($y, $m, $d) = explode('-', $dt->format('Y-m-d'));

        $julian_date = \gregoriantojd($m, $d, $y);
        $french = \jdtofrench($julian_date);

        if ($french == "0/0/0") {
            return null;
        }

        list($repuMonth, $repuDay, $repuYear) = explode("/", $french);

        // get the month name
        $monthname = $this->frenchMonthNames($repuMonth);

        /* convert the year number to roman digits (as most historians do and documents of the time did */
        $stryear = $this->decrom($repuYear);
        return implode(' ', array($monthname, $repuDay, $stryear));
    }
}