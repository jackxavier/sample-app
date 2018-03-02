<?php

namespace Zsa\Project\Service;

use Axmit\Dao\Criterion\Filter;
use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Entity\UserInterface;
use Zend\Paginator\Paginator;
use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Dto\PipRelation\PipRelationTo;
use Zsa\Pip\Service\PipAttachServiceInterface;
use Zsa\Pip\Service\PipManagementService;
use Zsa\Project\Dao\Criterion\ProjectSpecifications;
use Zsa\Project\Dao\Hydrator\ProjectFilteringHydrator;
use Zsa\Project\Dao\ProjectAttendeeDaoInterface;
use Zsa\Project\Dao\ProjectDaoInterface;
use Zsa\Project\Dto\Project\ProjectEditTo;
use Zsa\Project\Entity\Project;
use Zsa\Project\Entity\ProjectAttendee;
use Zsa\Util\Enum\WorkflowStatusEnum;

/**
 * Class ProjectService
 *
 * @package Zsa\Project\Service
 */
class ProjectService implements PipAttachServiceInterface
{
    /**
     * @var ProjectDaoInterface
     */
    protected $projectDao;

    /**
     * @var ProjectAttendeeDaoInterface
     */
    protected $projectAttendeeDao;

    /**
     * @var ProjectFilteringHydrator
     */
    protected $projectFilteringHydrator;

    /**
     * @var PipManagementService
     */
    protected $pipManagementService;

    /**
     * ProjectService constructor.
     *
     * @param ProjectDaoInterface         $projectDao
     * @param ProjectAttendeeDaoInterface $projectAttendeeDao
     * @param ProjectFilteringHydrator    $projectFilteringHydrator
     * @param PipManagementService        $pipManagementService
     */
    public function __construct(
        ProjectDaoInterface $projectDao,
        ProjectAttendeeDaoInterface $projectAttendeeDao,
        ProjectFilteringHydrator $projectFilteringHydrator,
        PipManagementService $pipManagementService
    ) {
        $this->projectDao               = $projectDao;
        $this->projectAttendeeDao       = $projectAttendeeDao;
        $this->projectFilteringHydrator = $projectFilteringHydrator;
        $this->pipManagementService     = $pipManagementService;
    }

    /**
     * @param ProjectEditTo      $editTo
     * @param UserInterface|User $user
     *
     * @return Project|null
     */
    public function create(ProjectEditTo $editTo, UserInterface $user)
    {
        $project = new Project();
        $project->setTitle($editTo->getTitle())
                ->setDescription($editTo->getDescription())
                ->setCode($editTo->getCode())
                ->setCreatedBy($user)
                ->setStatus($editTo->getStatus());

        if ($editTo->getProgram()) {
            $project->setProgram($editTo->getProgram());
        }

        $controller = $editTo->getController() ?: $user;
        $project->setController($controller);

        $attendees = $editTo->getAttendees();
        /** @var User $attendee */
        foreach ($attendees as $attendee) {
            $projectAttendee = new ProjectAttendee($attendee);
            $project->addProjectAttendee($projectAttendee);
        }

        $this->projectDao->save($project);

        return $project;
    }

    /**
     * @param $id
     *
     * @return Project
     */
    public function find($id)
    {
        return $this->projectDao->find($id);
    }

    /**
     * @param UserInterface|User $user
     * @param array              $params
     *
     * @return Paginator
     */
    public function fetchAll(UserInterface $user, array $params = [])
    {
        $filter      = new Filter();
        $filterArray = [];

        foreach ($params as $key => $paramName) {
            if (isset($paramName)) {
                $filterArray[$key] = $paramName;
            }
        }

        if (!isset($filterArray['controller']) && !isset($filterArray['user'])) {
            $filter->andConstraint(ProjectSpecifications::PROJECT_ATTENDEE)->value($user->getId());
        }

        $filter->fromArray($filterArray, $this->projectFilteringHydrator);

        return $this->projectDao->findPaginatedByFilter($filter);
    }

