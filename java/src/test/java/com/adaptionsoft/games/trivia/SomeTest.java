package com.adaptionsoft.games.trivia;


import com.adaptionsoft.games.trivia.runner.GameRunner;
import org.junit.jupiter.api.Assertions;
import org.junit.jupiter.api.Test;

import java.io.ByteArrayOutputStream;
import java.io.FileWriter;
import java.io.PrintStream;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.Random;

import static org.junit.jupiter.api.Assertions.assertTrue;

public class SomeTest {

    @Test
    public void golden() throws Exception {
        ByteArrayOutputStream salidaCapturada = new ByteArrayOutputStream();
        PrintStream flujoSalida = new PrintStream(salidaCapturada);
        System.setOut(flujoSalida);
        var expectedDataPath = Paths.get("src/test/resources/expected-data.out");
        var expectedData = Files.readString(expectedDataPath);

        var gameRunner = new GameRunner() {
            protected Random getRandom() {
                Random rand = new Random(1);
                return rand;
            }
        };

        gameRunner.play();



        // Files.writeString(expectedDataPath, salidaCapturada.toString());

        Assertions.assertEquals(expectedData, salidaCapturada.toString());

    }
}
