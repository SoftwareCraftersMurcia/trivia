import {Game} from './game';

export class GameRunner {
    public static main(): void {
        const game = new Game();
        game.add("Chet");
        game.add("Pat");
        game.add("Sue");

        let notAWinner;
        Array.from({length: 100}).forEach((_, index) => {
            game.roll(index % 6 + 1);
        
            if (index % 2 == 0) {
            notAWinner = game.wrongAnswer();
            } else {
            notAWinner = game.wasCorrectlyAnswered();
            }
        
        });
    }
}

GameRunner.main();

  