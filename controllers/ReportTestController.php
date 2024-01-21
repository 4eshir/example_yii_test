<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands\report_test;

use app\commands\SupCommandsController;
use app\models\common\EventLevel;
use app\models\common\Focus;
use app\models\common\ForeignEventParticipants;
use app\models\components\report\ReportConst;
use app\models\components\report\SupportReportFunctions;
use app\models\LoginForm;
use app\models\test\common\GetParticipantsTeam;
use app\models\test\work\GetParticipantsTeamWork;
use app\models\work\AllowRemoteWork;
use app\models\work\BranchWork;
use app\models\work\EventLevelWork;
use app\models\work\FocusWork;
use app\models\work\ForeignEventParticipantsWork;
use app\models\work\ForeignEventWork;
use app\models\work\ParticipantAchievementWork;
use app\models\work\TeacherParticipantWork;
use app\models\work\TrainingGroupParticipantWork;
use app\models\work\VisitWork;
use Mpdf\Tag\Br;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Console;
use app\services\ReportTestService;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ReportTestController extends Controller
{
    private $reportTestService;

    public function __construct(ReportTestService $service)
    {
        $this->reportTestService = $service ? : new ReportTestService();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    //--Запуск ВСЕХ тестов модуля--
    public function actionTestAll()
    {
        $this->actionParticipantTest();
        $this->actionGroupTest();
    }
    //-----------------------------



    //--Экшн и вспомогательные функции для тестирования участников мероприятий--
    public function actionParticipantTest()
    {
        $this->stdout("\n\n| Foreign event participants and achievements tests\n", Console::FG_CYAN);
        $this->GetParticipantsTest(); //Тест на выгрузку участников деятельности по заданным параметрам
        $this->stdout("\n");
        $this->ParticipantAchievementsTest(); //Тест на выгрузку победителей и призеров по заданным параметрам
        $this->stdout("\n|".str_repeat("-", 50)."\n", Console::FG_CYAN);
    }


    //--Экшн и вспомогательные функции для тестирования списко обучающихся--
    private function GetParticipantsTest()
    {
        $this->stdout("\n------(Get_Participants tests)------\n|".str_repeat(" ", 34)."|\n", Console::FG_PURPLE);

        $testResult1 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2020-01-01', '2023-01-01', 0);
        $testResult2 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2020-01-01', '2023-01-01', 1);
        $testResult3 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2020-01-01', '2023-01-01', 0, 1);
        $testResult4 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2020-01-01', '2023-01-01', 1, 1);
        $testResult5 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2020-01-01', '2023-01-01', 1, 0, [EventLevelWork::INTERNAL]);
        $testResult6 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2020-01-01', '2023-01-01', 1, 1, [EventLevelWork::INTERNAL]);
        $testResult7 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2020-01-01', '2023-01-01', 1, 0, [EventLevelWork::INTERNAL], [BranchWork::CDNTT]);
        $testResult8 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2022-02-01', '2023-01-01', 0, 0, EventLevelWork::ALL, [BranchWork::TECHNO, BranchWork::COD]);
        $testResult9 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2022-01-01', '2023-01-01', 1, 0, EventLevelWork::ALL, BranchWork::ALL, [FocusWork::ART, FocusWork::SPORT]);
        $testResult10 = SupportReportFunctions::GetParticipants(ReportConst::TEST, '2022-01-01', '2022-01-30', 0, 1, EventLevelWork::ALL, BranchWork::ALL, [FocusWork::TECHNICAL], AllowRemoteWork::ALL);

        $expectedResult1 = [[2, 2, 3, 5, 6, 7, 8], [], 7];
        $expectedResult2 = [[2, 2, 3, 5, 6, 7, 8], [1, 2], 9];
        $expectedResult3 = [[2, 3, 5, 6, 7, 8], [], 6];
        $expectedResult4 = [[2, 3, 5, 6, 7, 8], [1, 2], 8];
        $expectedResult5 = [[2, 3], [1], 3];
        $expectedResult6 = [[2, 3], [1], 3];
        $expectedResult7 = [[], [1], 1];
        $expectedResult8 = [[6, 7, 8], [], 3];
        $expectedResult9 = [[2, 3, 5], [], 3];
        $expectedResult10 = [[], [], 0];

        $testResults = [$testResult1, $testResult2, $testResult3, $testResult4, $testResult5, 
                        $testResult6, $testResult7, $testResult8, $testResult9, $testResult10];
        $expectedResults = [$expectedResult1, $expectedResult2, $expectedResult3, $expectedResult4, $expectedResult5, 
                            $expectedResult6, $expectedResult7, $expectedResult8, $expectedResult9, $expectedResult10];

        // Сравниваем полученные данные с эталонными
        $result = $this->reportTestService->compareParticipantsTest($testResults, $expectedResults);

        foreach ($result as $one)
            $this->stdout($one->text, $one->color);


        $this->stdout(str_repeat("-", 36), Console::FG_PURPLE);



        return ExitCode::OK;
    }

}
