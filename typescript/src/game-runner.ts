import { Game } from "./game";

export class GameRunner {
  public static main(): void {
    const game = new Game();
    game.addPlayer("Chet");
    game.addPlayer("Pat");
    game.addPlayer("Sue");

    let notAWinner;
    Array.from({ length: 100 }).forEach((_, index) => {
      game.roll((index % 6) + 1);

      if (index % 10 == 0) {
        notAWinner = game.wrongAnswer();
      } else {
        notAWinner = game.wasCorrectlyAnswered();
      }
    });
  }
}

GameRunner.main();
