<?php

declare(strict_types=1);

namespace App\Tests\Security\Voter;

use App\Entity\Hall;
use App\Entity\HallSession;
use App\Security\Voter\HallSessionVoter;
use App\Security\Voter\HallVoter;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class HallVoterTest extends TestCase
{
    const ROLE_DELETE = 'ROLE_ADMIN_HALL_DELETE';
    /** @var TokenInterface|MockObject */
    protected $token;
    /** @var HallSessionVoter */
    private $voter;
    /** @var AccessDecisionManagerInterface|MockObject */
    private $decisionManagerMock;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->decisionManagerMock = $this->createMock(AccessDecisionManagerInterface::class);
        $this->token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $this->voter = new HallVoter($this->decisionManagerMock);
    }

    public function getSupportTests(): array
    {
        return [
            [[self::ROLE_DELETE], VoterInterface::ACCESS_DENIED, new Hall(), 'ACCESS_DENIED if user has no permissions'],
            [['TEST_ROLE'], VoterInterface::ACCESS_ABSTAIN, new Hall(), 'ACCESS_ABSTAIN if no attribute is supported'],

            [[self::ROLE_DELETE], VoterInterface::ACCESS_ABSTAIN, $this, 'ACCESS_ABSTAIN if class is not supported'],

            [[self::ROLE_DELETE], VoterInterface::ACCESS_ABSTAIN, null, 'ACCESS_ABSTAIN if object is null'],

            [[], VoterInterface::ACCESS_ABSTAIN, new Hall(), 'ACCESS_ABSTAIN if no attributes were provided'],
        ];
    }

    /**
     * @dataProvider getSupportTests
     * @test
     * @param array $attributes
     * @param string $expectedVote
     * @param $object
     * @param string $message
     */
    public function support(array $attributes, string $expectedVote, $object, string $message)
    {
        $this->assertEquals($expectedVote, $this->voter->vote($this->token, $object, $attributes), $message);
    }

    /**
     * @test
     */
    public function canDelete()
    {
        $hallMock = $this->createMock(Hall::class);
        $hallSessionMock = $this->createMock(HallSession::class);
        $hallSessionMock2 = $this->createMock(HallSession::class);

        $hallMock->method('getHallSessions')->willReturn(new ArrayCollection([
            $hallSessionMock,
            $hallSessionMock2,
        ]));
        $userMock = $this->createMock(UserInterface::class);
        $this->token->method('getUser')->willReturn($userMock);

        $this->decisionManagerMock
            ->expects(self::exactly(2))
            ->method('decide')
            ->withConsecutive(
                [$this->token, [HallSessionVoterTest::ROLE_DELETE], $hallSessionMock],
                [$this->token, [HallSessionVoterTest::ROLE_DELETE], $hallSessionMock2]
            )->willReturn(true);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $this->voter->vote($this->token, $hallMock, [self::ROLE_DELETE]));
    }

    /**
     * @test
     */
    public function canDeleteWithoutHallSessions()
    {
        $hallMock = $this->createMock(Hall::class);

        $hallMock->method('getHallSessions')->willReturn(new ArrayCollection());
        $userMock = $this->createMock(UserInterface::class);
        $this->token->method('getUser')->willReturn($userMock);

        $this->decisionManagerMock->expects(self::never())->method('decide');

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $this->voter->vote($this->token, $hallMock, [self::ROLE_DELETE]));
    }

    /**
     * @test
     */
    public function cannotDelete()
    {
        $hallMock = $this->createMock(Hall::class);
        $hallSessionMock = $this->createMock(HallSession::class);
        $hallSessionMock2 = $this->createMock(HallSession::class);

        $hallMock->method('getHallSessions')->willReturn(new ArrayCollection([
            $hallSessionMock,
            $hallSessionMock2,
        ]));
        $userMock = $this->createMock(UserInterface::class);
        $this->token->method('getUser')->willReturn($userMock);

        $this->decisionManagerMock
            ->expects(self::exactly(2))
            ->method('decide')
            ->withConsecutive(
                [$this->token, [HallSessionVoterTest::ROLE_DELETE], $hallSessionMock],
                [$this->token, [HallSessionVoterTest::ROLE_DELETE], $hallSessionMock2]
            )->willReturnOnConsecutiveCalls(true, false);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $this->voter->vote($this->token, $hallMock, [self::ROLE_DELETE]));
    }

}