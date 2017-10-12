<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Verification\Interactions\Composites;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use Netmosfera\Behave\Verification\Interactions\Result;
use Netmosfera\Behave\Verification\Interactions\InteractionConstraint;
use const PHP_INT_MAX;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
class EveryInteractionConstraint implements InteractionConstraint
{
    /**
     * @TODOC
     *
     * @var         InteractionConstraint[]                                                 `Array<Int{NonNegative}, InteractionConstraint>`
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
     * @param       InteractionConstraint[]                 $constraints                    `Array<Int{NonNegative}, InteractionConstraint>`
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
        $remainingInteractionsCount = PHP_INT_MAX;
        foreach($this->constraints as $expectation){
            $result = $expectation->fulfill($interactions);
            if($result->remainingInteractionsCount < $remainingInteractionsCount){
                $remainingInteractionsCount = $result->remainingInteractionsCount;
            }
            $interactions = $result->interactions;
        }
        if($this->eatPreviousInteractions){
            if($remainingInteractionsCount === 0){
                $interactions = [];
            }else{
                $interactions = array_slice($interactions, $remainingInteractionsCount * -1);
            }
        }
        return new Result($interactions, $remainingInteractionsCount);
    }
}
