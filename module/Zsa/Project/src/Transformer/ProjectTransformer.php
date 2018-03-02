<?php

namespace Zsa\Project\Transformer;

use Axmit\UserCore\Dto\UserCollection;
use Axmit\UserCore\Transformer\UserTransformer;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zsa\Pip\Dto\PipRelation\PipRelationCollection;
use Zsa\Pip\Entity\PipRelation;
use Zsa\Pip\Transformer\PipTransformer;
use Zsa\Program\Transformer\ProgramTransformer;
use Zsa\Project\Dto\Project\ProjectEditTo;
use Zsa\Project\Dto\Project\ProjectTo;
use Zsa\Project\Dto\Project\ProjectViewTo;
use Zsa\Project\Dto\Project\ProjectWebViewTo;
use Zsa\Project\Entity\Project;
use Zsa\Project\Entity\ProjectAttendee;

/**
 * Class ProjectTransformer
 *
 * @package Zsa\Project\Transformer
 */
class ProjectTransformer
{
    /**
     * @param Project            $project
     * @param ProjectViewTo|null $projectViewTo
     *
     * @return ProjectTo|ProjectEditTo|ProjectViewTo
     */
    public static function toProjectViewTo(Project $project, ProjectViewTo $projectViewTo = null)
    {
        if (!$projectViewTo) {
            $projectViewTo = new ProjectViewTo();
        }

        if ($project->hasPips()) {

            $pips = $project->getPips()->map(
                function (PipRelation $pip) {
                    return PipTransformer::toPipRelationTo($pip);
                }
            );

            $pipToCollection = new PipRelationCollection(new ArrayAdapter($pips->toArray()));

            $projectViewTo->setPipCollection($pipToCollection);
        }

        return self::toProjectEditTo($project, $projectViewTo);
    }

    /**
     * @param Project        $project
     * @param ProjectTo|null $projectTo
     *
     * @return ProjectTo|ProjectEditTo
     */
    public static function toProjectTo(Project $project, ProjectTo $projectTo = null)
    {
        if (!$projectTo) {
            $projectTo = new ProjectTo();
        }

        $projectTo->setId($project->getId())
                  ->setTitle($project->getTitle())
                  ->setCode($project->getCode())
                  ->setStatus($project->getStatus())
                  ->setClosed($project->isClosed());

        if ($project->getController()) {
            $projectTo->setController(UserTransformer::toUserTo($project->getController()));
        }


        return $projectTo;
    }

    /**
     * @param Project            $project
     * @param ProjectEditTo|null $projectEditTo
     *
     * @return ProjectTo
     */
    public static function toProjectEditTo(Project $project, ProjectEditTo $projectEditTo = null)
    {
        if (!$projectEditTo) {
            $projectEditTo = new ProjectEditTo();
        }

        $projectEditTo->setDescription($project->getDescription());

        if ($project->getProgram()) {
            $projectEditTo->setProgram(ProgramTransformer::toProgramTo($project->getProgram()));
        }

        if ($project->hasProjectAttendees()) {
            $attendees = $project->getProjectAttendees()->map(
                function (ProjectAttendee $attendee) {
                    return UserTransformer::toUserTo($attendee->getUser());
                }
            );

            $projectEditTo->setAttendees(new UserCollection(new ArrayAdapter($attendees->toArray())));
        }

        return self::toProjectTo($project, $projectEditTo);
    }

    /**
     * @param Project               $project
     * @param ProjectWebViewTo|null $projectWebViewTo
     *
     * @return ProjectEditTo|ProjectTo|ProjectViewTo|ProjectWebViewTo
     */
    public static function toProjectWebViewTo(Project $project, ProjectWebViewTo $projectWebViewTo = null)
    {
        if (!$projectWebViewTo) {
            $projectWebViewTo = new ProjectWebViewTo();
        }

        $projectWebViewTo->setCreatedOn($project->getCreatedOn());

        if ($project->getUpdatedOn()) {
            $projectWebViewTo->setUpdatedOn($project->getUpdatedOn());
        }

        if ($project->getCreatedBy()) {
            $projectWebViewTo->setCreatedBy($project->getCreatedBy()->getId());
        }

        if ($project->getUpdatedBy()) {
            $projectWebViewTo->setUpdatedBy($project->getUpdatedBy()->getId());
        }

        return self::toProjectViewTo($project, $projectWebViewTo);
    }
}
