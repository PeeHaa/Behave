<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Log;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 *
 * @implements      Interaction
 */
class CallInteraction implements Interaction
{
    /**
     * The executed function.
     *
     * @return      Closure                                                                 `Closure`
     */
    public $closure;

    /**
     * The list of arguments that were used to call the function.
     *
     * @var         Mixed[]                                                                 `Array<Int{NonNegative}, Mixed>`
     */
    public $arguments;

    /**
     * The result of the call of the function (either via `return` or `throw`).
     *
     * @return      Mixed                                                                   `Mixed`
     */
    public $result;

    /**
     * `TRUE` if {@see self::getResult()} was `throw`n, `FALSE` if `return`ed.
     *
     * @return      Bool                                                                    `Bool`
     */
    public $resultWasThrown;

    /**
     * @param       Closure                                 $closure                        `Closure`
     * The executed function.
     *
     * @param       Mixed[]                                 $arguments                      `Array<Int{NonNegative}, Mixed>`
     * The list of arguments that were used to call the function.
     *
     * @param       Mixed                                   $result                         `Mixed`
     * Returns the result of the operation, either a `return` or a `throw`.
     *
     * @param       Bool                                    $resultWasThrown                `Bool`
     * `TRUE` if `$result` was `throw`n, `FALSE` if `return`ed.
     */
    function __construct(Closure $closure, Array $arguments, $result, Bool $resultWasThrown){
        $this->closure = $closure;
        $this->arguments = $arguments;
        $this->result = $result;
        $this->resultWasThrown = $resultWasThrown;
    }
}
