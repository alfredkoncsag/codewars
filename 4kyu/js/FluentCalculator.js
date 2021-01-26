/**
 * Description:
 * https://www.codewars.com/kata/5578a806350dae5b05000021
 */

'use strict';

const FluentCalculator = function () {

    let result = 0;

    const operations = {
        'plus': function (a, b) {
            return a + b;
        },
        'minus': function (a, b) {
            return a - b;
        },
        'times': function (a, b) {
            return a * b;
        },
        'dividedBy': function (a, b) {
            return a / b;
        },
    };

    const numbers = [
        'zero',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'seven',
        'eight',
        'nine',
        'ten'
    ];

    Object.keys(operations).forEach(function (operator) {

        let operationFn = operations[operator];
        let operatorObject = {};

        numbers.forEach(function (num, index) {
            Object.defineProperty(operatorObject, num, {
                get: function () {
                    return result = operationFn(result, index)
                }
            });
        });

        Number.prototype[operator] = operatorObject;
    });

    numbers.forEach((num, index) => {
        Object.defineProperty(this, num, {
            get: () => {
                result = index;
                return Number(index);
            }
        });
    });
};


const FluentCalculator = new FluentCalculator();