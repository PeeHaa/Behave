<?php declare(strict_types = 1); // atom

namespace Netmosfera\BehaveTests\Verification\Interactions\Composites;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\Behave\not;
use Netmosfera\Behave\Verification\Interactions\InteractionConstraint;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use Netmosfera\Behave\Verification\Interactions\Result;
use Netmosfera\Behave\Log\Interaction;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class NotInteractionConstraintTest extends TestCase
{
    function test_fulfill_1(){
        $interactions[0] = new class() implements Interaction{};

        $c = new class() implements InteractionConstraint{
            function fulfill(Array $interactions): Result{
                throw new CannotFulfill($this);
            }
        };

        $result = not($c)->fulfill($interactions);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(1, $result->remainingInteractionsCount);
    }

    function test_fulfill_2(){
        $interactions[0] = new class() implements Interaction{};
        $interactions[1] = new class() implements Interaction{};

        $c = new class() implements InteractionConstraint{
            function fulfill(Array $interactions): Result{
                throw new CannotFulfill($this);
            }
        };

        $result = not($c)->fulfill($interactions);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(2, $result->remainingInteractionsCount);
    }

    function test_fulfill_3(){
        $interactions[0] = new class() implements Interaction{};
        $interactions[1] = new class() implements Interaction{};
        $interactions[2] = new class() implements Interaction{};

        $c = new class() implements InteractionConstraint{
            function fulfill(Array $interactions): Result{
                throw new CannotFulfill($this);
            }
        };

        $result = not($c)->fulfill($interactions);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(3, $result->remainingInteractionsCount);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_cannot_fulfill(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[0] = new class() implements Interaction{};
        $interactions[1] = new class() implements Interaction{};
        $interactions[2] = new class() implements Interaction{};

        $c = new class() implements InteractionConstraint{
            function fulfill(Array $interactions): Result{
                return new Result([], 0);
            }
        };

        not($c)->fulfill($interactions);
    }
}
