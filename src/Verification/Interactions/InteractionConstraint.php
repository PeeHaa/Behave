<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Verification\Interactions;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Behave\Log\Interaction;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
interface InteractionConstraint
{
    /**
     * @TODOC
     *
     * @param       Interaction[]                           $interactions                   `Array<Int{NonNegative}, Interaction>`
     * @TODOC
     *
     * @throws      CannotFulfill                                                           `CannotFulfill`
     * @TODOC
     *
     * @return      Result                                                                  `Result`
     * @TODOC
     */
    function fulfill(Array $interactions): Result;
}
