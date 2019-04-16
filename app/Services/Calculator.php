<?php


namespace App\Services;

use App\Exceptions\DivisionByZeroException;

class Calculator
{
    /**
     * Perform a calculation with given arguments
     * @param float $valueA
     * @param float $valueB
     * @param string $operator
     * @return float
     * @throws DivisionByZeroException
     */
    public static function calculate(float $valueA, float $valueB, string $operator): float
    {
        switch ($operator){
            case '+':
                return ($valueA + $valueB);
                break;
            case '-':
                return ($valueA - $valueB);
                break;
            case '*':
                return ($valueA * $valueB);
                break;
            case '/':
                if($valueB == 0){
                    throw new DivisionByZeroException("Division by Zero");
                }
                return ($valueA / $valueB);
                break;
            case 'MOD':
                return ($valueA % $valueB);
                break;
        }
    }

    /**
     * Generate a random number using the given value
     *
     * @param float $value
     * @return float
     */
    public static function getRandomNumber(float $value): float
    {
        /**
         * Check if the given number has decimal part
         * @var boolean
         */
        $isDecimal = floor($value) != $value;

        /**
         * If so, extract the decimal part
         * @var float
         */
        $decimalPart = $isDecimal ? ($value - floor($value)) : 0.0;

        /**
         * Create a range of numbers from the given value to increase the chance of assertion
         */
        $min = floor($value - 10);
        $max = ceil($value + 10);
        /**
         * @var float
         */
        $randomNumber = (float)rand($min, $max);

        /**
         * Sums the decimal part to the integer part
         */
        $randomNumber += $decimalPart;

        /**
         * Return the number with both integer and decimal parts
         */
        return $randomNumber;
    }

}
