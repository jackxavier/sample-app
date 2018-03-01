<?php

namespace Api\ProgramTest\V1\Rest\ProgramTest\Controller;

use Api\Program\V1\Rest\Program\Controller\ProgramController;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Zsa\Employee\Entity\Employee;
use Zsa\Program\Entity\Program;
use Zsa\Program\Dto\Program\ProgramEditTo;
use Zsa\Program\Service\ProgramService;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\Api\Common\ApiTest;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

/**
 * Class ProgramControllerTest
 *
 * @package Api\ProgramTest\V1\Rest\ProgramTest\Controller
 */
class ProgramControllerTest extends ApiTest
{
    /**
     * @var bool
     */
    protected $traceError = false;

    /**
     * @var  ProgramService|ObjectProphecy
     */
    protected $programService;

    /**
     * @var  Program|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $program;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->configureServiceManager(
            $this->getApplicationServiceLocator(), ProgramService::class, $this->mockProgramService()->reveal()
        );
    }

    /**
     * @return void
     */
    public function testFetchAllActionCanBeAccessed()
    {
        $this->dispatch('/api/program', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertControllerName(ProgramController::class);
        $this->assertControllerClass('ProgramController');
    }

    /**
     * @return void
     */
    public function testFetchOneActionCanBeAccessed()
    {
        $this->programService->find(Argument::any())->shouldBeCalled();

        $this->dispatch('/api/program/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertControllerName(ProgramController::class);
        $this->assertControllerClass('ProgramController');
    }

    /**
     * @return void
     */
    public function testCreateAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $this->programService->create(Argument::type(ProgramEditTo::class), $this->testUser)->shouldBeCalled();

        $data = [
            'title'       => 'TestCaseUpdate',
            'description' => 'TestDescription',
        ];

        $request->setContent(Json::encode($data));

        $this->dispatch('/api/program', 'POST');

        $this->assertResponseStatusCode(201);
    }

    /**
     * @return void
     */
    public function testUpdateAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $this->programService->update(
            Argument::type(Program::class), Argument::type(ProgramEditTo::class), $this->testUser
        )->shouldBeCalled();

        $data = [
            'title'       => 'TestCaseUpdate',
            'description' => 'TestDescription',
        ];

        $request->setContent(Json::encode($data));

        $this->dispatch('/api/program/2', 'PUT');

        $this->assertResponseStatusCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteProgramAction()
    {
        $this->programService->archive(Argument::type(Program::class), Argument::any())->shouldBeCalled();

        $this->dispatch('/api/program/2', 'DELETE');
        $this->assertResponseStatusCode(200);
    }

    /**
     * @return ProgramService|ObjectProphecy
     */
    protected function mockProgramService()
    {
        $this->programService = $this->prophesize(ProgramService::class);
        $this->programService->find(Argument::any())->willReturn($this->mockProgram());
        $this->programService->fetchAll([])->willReturn(new Paginator(new ArrayAdapter([])));
        $this->programService->create(Argument::type(ProgramEditTo::class), $this->testUser)->willReturn(
            $this->mockProgram()
        );

        $this->programService->update(
            Argument::type(Program::class), Argument::type(ProgramEditTo::class), $this->testUser
        )->willReturn($this->mockProgram());

        $this->programService->changeStatus(Argument::type(Program::class), Argument::any())->willReturn(true);

        return $this->programService;
    }

    /**
     * @return Program|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockProgram()
    {
        if (!$this->program) {
            $this->program = $this->createConfiguredMock(
                Program::class,
                [
                    'getId'        => 1,
                    'getTitle'     => 'Test program',
                    'getCreatedBy' => $this->testUser,
                    'getController'   => $this->mockEmployee(),
                ]
            );
        }

        return $this->program;
    }

    protected function mockEmployee()
    {
        return $this->createConfiguredMock(
            Employee::class,
            [
                'getId'   => 1,
                'getUser' => $this->testUser,
            ]
        );
    }
}