<?php

namespace Api\Project\V1\Rpc\ProjectTasks;

use Axmit\Util\Filter\CollectionFilter;
use Epos\UserCore\Service\UserService;
use Zsa\Project\Entity\Project;
use Zsa\Project\Service\ProjectService;
use Zsa\Task\Form\TaskFilterForm;
use Zsa\Task\Service\TaskService;
use Interop\Container\ContainerInterface;
use Zend\Filter\FilterInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ContentNegotiation\ViewModel;
use ZF\Hal\Collection;

/**
 * Class ProjectTasksController
 *
 * @package Api\Project\V1\Rpc\ProjectTasks
 */
class ProjectTasksController extends AbstractActionController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * @var TaskService
     */
    protected $taskService;

    /**
     * @var ContainerInterface
     */
    protected $formContainer;

    /**
     * @var FilterInterface
     */
    protected $converter;

    /**
     * @var Project
     */
    protected $project;

    /**
     * ProjectTasksController constructor.
     *
     * @param UserService        $userService
     * @param ProjectService     $projectService
     * @param TaskService        $taskService
     * @param ContainerInterface $formContainer
     * @param FilterInterface    $converter
     */
    public function __construct(
        UserService $userService,
        ProjectService $projectService,
        TaskService $taskService,
        ContainerInterface $formContainer,
        FilterInterface $converter
    ) {
        $this->userService    = $userService;
        $this->projectService = $projectService;
        $this->taskService    = $taskService;
        $this->formContainer  = $formContainer;
        $this->converter      = $converter;
    }

    /**
     * @return ViewModel
     */
    public function projectTasksAction()
    {
        /** @var TaskFilterForm $filterForm */
        $filterForm  = $this->formContainer->get(TaskFilterForm::class);
        $queryParams = $filterForm->filterValues($this->params()->fromQuery());

        $taskCollection = $this->taskService->getProjectTasks($this->project, $queryParams);
        $taskCollection->setFilter(new CollectionFilter($this->converter));

        $halCollection = new Collection($taskCollection);
        $halCollection->setCollectionRoute('api-project.rpc.project-tasks');
        $halCollection->setPageSize(-1);

        return new ViewModel(['payload' => $halCollection]);
    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed|ApiProblemResponse
     */
    public function onDispatch(MvcEvent $e)
    {
        if (!$this->userService->hasAuthenticatedUser()) {
            return new ApiProblemResponse(
                new ApiProblem(401, 'Для продолжения текущего действия необходимо войти в систему')
            );
        }

        $projectId = $this->params()->fromRoute('project_id');

        $this->project = $projectId > 0 ? $this->projectService->find($projectId) : null;

        if (!$this->project) {
            return new ApiProblemResponse(new ApiProblem(404, 'Проект не найден'));
        }

        return parent::onDispatch($e);
    }
}
