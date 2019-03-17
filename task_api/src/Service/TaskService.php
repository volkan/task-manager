<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Exception\TaskNotFoundException;

final class TaskService
{
    /**
     * @var EntityManagerInterface
     */
    private $_entityManager;

    /**
     * @var TaskRepository
     */
    private $_taskRepository;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->_taskRepository = $taskRepository;
        $this->_entityManager = $entityManager;
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $dueDate
     * @param string $assignedTo
     * @return Task
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addTask(string $name, string $description, string $dueDate, string $assignedTo): Task
    {
        $task = new Task();
        $task->setName($name);
        $task->setDescription($description);
        $task->setDueDate(new \DateTime($dueDate));
        $task->setAssignedTo($assignedTo);

        $this->_taskRepository->save($task);

        return $task;
    }

    /**
     * @param int $taskId
     * @param string $name
     * @param string $description
     * @param string $dueDate
     * @param string $assignedTo
     * @return Task
     * @throws TaskNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateTask(int $taskId, ?string $name, ?string $description, ?string $dueDate, ?string $assignedTo): ?Task
    {
        $task = $this->getTask($taskId);

        $task->setName($name);
        $task->setDescription($description);
        $task->setDueDate(new \DateTime($dueDate));
        $task->setAssignedTo($assignedTo);

        $this->_taskRepository->save($task);

        return $task;
    }

    /**
     * @return Task[]
     */
    public function getAllTasks(): ?array
    {
        return $this->_taskRepository->findAll();
    }

    /**
     * @param int $taskId
     * @return Task
     * @throws TaskNotFoundException
     */
    public function getTask(int $taskId): ?Task
    {
        $task = $this->_taskRepository->find($taskId);
        if (!$task) {
            throw new TaskNotFoundException('This Task Doesn\'t exist');
        }

        return $task;
    }

    /**
     * @param int $taskId
     * @throws TaskNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteTask(int $taskId): void
    {
        $task = $this->getTask($taskId);

        $this->_taskRepository->delete($task);
    }
}