<?php

declare(strict_types=1);

namespace App\Tests\Security\Voter;
use App\Entity\HallSession;
use App\Entity\Seat;
use App\Repository\SeatRepository;
use App\Security\Voter\HallSessionVoter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class HallSessionVoterTest extends TestCase
{
    /** @var TokenInterface|MockObject */
    protected $token;
    /** @var HallSessionVoter */
    private $voter;
    /** @var SeatRepository|MockObject */
    private $seatRepositoryMock;

    public function setUp()
    {
        $this->seatRepositoryMock = $this->createMock(SeatRepository::class);
        $this->token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $this->voter = new HallSessionVoter($this->seatRepositoryMock);
    }

    public function getSupportTests(): array
    {
        return [
            [['ROLE_ADMIN_HALL_SESSION_DELETE'], VoterInterface::ACCESS_DENIED, new HallSession(), 'ACCESS_DENIED if user has no permissions'],
            [['TEST_ROLE'], VoterInterface::ACCESS_ABSTAIN, new HallSession(), 'ACCESS_ABSTAIN if no attribute is supported'],

            [['ROLE_ADMIN_HALL_SESSION_DELETE'], VoterInterface::ACCESS_ABSTAIN, $this, 'ACCESS_ABSTAIN if class is not supported'],

            [['ROLE_ADMIN_HALL_SESSION_DELETE'], VoterInterface::ACCESS_ABSTAIN, null, 'ACCESS_ABSTAIN if object is null'],

            [[], VoterInterface::ACCESS_ABSTAIN, new HallSession(), 'ACCESS_ABSTAIN if no attributes were provided'],
        ];
    }

    /**
     * @dataProvider getSupportTests
     * @test
     */
    public function support(array $attributes, $expectedVote, $object, $message)
    {
        $this->assertEquals($expectedVote, $this->voter->vote($this->token, $object, $attributes), $message);
    }

    /**
     * @test
     */
    public function canDelete()
    {
        $hallSessionMock = $this->createMock(HallSession::class);
        $userMock = $this->createMock(UserInterface::class);
        $this->token->method('getUser')->willReturn($userMock);

        $this->seatRepositoryMock->method('findReservedSeatsByHallSession')
            ->with($hallSessionMock)
            ->willReturn([]);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $this->voter->vote($this->token, $hallSessionMock, ['ROLE_ADMIN_HALL_SESSION_DELETE']));
    }

    /**
     * @test
     */
    public function cannotDelete()
    {
        $hallSessionMock = $this->createMock(HallSession::class);
        $userMock = $this->createMock(UserInterface::class);
        $this->token->method('getUser')->willReturn($userMock);

        $seatMock = $this->createMock(Seat::class);
        $this->seatRepositoryMock->method('findReservedSeatsByHallSession')
            ->with($hallSessionMock)
            ->willReturn([$seatMock]);
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $this->voter->vote($this->token, $hallSessionMock, ['ROLE_ADMIN_HALL_SESSION_DELETE']));
    }

}