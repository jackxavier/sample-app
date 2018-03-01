<?php

namespace Zsa\Program\Transformer;

use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Transformer\UserTransformer;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zsa\Pip\Dto\PipRelation\PipRelationCollection;
use Zsa\Pip\Entity\PipRelation;
use Zsa\Pip\Transformer\PipTransformer;
use Zsa\Program\Dto\Program\ProgramEditTo;
use Zsa\Program\Dto\Program\ProgramTo;
use Zsa\Program\Dto\Program\ProgramViewTo;
use Zsa\Program\Entity\Program;
use Zsa\Project\Dto\Project\ProjectCollection;
use Zsa\Project\Entity\Project;
use Zsa\Project\Transformer\ProjectTransformer;

/**
 * Class ProgramTransformer
 *
 * @package Zsa\Program\Transformer
 */
class ProgramTransformer
{
    /**
     * @param User               $user
     * @param Program            $program
     * @param ProgramViewTo|null $programViewTo
     *
     * @return ProgramTo
     */
    public static function toProgramViewTo(User $user, Program $program, ProgramViewTo $programViewTo = null)
    {
        if (!$programViewTo) {
            $programViewTo = new ProgramViewTo();
        }

        $programViewTo->setCreatedOn($program->getCreatedOn())
                      ->setUpdatedOn($program->getUpdatedOn());

        if ($program->getCreatedBy()) {
            $programViewTo->setCreatedBy($program->getCreatedBy()->getId());
        }

        if ($program->getUpdatedBy()) {
            $programViewTo->setUpdatedBy($program->getUpdatedBy()->getId());
        }

        if ($program->hasPips()) {

            $pips = $program->getPips()->map(
                function (PipRelation $pip) {
                    return PipTransformer::toPipRelationTo($pip);
                }
            );

            $pipToCollection = new PipRelationCollection(new ArrayAdapter($pips->toArray()));

            $programViewTo->setPipCollection($pipToCollection);
        }

        return self::toProgramEditTo($user, $program, $programViewTo);
    }

    /**
     * @param Program        $program
     * @param ProgramTo|null $programTo
     *
     * @return ProgramTo
     */
    public static function toProgramTo(Program $program, ProgramTo $programTo = null)
    {
        if (!$programTo) {
            $programTo = new ProgramTo();
        }

        $programTo->setId($program->getId())
                  ->setTitle($program->getTitle())
                  ->setDescription($program->getDescription())
                  ->setStatus($program->getStatus())
                  ->setClosed($program->isClosed());

        if ($program->getController()) {
            $programTo->setController(UserTransformer::toUserTo($program->getController()));
        }

        return $programTo;
    }

    /**
     * @param User               $user
     * @param Program            $program
     * @param ProgramEditTo|null $programEditTo
     *
     * @return ProgramTo
     */
    public static function toProgramEditTo(User $user, Program $program, ProgramEditTo $programEditTo = null)
    {
        if (!$programEditTo) {
            $programEditTo = new ProgramEditTo();
        }

        if ($program->hasProjects()) {
            $projectToCollection = $program->getUserProjects($user)->map(
                function (Project $project) {
                    return ProjectTransformer::toProjectTo($project);
                }
            );

            $programEditTo->setProjects(new ProjectCollection(new ArrayAdapter($projectToCollection->toArray())));
        }

        return self::toProgramTo($program, $programEditTo);
    }
}