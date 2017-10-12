<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Verification\Interactions\Composites;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use Netmosfera\Behave\Verification\Interactions\InteractionConstraint;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use Netmosfera\Behave\Verification\Interactions\Result;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
class OneInteractionConstraint implements InteractionConstraint
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
        foreach($this->constraints as $expectation){
            try{
                $result = $expectation->fulfill($interactions);
                if($this->eatPreviousInteractions){
                    if($result->remainingInteractionsCount === 0){
                        $interactions = [];
                    }else{
                        $interactions = array_slice($result->interactions, $result->remainingInteractionsCount * -1);
                    }
                    return new Result($interactions, $result->remainingInteractionsCount);
                }else{
                    return $result;
                }
            }catch(CannotFulfill $e){}
        }
        throw new CannotFulfill($this);
    }
}
