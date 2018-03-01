<?php

namespace Zsa\Project\Dao;

use Axmit\Dao\Criterion\Filter;
use Zsa\Project\Entity\ProjectAttendee;

/**
 * Interface ProjectAttendeeDaoInterface
 *
 * @package Zsa\Project\Dao
 */
interface ProjectAttendeeDaoInterface
{
    /**
     * @param ProjectAttendee $projectAttendee
     *
     * @return void
     */
    public function save(ProjectAttendee $projectAttendee);

    /**
     * @param ProjectAttendee $projectAttendee
     *
     * @return void
     */
    public function remove(ProjectAttendee $projectAttendee);

    /**
     * @param $id
     *
     * @return ProjectAttendee|null|object
     */
    public function find($id);

    /**
     * @param Filter $filter
     *
     * @return ProjectAttendee
     */
    public function findOneByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return ProjectAttendee[]
     */
    public function findByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return \Zend\Paginator\Paginator
     */
    public function findPaginatedByFilter(Filter $filter);
}