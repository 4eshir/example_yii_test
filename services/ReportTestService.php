<?php

namespace app\services;

use app\models\components\RoleBaseAccess;
use app\models\work\CompanyWork;
use app\models\work\DocumentOutWork;
use app\models\work\InOutDocsWork;
use app\models\components\Logger;
use app\models\components\UserRBAC;
use app\models\components\ConsoleOutput;
use app\models\work\PeoplePositionBranchWork;
use app\models\work\PeopleWork;
use app\models\work\PositionWork;
use Arhitector\Yandex\Disk;
use Yii;
use app\models\work\DocumentInWork;
use app\models\SearchDocumentIn;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * Вспомогательный сервис для Unit-тестирования
 * раздела отчетов
 */
class ReportTestService
{
    public function __construct()
    {
        
    }

    // Сравнение тестовых результатов с эталонным
    public function compareParticipantsTest($testResults, $expectedResults)
    {
        $result = [new ConsoleOutput];

        if ($testResults[0][0] === $expectedResults[0][0] &&
            $testResults[0][1] === $expectedResults[0][1] &&
            $testResults[0][2] == $expectedResults[0][2])
            $result[] = new ConsoleOutput('| Test #1 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #1 failed                   |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResult1[0] === $expectedResult1[0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResult1[1] === $expectedResult1[1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResult1[2] == $expectedResult1[2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);
        }

        if ($testResults[1][0] === $expectedResults[1][0] &&
            $testResults[1][1] === $expectedResults[1][1] &&
            $testResults[1][2] == $expectedResults[1][2])
            $result[] = new ConsoleOutput('| Test #2 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #2 failed                   |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[1][0] === $expectedResults[1][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[1][1] === $expectedResults[1][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[1][2] == $expectedResults[1][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);

            $result[] = new ConsoleOutput(count($testResults[1][1])."\n", Console::FG_CYAN);
            foreach ($testResult2[4] as $one)
                $result[] = new ConsoleOutput($one."\n", Console::FG_CYAN);
        }

        if ($testResults[2][0] === $expectedResults[2][0] &&
            $testResults[2][1] === $expectedResults[2][1] &&
            $testResults[2][2] == $expectedResults[2][2])
            $result[] = new ConsoleOutput('| Test #3 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #3 failed                   |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[2][0] === $expectedResults[2][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[2][1] === $expectedResults[2][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[2][2] == $expectedResults[2][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);
        }

        if ($testResults[3][0] === $expectedResults[3][0] &&
            $testResults[3][1] === $expectedResults[3][1] &&
            $testResults[3][2] == $expectedResults[3][2])
            $result[] = new ConsoleOutput('| Test #4 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #4 failed                  |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[3][0] === $expectedResults[3][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[3][1] === $expectedResults[3][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[3][2] == $expectedResults[3][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);

            foreach ($testResult4[3] as $one)
                $result[] = new ConsoleOutput($one."\n", Console::FG_YELLOW);
        }

        if ($testResults[4][0] === $expectedResults[4][0] &&
            $testResults[4][1] === $expectedResults[4][1] &&
            $testResults[4][2] == $expectedResults[4][2])
            $result[] = new ConsoleOutput('| Test #5 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #5 failed                  |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[4][0] === $expectedResults[4][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[4][1] === $expectedResults[4][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[4][2] == $expectedResults[4][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);
        }

        if ($testResults[5][0] === $expectedResults[5][0] &&
            $testResults[5][1] === $expectedResults[5][1] &&
            $testResults[5][2] == $expectedResults[5][2])
            $result[] = new ConsoleOutput('| Test #6 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #6 failed                   |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[5][0] === $expectedResults[5][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[5][1] === $expectedResults[5][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[5][2] == $expectedResults[5][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);
        }

        if ($testResults[6][0] === $expectedResults[6][0] &&
            $testResults[6][1] === $expectedResults[6][1] &&
            $testResults[6][2] == $expectedResults[6][2])
            $result[] = new ConsoleOutput('| Test #7 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #7 failed                   |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[6][0] === $expectedResults[6][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[6][1] === $expectedResults[6][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[6][2] == $expectedResults[6][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);
        }

        if ($testResults[7][0] === $expectedResults[7][0] &&
            $testResults[7][1] === $expectedResults[7][1] &&
            $testResults[7][2] == $expectedResults[7][2])
            $result[] = new ConsoleOutput('| Test #8 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #8 failed                   |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[7][0] === $expectedResults[7][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[7][1] === $expectedResults[7][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[7][2] == $expectedResults[7][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);
        }

        if ($testResults[8][0] === $expectedResults[8][0] &&
            $testResults[8][1] === $expectedResults[8][1] &&
            $testResults[8][2] == $expectedResults[8][2])
            $result[] = new ConsoleOutput('| Test #9 was passed successfully  |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #9 failed                   |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[8][0] === $expectedResults[8][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[8][1] === $expectedResults[8][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[8][2] == $expectedResults[8][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);

            foreach ($testResults[8][1] as $one)
                $result[] = new ConsoleOutput($one."\n", Console::FG_YELLOW);
        }

        if ($testResults[9][0] === $expectedResults[9][0] &&
            $testResults[9][1] === $expectedResults[9][1] &&
            $testResults[9][2] == $expectedResults[9][2])
            $result[] = new ConsoleOutput('| Test #10 was passed successfully |'."\n", Console::FG_GREEN);
        else
        {
            $result[] = new ConsoleOutput('| Test #10 failed                  |'."\n", Console::FG_RED);
            $result[] = new ConsoleOutput($testResults[9][0] === $expectedResults[9][0] ? "T1 OK\n" : "T1 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[9][1] === $expectedResults[9][1] ? "T2 OK\n" : "T2 FAIL\n", Console::FG_YELLOW);
            $result[] = new ConsoleOutput($testResults[9][2] == $expectedResults[9][2] ? "T3 OK\n" : "T3 FAIL\n", Console::FG_YELLOW);
        }

        return $result;
    }
}