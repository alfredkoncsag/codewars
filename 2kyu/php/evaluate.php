<?php
/**
 * Description:
 * https://www.codewars.com/kata/52a78825cdfc2cfc87000005
 */

/**
 * Hack 1.
 *
 * @param  string  $expression
 * @return float
 */
function calc1(string $expression): float
{
    return create_function('', 'return '.$expression.';')();
}

/**
 * Hack with eval.
 *
 * @param  string  $expression
 * @return float
 */
function calc2(string $expression): float
{
    return create_function('', 'return '.str_replace('HACK', "", "eHACKvaHACKl('return {$expression};')").';')();
}

/**
 * Best practice, as it should have been...
 * TODO: Learn Regular Expressions...
 *
 * @param  string  $expression
 * @return float
 */
function calc3(string $expression): float
{
    $expression = preg_replace_callback(
        "/\((((?>[^()]+)|(?R))*)\)/", function ($brackets) {
        return calc($brackets[1]);
    }, $expression);

    $expression = str_replace("--", "", $expression);
    $num = "\s*\-?\d+(?:\.\d+)?\s*";
    while (preg_match("/($num)([\*\/])($num)/", $expression, $action)) {
        switch ($action[2]) {
            case "*":
                $expression = str_replace($action[0], calc($action[1]) * calc($action[3]), $expression);
                break;
            case "/":
                $expression = str_replace($action[0], calc($action[1]) / calc($action[3]), $expression);
                break;
        }
    }
    while (preg_match("/($num)([\+\-])($num)/", $expression, $action)) {
        switch ($action[2]) {
            case "+":
                $expression = str_replace($action[0], calc($action[1]) + calc($action[3]), $expression);
                break;
            case "-":
                $expression = str_replace($action[0], calc($action[1]) - calc($action[3]), $expression);
                break;
        }
    }

    return $expression;
}