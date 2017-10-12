<?php declare(strict_types = 1); // atom

namespace Netmosfera\Behave\Verification\Interactions\Units;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Behave\Log\SetInteraction;
use Netmosfera\Behave\Verification\Interactions\Result;
use Netmosfera\Behave\Verification\Objects\ObjectConstraint;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use Netmosfera\Behave\Verification\Interactions\InteractionConstraint;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
class SetInteractionConstraint implements InteractionConstraint
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
    private $contentConstraint;

    /**
     * @TODOC
     *
     * @var         ObjectConstraint                                                        `ObjectConstraint`
     */
    private $throwConstraint;

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
     * @param       ObjectConstraint                        $contentConstraint              `ObjectConstraint`
     * @TODOC
     *
     * @param       ObjectConstraint                        $throwConstraint                `ObjectConstraint`
     * @TODOC
     *
     * @param       Bool                                    $eatPreviousInteractions        `Bool`
     * @TODOC
     */
    function __construct(
        $object,
        String $member,
        ObjectConstraint $contentConstraint,
        ObjectConstraint $throwConstraint,
        Bool $eatPreviousInteractions
    ){
        $this->object = $object;
        $this->member = $member;
        $this->contentConstraint = $contentConstraint;
        $this->throwConstraint = $throwConstraint;
        $this->eatPreviousInteractions = $eatPreviousInteractions;
    }

    /** @inheritDoc */
    function fulfill(Array $interactions): Result{
        foreach($interactions as $index => $interaction){
            if(
                $interaction instanceof SetInteraction &&
                $this->object === $interaction->object &&
                $this->member === $interaction->member &&
                $this->contentConstraint->isFulfilledBy($interaction->content) &&
                $this->throwConstraint->isFulfilledBy($interaction->throw)
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
