<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Verification\Interactions\Composites;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use Netmosfera\Behave\Verification\Interactions\Result;
use Netmosfera\Behave\Verification\Interactions\InteractionConstraint;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
class EveryInteractionConstraint implements InteractionConstraint
{
    /**
     * @TODOC
     *
     * @var         InteractionConstraint[]                                                 `Array<Int, InteractionConstraint>`
     */
    private $constraints;

    /**
     * @TODOC
     *
     * @var         Bool                                                                    `Bool`
     */
    private $eatPreviousInteractions;

    /**
     * @throws
     *
     * @param       InteractionConstraint[]                 $constraints                    `Array<Int, InteractionConstraint>`
     * @TODOC
     *
     * @param       Bool                                    $eatPreviousInteractions        `Bool`
     * @TODOC
     */
    function __construct(array $constraints, Bool $eatPreviousInteractions){
        if(count($constraints) < 2){
            throw new Error("At least two constraints must be provided");
        }
        $this->constraints = $constraints;
        $this->eatPreviousInteractions = $eatPreviousInteractions;
    }

    /** @inheritDoc */
    function fulfill(Array $interactions): Result{
        $continueIndex = PHP_INT_MIN;
        foreach($this->constraints as $expectation){
            $result = $expectation->fulfill($interactions);
            if($result->continueIndex > $continueIndex){
                $continueIndex = $result->continueIndex;
            }
            $interactions = $result->interactions;
        }
        if($this->eatPreviousInteractions){
            if($continueIndex === 0){
                $interactions = [];
            }else{
                $interactions = array_slice($interactions, $continueIndex);
            }
        }
        return new Result($interactions, $continueIndex);
    }
}
