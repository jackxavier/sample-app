<?php

namespace Api\Project\V1\Rest\Project;

use Axmit\Util\ApiProblem\ValidationApiProblem;
use Interop\Container\ContainerInterface;
use Zend\Filter\FilterInterface;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;
use Zsa\Project\Dto\Project\ProjectEditTo;
use Zsa\Project\Entity\Project;
use Zsa\Project\Form\ProjectForm;
use Zsa\Project\Service\ProjectService;

/**
 * Class ProjectResource
 *
 * @package Api\Project\V1\Rest\Project
 */
class ProjectResource extends AbstractResourceListener
{
    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var FilterInterface
     */
    protected $converter;

    /**
     * @var ContainerInterface
     */
    protected $formContainer;

    /**
     * @var AppLogger
     */
    protected $logger;

    /**
     * @var UserInterface|User
     */
    protected $user;

    /**
     * EmployeeResource constructor.
     *
     * @param UserService        $userService
     * @param ProjectService     $projectService
     * @param ContainerInterface $formContainer
     * @param FilterInterface    $filter
     * @param AppLogger          $logger
     */
    public function __construct(
        UserService $userService,
        ProjectService $projectService,
        ContainerInterface $formContainer,
        FilterInterface $filter,
        AppLogger $logger
    ) {
        $this->projectService = $projectService;
        $this->userService    = $userService;
        $this->formContainer  = $formContainer;
        $this->converter      = $filter;
        $this->logger         = $logger;
    }

    /**
     * @param mixed $data
     *
     * @return ApiProblem
     */
    public function create($data)
    {
        /** @var EmployeeEditForm $projectForm */
        $projectForm = $this->formContainer->get(ProjectForm::class);
        $projectForm->setData((array)$data);

        if (!$projectForm->isValid()) {
            return new ValidationApiProblem($projectForm->getMessages());
        }

        /** @var ProjectEditTo $projectEditTo */
        $projectEditTo = $projectForm->getObject();

        try {
            $result = $this->projectService->create($projectEditTo, $this->user);
        } catch (\Throwable $exception) {
            $this->logger->logOnThrowable($exception);
            $result = null;
        }

        if (!$result) {
            return new ApiProblem(500, 'Возникла ошибка при создании проекта. Повторите попытку позже');
        }

        return $this->converter->filter($result);
    }

    /**
     * @param mixed $id
     *
     * @return SuccessResponse|ApiProblem
     */
    public function delete($id)
    {
        $project = $this->projectService->find($id);

        if (!$project) {
            return new ApiProblem(404, 'Проект не найден');
        }

        if (!$this->isUserGrantedForEditProject($project)) {
            return new ApiProblem(403, 'Доступ запрещен');
        }

        $queryParams = $this->event->getParam('fromQuery');
        $removePips  = isset($queryParams['removePips']) ? (bool)$queryParams['removePips'] : false;

        try {
            $this->projectService->archive($project, $removePips);
        } catch (\Throwable $exception) {
            $this->logger->logOnThrowable($exception);

            return new ApiProblem(500, 'Не удалось удалить проект');

        }

        return SuccessResponse::withMessages(['Проект успешно удален']);
    }

    /**
     * @param mixed $id
     *
     * @return mixed|ApiProblem
     */
    public function fetch($id)
    {
        $project = $this->projectService->find($id);

        if (!$project) {
            return new ApiProblem(404, 'Проект не найден');
        }

        if (!$this->isUserGrantedForEditProject($project)) {
            return new ApiProblem(403, 'Доступ запрещен');
        }

        return $this->converter->filter($project);
    }

    /**
     * @param array $params
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($params = [])
    {
        $paginator = $this->projectService->fetchAll($this->user, (array)$params);
        $paginator->setFilter(new CollectionFilter($this->converter));

        return $paginator;
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     *
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        /** @var Project $project */
        $project = $this->projectService->find($id);

        if (!$project) {
            return new ApiProblem(404, 'Проект не найден');
        }

        if (!$this->isUserGrantedForEditProject($project)) {
            return new ApiProblem(403, 'Доступ запрещен');
        }

        $arrayData = (array)$data;
        $status    = isset($arrayData['status']) ? $arrayData['status'] : false;

        try {
            $result = $this->projectService->changeStatus($project, $status);
        } catch (\Throwable $exception) {
            $this->logger->logOnThrowable($exception);
            $result = null;
        }

        if (!$result) {
            return new ApiProblem(500, 'Не удалось обновить информацию о проекте');
        }

        return $this->converter->filter($result);
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     *
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        $project = $this->projectService->find($id);

        if (!$project) {
            return new ApiProblem(404, 'Проект не найден');
        }

        if (!$this->isUserGrantedForEditProject($project)) {
            return new ApiProblem(403, 'Доступ запрещен');
        }

        /** @var EmployeeEditForm $projectForm */
        $projectForm = $this->formContainer->get(ProjectForm::class);
        $projectForm->setData((array)$data);

        if (!$projectForm->isValid()) {
            return new ValidationApiProblem($projectForm->getMessages());
        }
        /** @var ProjectEditTo $projectEditTo */
        $projectEditTo = $projectForm->getObject();

        try {
            $result = $this->projectService->update($project, $projectEditTo, $this->user);
        } catch (\Throwable $exception) {
            $this->logger->logOnThrowable($exception);
            $result = null;
        }

        if (!$result) {
            return new ApiProblem(500, 'Не удалось обновить информацию о проекте');
        }

        return $this->converter->filter($result);
    }

    /**
     * @param ResourceEvent $event
     *
     * @return mixed
     */
    public function dispatch(ResourceEvent $event)
    {
        if (!$this->userService->hasAuthenticatedUser()) {
            return new ApiProblem(403, 'Доступ запрещен');
        }

        $this->user = $this->userService->getAuthenticatedUser();

        return parent::dispatch($event);
    }

    /**
     * @param Project $project
     *
     * @return bool
     */
    private function isUserGrantedForEditProject(Project $project)
    {
        if ($this->user->isAdmin()) {
            return true;
        }

        $employee = $this->user->getEmployee();

        if ($project->getController() && $employee->getId() == $project->getController()->getId()) {
            return true;
        }

        if ($project->getProjectAttendeeByEmployee($employee)) {
            return true;
        }

        if ($project->hasProgram()) {
            $programController = $project->getProgram()->getController();

            if ($programController && $programController->getId() == $employee->getId()) {
                return true;
            }
        }

        return false;
    }
}