    /**
     * @param Project $project
     * @param bool    $removePips
     *
     * @return void
     */
    public function remove(Project $project, $removePips = false)
    {
        if ($removePips) {
            $this->pipManagementService->removeRelatedPips($project);
        } else {
            $this->pipManagementService->changeRelatedPipsStatuses($project);
            $project->getPips()->clear();
        }

        $this->projectDao->remove($project);
    }

    /**
     * @param Project $project
     * @param bool    $removePips
     *
     * @return void
     */
    public function archive(Project $project, $removePips = false)
    {
        if ($removePips) {
            $this->pipManagementService->removeRelatedPips($project);
        } else {
            $this->pipManagementService->changeRelatedPipsStatuses($project);
            $project->getPips()->clear();
        }

        $project->setStatus(WorkflowStatusEnum::VALUE_STATUS_ARCHIVED_STR);

        $this->projectDao->save($project);
    }

    /**
     * @param Project            $project
     * @param ProjectEditTo      $editTo
     * @param UserInterface|User $user
     *
     * @return Project|null
     */
    public function update(Project $project, ProjectEditTo $editTo, UserInterface $user)
    {
        $project->setTitle($editTo->getTitle())
                ->setCode($editTo->getCode())
                ->setDescription($editTo->getDescription())
                ->setUpdatedBy($user)
                ->setUpdatedOn(new \DateTime())
                ->setStatus($editTo->getStatus())
                ->setProgram($editTo->getProgram());

        if ($editTo->getController()) {
            $this->changeController($project, $editTo->getController(), $user);
        }

        $project->updateProjectAttendees($editTo->getAttendees());

        $this->projectDao->save($project);

        return $project;
    }

    /**
     * @param Project $project
     * @param bool    $status
     *
     * @return Project|null
     */
    public function changeStatus(Project $project, $status = false)
    {
        $project->setStatus($status)
                ->setUpdatedOn(new \DateTime());

        $this->projectDao->save($project);

        return $project;
    }

    /**
     * @param PipContainerInterface|Project $project
     * @param PipRelationTo                 $relationTo
     * @param UserInterface                 $user
     *
     * @return bool
     */
    public function attachPip(PipContainerInterface $project, PipRelationTo $relationTo, UserInterface $user)
    {
        $project->assignPip($relationTo);
        $project->setUpdatedBy($user)
                ->setUpdatedOn(new \DateTime());

        $this->projectDao->save($project);

        return true;
    }

    /**
     * @param PipContainerInterface|Project $project
     * @param PipRelationTo                 $relationTo
     * @param UserInterface                 $user
     *
     * @return bool
     */
    public function detachPip(PipContainerInterface $project, PipRelationTo $relationTo, UserInterface $user)
    {
        $project->disassignPip($relationTo);
        $project->setUpdatedBy($user)
                ->setUpdatedOn(new \DateTime());

        $this->projectDao->save($project);

        return true;
    }


    /**
     * @param User $user
     *
     * @return void
     */
    protected function detachAttendee(User $user, User $manager)
    {
        $filter = new Filter();
        $filter->andConstraint('prjuser')->eq($user->getId());

        $projectAttendees = $this->projectAttendeeDao->findByFilter($filter);

        if (empty($projectAttendees)) {
            return;
        }

        /** @var Project $project */
        foreach ($projectAttendees as $projectAttendee) {

            $project = $projectAttendee->getProject();

            if ($project->getStatus() === WorkflowStatusEnum::VALUE_STATUS_ARCHIVED_STR) {
                continue;
            }

            if ($project->isClosed()) {
                continue;
            }

            if ((int)$project->getController()->getId() !== (int)$manager->getId()) {
                $projectAttendee->setUser($manager);

                $this->projectAttendeeDao->save($projectAttendee);
            } else {
                $this->projectAttendeeDao->remove($projectAttendee);
            }
        }

    }

    /**
     * @param Project       $project
     * @param User          $newController
     * @param UserInterface $author
     *
     * @return Project
     */
    public function changeController(Project $project, User $newController, UserInterface $author)
    {
        if ($project->getController()->getId() == $newController->getId()) {
            return $project;
        }

        $project->setController($newController);

        return $project;
    }
}