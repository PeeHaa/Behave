<?php declare(strict_types = 1); // atom

namespace Netmosfera\BehaveTests\Verification\Interactions\Composites;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function array_filter;
use function array_values;
use function Netmosfera\Behave\get;
use function Netmosfera\Behave\same;
use function Netmosfera\Behave\every;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use Netmosfera\Behave\Log\GetInteraction;
use PHPUnit\Framework\TestCase;
use Error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class EveryInteractionConstraintTest extends TestCase
{
    private function remove(Array $from, Array $indexes): Array{
        $objects = [];
        foreach($indexes as $index){
            $objects[] = $from[$index];
        }
        $filtered = array_filter($from, function($object) use($objects){
            return !in_array($object, $objects, TRUE);
        });
        return array_values($filtered);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_throws_if_less_than_two_constraints_are_given_to_the_ctor(){
        $this->expectException(Error::CLASS);

        every([
            get("@", "@", same("@"), FALSE, FALSE)
        ], FALSE);
    }

    function test_exception_bubbles_up(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[0] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[1] = new GetInteraction("#", "#", "#", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("§", "§", same("§"), FALSE, FALSE),
        ], FALSE);
        $constraint->fulfill($interactions);
    }

    function test_fulfill(){
        $interactions[0] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[1] = new GetInteraction("#", "#", "#", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        self::assertSame([], $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    function test_fulfill_reversed_order(){
        $interactions[0] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        self::assertSame([], $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_adjacent_2_more(){
        $interactions[0] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[1] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[2] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_adjacent_1_more(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [1, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_adjacent_0_more(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [2, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

        function test_fulfill_nonadjacent_2_more(){
        $interactions[0] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[4] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_nonadjacent_1_more(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[4] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [1, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_nonadjacent_0_more(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[4] = new GetInteraction("#", "#", "#", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [2, 4]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_adjacent_reversed_2_more(){
        $interactions[0] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_adjacent_reversed_1_more(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[2] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [1, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_adjacent_reversed_0_more(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("@", "@", "@", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [2, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

        function test_fulfill_nonadjacent_reversed_2_more(){
        $interactions[0] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[4] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_nonadjacent_reversed_1_more(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[2] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[4] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [1, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_nonadjacent_reversed_0_more(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[4] = new GetInteraction("@", "@", "@", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], FALSE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [2, 4]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_adjacent_2_more_eat(){
        $interactions[0] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[1] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[2] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_adjacent_1_more_eat(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_adjacent_0_more_eat(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

        function test_fulfill_nonadjacent_2_more_eat(){
        $interactions[0] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[4] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_nonadjacent_1_more_eat(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[4] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_nonadjacent_0_more_eat(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[4] = new GetInteraction("#", "#", "#", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2, 3, 4]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_adjacent_reversed_2_more_eat(){
        $interactions[0] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_adjacent_reversed_1_more_eat(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[2] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_adjacent_reversed_0_more_eat(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("@", "@", "@", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

        function test_fulfill_nonadjacent_reversed_2_more_eat(){
        $interactions[0] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[4] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_nonadjacent_reversed_1_more_eat(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[2] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[3] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[4] = new GetInteraction("*", "*", "*", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_nonadjacent_reversed_0_more_eat(){
        $interactions[0] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[4] = new GetInteraction("@", "@", "@", FALSE);

        $constraint = every([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
        ], TRUE);
        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2, 3, 4]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->remainingInteractionsCount);
    }
}
