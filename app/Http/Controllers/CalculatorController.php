<?php

namespace App\Http\Controllers;

use App\Exceptions\DivisionByZeroException;
use App\Models\History;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Calculator;

class CalculatorController extends Controller
{
    /**
     * Receive the stack of values and operations for calculation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request)
    {
        $stack = $request->post('stack');

        /**
         * Accumulated value of the operations
         * @var float
         */
        $currentValue = 0;

        /**
         * Current operation to be performed
         * @var string
         */
        $currentOperator = '+';

        try
        {
            foreach($stack as $item){
                /**
                 * Try replace "," to "." if applicable fixing the number format
                 */
                $item = str_replace(',','.', $item);

                /**
                 * Test if the fixed item is a number or operator
                 */
                if(is_numeric($item))
                {
                    /**
                     * If is a number cast to float before calculate
                     */
                    $item = (float)$item;

                    /**
                     * Perform the operation and update the current value
                     */
                    $currentValue = Calculator::calculate($currentValue, $item, $currentOperator);
                }
                else
                {
                    /**
                     * If is an operator, update for the next iteration
                     */
                    $currentOperator = $item;
                }
            }
        }
        Catch(DivisionByZeroException $e)
        {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }

        /**
         * Generate a random number and compare if matches with the result
         * @var boolean
         */
        $randomNumber = Calculator::getRandomNumber($currentValue);
        $bonus = ($randomNumber == $currentValue);

        /**
         * Compose the information to be stored in the history and to be responded
         * @var array
         */
        $history = [
            'ip' => $request->getClientIp(),
            'operation' => implode(' ', $stack),
            'result' => $currentValue,
            'bonus' => $bonus
        ];
        History::create($history);

        /**
         * Add the generated random number and convert the "." to "," before send to response
         */
        $history['randomNumber'] = $randomNumber;
        $history['result'] = str_replace('.',',', $currentValue);

        return new JsonResponse($history, 201);
    }

}
