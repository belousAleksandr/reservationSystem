<?php

declare(strict_types=1);

namespace App\Tests\Security\Voter;

use App\Entity\Cinema;
use App\Entity\Hall;
use App\Repository\HallRepository;
use App\Security\Voter\CinemaVoter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CinemaVoterTest extends TestCase
{
    const ROLE_DELETE = 'ROLE_ADMIN_CINEMA_DELETE';
    /** @var TokenInterface|MockObject */
    protected $token;
    /** @var CinemaVoter */
    private $voter;
    /** @var AccessDecisionManagerInterface|MockObject */
    private $decisionManagerMock;

    /** @var HallRepository|MockObject */
    private $hallRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->hallRepository = $this->createMock(HallRepository::class);
        $this->decisionManagerMock = $this->createMock(AccessDecisionManagerInterface::class);
        $this->token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $this->voter = new CinemaVoter($this->decisionManagerMock, $this->hallRepository);
    }

    public function getSupportTests(): array
    {
        return [
            [[self::ROLE_DELETE], VoterInterface::ACCESS_DENIED, new Cinema(), 'ACCESS_DENIED if user has no permissions'],
            [['TEST_ROLE'], VoterInterface::ACCESS_ABSTAIN, new Cinema(), 'ACCESS_ABSTAIN if no attribute is supported'],

            [[self::ROLE_DELETE], VoterInterface::ACCESS_ABSTAIN, $this, 'ACCESS_ABSTAIN if class is not supported'],

            [[self::ROLE_DELETE], VoterInterface::ACCESS_ABSTAIN, null, 'ACCESS_ABSTAIN if object is null'],

            [[], VoterInterface::ACCESS_ABSTAIN, new Cinema(), 'ACCESS_ABSTAIN if no attributes were provided'],
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
        $cinemaMock = $this->createMock(Cinema::class);
        $hallMock = $this->createMock(Hall::class);
        $hallMock2 = $this->createMock(Hall::class);
        $this->hallRepository->method('findBy')->with(['cinema' => $cinemaMock])->willReturn(
            [$hallMock, $hallMock2]
        );

        $userMock = $this->createMock(UserInterface::class);
        $this->token->method('getUser')->willReturn($userMock);

        $this->decisionManagerMock
            ->expects(self::exactly(2))
            ->method('decide')
            ->withConsecutive(
                [$this->token, [HallVoterTest::ROLE_DELETE], $hallMock],
                [$this->token, [HallVoterTest::ROLE_DELETE], $hallMock2]
            )->willReturn(true);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $this->voter->vote($this->token, $cinemaMock, [self::ROLE_DELETE]));
    }

    /**
     * @test
     */
    public function canDeleteWithoutHalls()
    {
        $cinemaMock = $this->createMock(Cinema::class);
        $this->hallRepository->method('findBy')->with(['cinema' => $cinemaMock])->willReturn([]);

        $userMock = $this->createMock(UserInterface::class);
        $this->token->method('getUser')->willReturn($userMock);

        $this->decisionManagerMock
            ->expects(self::never())
            ->method('decide');

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $this->voter->vote($this->token, $cinemaMock, [self::ROLE_DELETE]));
    }

    /**
     * @test
     */
    public function cannotDelete()
    {
        $cinemaMock = $this->createMock(Cinema::class);
        $hallMock = $this->createMock(Hall::class);
        $hallMock2 = $this->createMock(Hall::class);
        $this->hallRepository->method('findBy')->with(['cinema' => $cinemaMock])->willReturn(
            [$hallMock, $hallMock2]
        );

        $userMock = $this->createMock(UserInterface::class);
        $this->token->method('getUser')->willReturn($userMock);

        $this->decisionManagerMock
            ->expects(self::exactly(2))
            ->method('decide')
            ->withConsecutive(
                [$this->token, [HallVoterTest::ROLE_DELETE], $hallMock],
                [$this->token, [HallVoterTest::ROLE_DELETE], $hallMock2]
            )->willReturnOnConsecutiveCalls(true, false);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $this->voter->vote($this->token, $cinemaMock, [self::ROLE_DELETE]));
    }
}