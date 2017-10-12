<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Verification\Interactions\Composites;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Behave\Verification\Interactions\InteractionConstraint;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use Netmosfera\Behave\Verification\Interactions\Result;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
class NotInteractionConstraint implements InteractionConstraint
{
    /**
     * @TODOC
     *
     * @var         InteractionConstraint                                                   `InteractionConstraint`
     */
    private $constraint;

    /**
     * @throws
     *
     * @param       InteractionConstraint                   $constraint                     `InteractionConstraint`
     * @TODOC
     */
    function __construct($constraint){
        $this->constraint = $constraint;
    }

    /** @inheritDoc */
    function fulfill(Array $interactions): Result{
        try{
            $this->constraint->fulfill($interactions);
        }catch(CannotFulfill $e){
            return new Result($interactions, count($interactions));
        }
        throw new CannotFulfill($this);
    }
}
