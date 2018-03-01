<?php

namespace Api\ProjectTest\V1\Rest\ProjectTest\Controller;

use Api\Project\V1\Rest\Project\Controller\ProjectController;
use Doctrine\ORM\EntityManager;
use Zsa\Employee\Entity\Employee;
use Zsa\Program\Entity\Program;
use Zsa\Project\Dto\Project\ProjectEditTo;
use Zsa\Project\Entity\Project;
use Zsa\Project\Service\ProjectService;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\Api\Common\ApiTest;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

/**
 * Class ProjectControllerTest
 *
 * @package Api\ProjectTest\V1\Rest\ProjectTest\Controller
 */
class ProjectControllerTest extends ApiTest
{
    /**
     * @var bool
     */
    protected $traceError = false;

    /**
     * @var  ProjectService|ObjectProphecy
     */
    protected $projectService;

    /**
     * @var  Project|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $project;

    /**
     * @var Program|\PHPUnit_Framework_MockObject_MockObject
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
            $this->getApplicationServiceLocator(), ProjectService::class, $this->mockProjectService()->reveal()
        );

        $this->testUser->method('getEmployee')->willReturn($this->mockEmployee());
    }

    /**
     * @return void
     */
    public function testFetchAllActionCanBeAccessed()
    {
        $this->dispatch('/api/project', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertControllerName(ProjectController::class);
        $this->assertControllerClass('ProjectController');
    }

    /**
     * @return void
     */
    public function testFetchOneActionCanBeAccessed()
    {
        $this->projectService->find(Argument::any())->shouldBeCalled();

        $this->dispatch('/api/project/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertControllerName(ProjectController::class);
        $this->assertControllerClass('ProjectController');
    }

    /**
     * @return void
     */
    public function testCreateAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $this->projectService->create(Argument::type(ProjectEditTo::class), $this->testUser)->shouldBeCalled();

        $data = [
            'title' => 'TestCase',
            'code'  => 'TestCode',
        ];

        $request->setContent(Json::encode($data));

        $this->dispatch('/api/project', 'POST');

        $this->assertResponseStatusCode(201);
    }

    /**
     * @return void
     */
    public function testUpdateAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $this->projectService->update(
            Argument::type(Project::class), Argument::type(ProjectEditTo::class), $this->testUser
        )->shouldBeCalled();

        $data = [
            'title'   => 'TestCaseUpdate',
            'code'    => 'TestCodeUpdate',
            'program' => 1,
        ];

        $request->setContent(Json::encode($data));

        $this->dispatch('/api/project/2', 'PUT');

        $this->assertResponseStatusCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteProjectAction()
    {
        $this->projectService->archive(Argument::type(Project::class), Argument::any())->shouldBeCalled();

        $this->dispatch('/api/project/2', 'DELETE');
        $this->assertResponseStatusCode(200);
    }

    /**
     * @return ProjectService|ObjectProphecy
     */
    protected function mockProjectService()
    {
        $this->projectService = $this->prophesize(ProjectService::class);
        $this->projectService->find(Argument::any())->willReturn($this->mockProject());
        $this->projectService->fetchAll($this->testUser, [])->willReturn(new Paginator(new ArrayAdapter([])));
        $this->projectService->create(Argument::type(ProjectEditTo::class), $this->testUser)->willReturn(
            $this->mockProject()
        );

        $this->projectService->update(
            Argument::type(Project::class), Argument::type(ProjectEditTo::class), $this->testUser
        )->willReturn($this->mockProject());

        $this->projectService->remove(Argument::type(Project::class))->willReturn(true);

        return $this->projectService;
    }

    /**
     * @return Project|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockProject()
    {
        if (!$this->project) {
            $this->project = $this->createConfiguredMock(
                Project::class,
                [
                    'getId'         => 2,
                    'getCreatedBy'  => $this->testUser,
                    'getProgram'    => $this->mockProgram(),
                    'getController' => $this->mockEmployee(),

                ]
            );
        }

        return $this->project;
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
                    'getId'         => 2,
                    'getTitle'      => 'Test program',
                    'getCreatedBy'  => $this->testUser,
                    'getController' => $this->mockEmployee(),
                ]
            );
        }

        return $this->program;
    }

    /**
     * @return Employee|\PHPUnit_Framework_MockObject_MockObject
     */
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
