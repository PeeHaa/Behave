<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Verification\Interactions;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Behave\Log\Interaction;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
class Result
{
    /**
     * The remaining interactions.
     *
     * @var         Interaction[]                                                           `Array<Int{NonNegative}, Interaction>`
     * @TODOC
     */
    public $interactions;

    /**
     * How many interactions appear after the last fulfilled interaction.
     *
     * For example, if the last fulfilled interaction is the fourth-to-last, this value
     * will be `3`; if it is the third-to-last, this value will be `2`; if it is the last,
     * this value will be `0`, and so on.
     *
     * @var         Int                                                                     `Int{NonNegative}`
     */
    public $remainingInteractionsCount;

    function __construct(Array $interactions, Int $remainingInteractionsCount){
        $this->interactions = $interactions;
        $this->remainingInteractionsCount = $remainingInteractionsCount;
    }
}
