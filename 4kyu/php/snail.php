<?php
/**
 * Description:
 * https://www.codewars.com/kata/521c2db8ddc89b9b7a0000c1
 */

/**
 * @param  array  $array
 * @return array
 */
function snail(array $array): array
{
    $res = [];

    while (true) {
        $res = array_merge($res, $array[0]);
        $array = array_slice($array, 1);

        if (empty($array[0])) {
            break;
        }

        $rotate = [];

        foreach (array_keys($array[0]) as $x) {
            foreach (array_keys($array) as $y) {
                $rotate[$x][$y] = $array[$y][$x];
            }
        }

        $array = array_reverse($rotate);
    }

    return $res;
}