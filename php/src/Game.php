<?php
declare(strict_types=1);

namespace Kata;

function echoln(string $string): void
{
    echo $string . "\n";
}

class Game
{
    private const BOARD_SIZE = 12;
    private const QUESTIONS_COUNT = 50;
    private const WINNING_PURSE = 6;
    
    /** @var string[] */
    private array $players = [];
    /** @var int[] */
    private array $places = [0];
    /** @var int[] */
    private array $purses = [0];
    /** @var bool[] */
    private array $inPenaltyBox = [false];
    
    /** @var string[] */
    private array $popQuestions = [];
    /** @var string[] */
    private array $scienceQuestions = [];
    /** @var string[] */
    private array $sportsQuestions = [];
    /** @var string[] */
    private array $rockQuestions = [];
    
    private int $currentPlayer = 0;
    private bool $isGettingOutOfPenaltyBox = false;

    public function __construct()
    {
        for ($i = 0; $i < self::QUESTIONS_COUNT; $i++) {
            $this->popQuestions[] = "Pop Question " . $i;
            $this->scienceQuestions[] = "Science Question " . $i;
            $this->sportsQuestions[] = "Sports Question " . $i;
            $this->rockQuestions[] = $this->createRockQuestion($i);
        }
    }

    private function createRockQuestion(int $index): string
    {
        return "Rock Question " . $index;
    }

    public function add(string $playerName): bool
    {
        $this->players[] = $playerName;
        $this->places[$this->howManyPlayers()] = 0;
        $this->purses[$this->howManyPlayers()] = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        echoln($playerName . " was added");
        echoln("They are player number " . count($this->players));
        return true;
    }

    public function howManyPlayers(): int
    {
        return count($this->players);
    }

    public function roll(int $roll): void
    {
        echoln($this->players[$this->currentPlayer] . " is the current player");
        echoln("They have rolled a " . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            $this->handlePenaltyBoxRoll($roll);
            return;
        }

        $this->movePlayer($roll);
        $this->announceNewPositionAndCategory();
        $this->askQuestion();
    }

    private function handlePenaltyBoxRoll(int $roll): void
    {
        if ($roll % 2 !== 0) {
            $this->isGettingOutOfPenaltyBox = true;
            echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
            $this->movePlayer($roll);
            $this->announceNewPositionAndCategory();
            $this->askQuestion();
        } else {
            echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
            $this->isGettingOutOfPenaltyBox = false;
        }
    }

    private function movePlayer(int $roll): void
    {
        $this->places[$this->currentPlayer] = ($this->places[$this->currentPlayer] + $roll) % self::BOARD_SIZE;
    }

    private function announceNewPositionAndCategory(): void
    {
        echoln($this->players[$this->currentPlayer] . "'s new location is " . $this->places[$this->currentPlayer]);
        echoln("The category is " . $this->currentCategory());
    }

    private function askQuestion(): void
    {
        $category = $this->currentCategory();
        $question = match ($category) {
            'Pop' => array_shift($this->popQuestions),
            'Science' => array_shift($this->scienceQuestions),
            'Sports' => array_shift($this->sportsQuestions),
            'Rock' => array_shift($this->rockQuestions),
            default => throw new \RuntimeException("Unknown category: $category"),
        };
        echoln($question);
    }

    private function currentCategory(): string
    {
        $place = $this->places[$this->currentPlayer];
        
        return match ($place % 4) {
            0 => 'Pop',
            1 => 'Science',
            2 => 'Sports',
            3 => 'Rock',
        };
    }

    public function wasCorrectlyAnswered(): bool
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if (!$this->isGettingOutOfPenaltyBox) {
                $this->advancePlayer();
                return true;
            }
            return $this->handleCorrectAnswer();
        }
        return $this->handleCorrectAnswer();
    }

    private function handleCorrectAnswer(): bool
    {
        echoln("Answer was correct!!!!");
        $this->purses[$this->currentPlayer]++;
        echoln($this->players[$this->currentPlayer] . " now has " . $this->purses[$this->currentPlayer] . " Gold Coins.");

        $winner = $this->didPlayerWin();
        $this->advancePlayer();
        return $winner;
    }

    public function wrongAnswer(): bool
    {
        echoln("Question was incorrectly answered");
        echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->advancePlayer();
        return true;
    }

    private function advancePlayer(): void
    {
        $this->currentPlayer = ($this->currentPlayer + 1) % count($this->players);
    }

    private function didPlayerWin(): bool
    {
        return $this->purses[$this->currentPlayer] !== self::WINNING_PURSE;
    }
}
