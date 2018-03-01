<?php

namespace Api\Program\V1\Rest\Program;

use Axmit\Monolog\Logger\AppLogger\AppLogger;
use Axmit\Util\ApiProblem\ValidationApiProblem;
use Axmit\Util\Filter\CollectionFilter;
use Axmit\Util\Response\SuccessResponse;
use Epos\UserCore\Entity\UserInterface;
use Epos\UserCore\Service\UserService;
use Zsa\Program\Dto\Program\ProgramEditTo;
use Zsa\Program\Entity\Program;
use Zsa\Program\Form\ProgramEditForm;
use Zsa\Program\Service\ProgramService;
use Interop\Container\ContainerInterface;
use Zend\Filter\FilterInterface;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

/**
 * Class ProgramResource
 *
 * @package Api\Program\V1\Rest\Program
 */
class ProgramResource extends AbstractResourceListener
{
    /**
     * @var ProgramService
     */
    protected $programService;

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
     * @var UserInterface
     */
    protected $user;

    /**
     * ProgramResource constructor.
     *
     * @param ProgramService     $programService
     * @param UserService        $userService
     * @param FilterInterface    $converter
     * @param ContainerInterface $formContainer
     * @param AppLogger          $logger
     */
    public function __construct(
        ProgramService $programService,
        UserService $userService,
        FilterInterface $converter,
        ContainerInterface $formContainer,
        AppLogger $logger
    ) {
        $this->programService = $programService;
        $this->userService    = $userService;
        $this->converter      = $converter;
        $this->formContainer  = $formContainer;
        $this->logger         = $logger;
    }

    /**
     * @param mixed $data
     *
     * @return ApiProblem
     */
    public function create($data)
    {
        /** @var ProgramEditForm $programEditForm */
        $programEditForm = $this->formContainer->get(ProgramEditForm::class);
        $programEditForm->setData((array)$data);

        if (!$programEditForm->isValid()) {
            return new ValidationApiProblem($programEditForm->getMessages());
        }

        /** @var ProgramEditTo $programEditTo */
        $programEditTo = $programEditForm->getObject();

        try {
            $result = $this->programService->create($programEditTo, $this->user);
        } catch (\Throwable $exception) {
            $this->logger->logOnThrowable($exception);
            $result = null;
        }

        if (!$result) {
            return new ApiProblem(500, 'Возникла ошибка при создании программы. Повторите попытку позже');
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
        $program = $this->programService->find($id);

        if (!$program) {
            return new ApiProblem(404, 'Программа не найдена');
        }

        $queryParams = $this->event->getParam('fromQuery');
        $removePips = isset($queryParams['removePips']) ? (bool)$queryParams['removePips'] : false;

        try {
            $this->programService->archive($program, $removePips);
        } catch (\Throwable $exception) {
            $this->logger->logOnThrowable($exception);

            return new ApiProblem(500, 'Не удалось удалить программу');
        }

        return SuccessResponse::withMessages(['Программа успешно удалена']);
    }

    /**
     * @param mixed $id
     *
     * @return mixed|ApiProblem
     */
    public function fetch($id)
    {
        $program = $this->programService->find($id);

        if (!$program) {
            return new ApiProblem(404, 'Программа не найдена');
        }

        return $this->converter->filter($program);
    }

    /**
     * @param array $params
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($params = [])
    {
        $paginator = $this->programService->fetchAll((array)$params);
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
        /** @var Program $program */
        $program = $this->programService->find($id);

        if (!$program) {
            return new ApiProblem(404, 'Программа не найдеа');
        }

        $arrayData = (array)$data;
        $status    = isset($arrayData['status']) ? $arrayData['status'] : false;

        try {
            $result = $this->programService->changeStatus($program, $status);
        } catch (\Throwable $exception) {
            $this->logger->logOnThrowable($exception);
            $result = null;
        }

        if (!$result) {
            return new ApiProblem(500, 'Не удалось обновить информацию о программе');
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
        $program = $this->programService->find($id);

        if (!$program) {
            return new ApiProblem(404, 'Программа не найдена');
        }

        /** @var ProgramEditForm $programEditForm */
        $programEditForm = $this->formContainer->get(ProgramEditForm::class);
        $programEditForm->setData((array)$data);

        if (!$programEditForm->isValid()) {
            return new ValidationApiProblem($programEditForm->getMessages());
        }
        /** @var ProgramEditTo $programEditTo */
        $programEditTo = $programEditForm->getObject();

        try {
            $result = $this->programService->update($program, $programEditTo, $this->user);
        } catch (\Throwable $exception) {
            $this->logger->logOnThrowable($exception);
            $result = null;
        }

        if (!$result) {
            return new ApiProblem(500, 'Не удалось обновить информацию о программе');
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
}