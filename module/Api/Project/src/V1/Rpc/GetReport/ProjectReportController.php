<?php

namespace Api\Project\V1\Rpc\GetReport;

use Epos\UserCore\Service\UserService;
use Zsa\Project\Entity\Project;
use Zsa\Reporting\Project\Processor\ProjectSpreadsheetProcessor;
use Zsa\Reporting\Project\Service\ProjectReportService;
use Zend\Http\Headers;
use Zend\Http\Response\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class ProjectReportController
 *
 * @package Api\Project\V1\Rpc\GetReport
 */
class ProjectReportController extends AbstractActionController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var ProjectReportService
     */
    protected $projectService;

    /**
     * @var ProjectSpreadsheetProcessor
     */
    protected $processor;

    /**
     * @var Project
     */
    protected $project;

    /**
     * ProjectReportController constructor.
     *
     * @param UserService                 $userService
     * @param ProjectReportService        $projectService
     * @param ProjectSpreadsheetProcessor $processor
     */
    public function __construct(
        UserService $userService,
        ProjectReportService $projectService,
        ProjectSpreadsheetProcessor $processor
    ) {
        $this->userService    = $userService;
        $this->projectService = $projectService;
        $this->processor      = $processor;
    }

    /**
     * @return Stream
     */
    public function projectReportAction()
    {
        $response = new Stream();
        $headers  = new Headers();
        $finfo    = new \finfo(FILEINFO_MIME);

        $filename = $this->projectService->generateReport($this->project);

        $response->setStream(fopen($filename, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($filename));
        $headers->addHeaders(
            [
                'Content-Disposition' => 'attachment; filename="' . basename($filename) .'"',
                'Content-Type'   => $finfo->file($filename),
                'Content-Length' => filesize($filename),
            ]
        );
        $response->setHeaders($headers);

        return $response;
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

        $this->project = $projectId > 0 ? $project = $this->projectService->fetchProject($projectId)
            : null;

        if (!$this->project) {
            return new ApiProblemResponse(new ApiProblem(404, 'Проект не найден'));
        }

        return parent::onDispatch($e);
    }
}
