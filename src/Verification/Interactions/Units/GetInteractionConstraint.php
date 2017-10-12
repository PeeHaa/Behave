<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Verification\Interactions\Units;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Behave\Verification\Interactions\InteractionConstraint;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use Netmosfera\Behave\Verification\Objects\ObjectConstraint;
use Netmosfera\Behave\Verification\Interactions\Result;
use Netmosfera\Behave\Log\GetInteraction;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
class GetInteractionConstraint implements InteractionConstraint
{
    /**
     * @TODOC
     *
     * @var         Mixed                                                                   `Mixed`
     */
    private $object;

    /**
     * @TODOC
     *
     * @var         String                                                                  `String`
     */
    private $member;

    /**
     * @TODOC
     *
     * @var         ObjectConstraint                                                        `ObjectConstraint`
     */
    private $result;

    /**
     * @TODOC
     *
     * @var         Bool                                                                    `Bool`
     */
    private $resultIsThrown;

    /**
     * @TODOC
     *
     * @var         Bool                                                                    `Bool`
     */
    private $eatPreviousInteractions;

    /**
     * @param       Mixed                                   $object                         `Mixed`
     * @TODOC
     *
     * @param       String                                  $member                         `String`
     * @TODOC
     *
     * @param       ObjectConstraint                        $resultConstraint               `ObjectConstraint`
     * @TODOC
     *
     * @param       Bool                                    $resultIsThrown                 `Bool`
     * @TODOC
     *
     * @param       Bool                                    $eatPreviousInteractions        `Bool`
     * @TODOC
     */
    function __construct(
        $object,
        String $member,
        ObjectConstraint $resultConstraint,
        Bool $resultIsThrown,
        Bool $eatPreviousInteractions
    ){
        $this->object = $object;
        $this->member = $member;
        $this->result = $resultConstraint;
        $this->resultIsThrown = $resultIsThrown;
        $this->eatPreviousInteractions = $eatPreviousInteractions;
    }

    /** @inheritDoc */
    function fulfill(Array $interactions): Result{
        foreach($interactions as $index => $interaction){
            if(
                $interaction instanceof GetInteraction &&
                $this->object === $interaction->object &&
                $this->member === $interaction->member &&
                $interaction->resultWasThrown === $this->resultIsThrown &&
                $this->result->isFulfilledBy($interaction->result)
            ){
                $remainingInteractionsCount = count($interactions) - $index - 1;

                if($this->eatPreviousInteractions){
                    array_splice($interactions, 0, $index + 1, []);
                }else{
                    array_splice($interactions, $index, 1, []);
                }

                return new Result($interactions, $remainingInteractionsCount);
            }
        }
        throw new CannotFulfill($this);
    }
}
