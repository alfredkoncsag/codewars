<?php
/**
 * Description:
 * https://www.codewars.com/kata/57cc3302d954d951530000a5
 */

class FluentCalculator
{
    /**
     * Maximum input and calculation value
     */
    const DIGIT_COUNT_OVERFLOW = 9;

    /**
     * The possible values that can be added to the calculation stack
     *
     * @var int[]
     */
    protected $numbers = [
        'zero'  => 0,
        'one'   => 1,
        'two'   => 2,
        'three' => 3,
        'four'  => 4,
        'five'  => 5,
        'six'   => 6,
        'seven' => 7,
        'eight' => 8,
        'nine'  => 9,
    ];
    /**
     * The possible operations that can be added to the calculation stack
     *
     * @var string[]
     */
    protected $operations = [
        'plus'      => "+",
        'minus'     => "-",
        'times'     => "*",
        'dividedBy' => "/",
    ];

    /**
     * The calculation stack
     *
     * @var array
     */
    protected $stack = [];

    /**
     * @var bool
     */
    protected $lastOperation = false;

    /**
     * Returns a new instance
     *
     * @return FluentCalculator
     */
    public static function init(): FluentCalculator
    {
        return new static();
    }

    /**
     * Same as __get(), but performs the actual calculation afterwards and returns the result
     *
     * @param $method string The name of the value or operation to add to the stack.
     * @param $args   array not used
     *
     * @return int
     *
     * @throws DigitCountOverflowException if a value on the stack has more than 9 digits of if an operation would result in a number with more than 9 digits
     * @throws DivisionByZeroException on division by zero
     * @throws InvalidInputException if an item on the stack can not be interpreted
     */
    public function __call($method, $args)
    {
        if (!array_key_exists($method, $this->operations) && !array_key_exists($method, $this->numbers)) {
            throw new InvalidInputException;
        }

        if (isset($this->numbers[$method])) {
            if ($this->lastOperation) {
                $this->stack[] = $this->numbers[$method];
            } else {
                if (empty($this->stack)) {
                    $this->stack[] = '';
                }
                $this->stack[count($this->stack) - 1] .= $this->numbers[$method];
            }
        }

        $result = 0;
        $firstRun = true;

        while ($this->stack) {
            $cur = array_shift($this->stack);
            if (!empty($this->operations[$cur])) {
                if (empty($this->stack)) {
                    continue;
                }

                $operand = array_shift($this->stack);

                if (strlen(abs($operand)) > self::DIGIT_COUNT_OVERFLOW) {
                    throw new DigitCountOverflowException();
                }

                switch ($cur) {
                    case 'plus':
                        $result += $operand;
                        break;

                    case 'minus':
                        if ($firstRun) {
                            $result = -1 * $operand;
                        } else {
                            $result -= $operand;
                        }
                        break;

                    case 'times':
                        $result *= $operand;
                        break;

                    case 'dividedBy':
                        if (0 == $operand) {
                            throw new DivisionByZeroException();
                        }
                        $result = intdiv($result, $operand);
                        break;
                }
            } else {
                $result = (int) $cur;
            }

            if (strlen(abs($result)) > self::DIGIT_COUNT_OVERFLOW) {
                throw new DigitCountOverflowException();
            }

            $firstRun = false;
        }

        return $result;
    }

    /**
     * Add a new value or operation to the calculator stack
     *
     * @param $name string The name of the value or operation to add to the stack.
     *
     * @return $this
     * @throws InvalidInputException If $name is neither a valid value nor a valid operation.
     */
    public function __get(string $name): FluentCalculator
    {
        if (!array_key_exists($name, $this->operations) && !array_key_exists($name, $this->numbers)) {
            throw new InvalidInputException;
        }

        if (isset($this->numbers[$name])) {
            if (empty($this->stack) || $this->lastOperation) {
                $this->stack[] = '';
            }

            $this->lastOperation = false;

            $key = count($this->stack) - 1;

            if (!empty($this->stack[$key]) || $name !== 'zero') {
                $this->stack[$key] .= $this->numbers[$name];
            }
        }

        if (isset($this->operations[$name])) {
            if ($this->lastOperation) {
                $this->stack[count($this->stack) - 1] = $name;
            } else {
                $this->stack[] = $name;
            }

            $this->lastOperation = true;
        }

        return $this;
    }
}