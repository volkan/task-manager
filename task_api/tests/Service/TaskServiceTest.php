<?php
/**
 * User: volkan
 * Date: 2019-03-16
 * Time: 13:54
 */

namespace App\Tests\Service;

use App\Exception\TaskNotFoundException;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $_entityManager;

    /**
     * @var TaskRepository
     */
    private $_taskRepository;

    protected function setUp()
    {
        $taskRepository = $this->createMock(TaskRepository::class);
        $taskRepository->expects($this->any())->method('save')->willReturn(Task::class);
        $this->_taskRepository = $taskRepository;

        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_entityManager = $entityManager;
    }


    public function testItReturnNotFoundExceptionGetTask(): void
    {
        $taskService = new TaskService($this->_taskRepository, $this->_entityManager);

        $this->expectException(TaskNotFoundException::class);

        $taskService->getTask(1111);
    }

    public function testGetTask(): void
    {
        $taskRepository = $this->createMock(TaskRepository::class);
        $taskObject = new Task();
        $taskObject->setId(1);
        $taskRepository->expects($this->any())->method('find')->willReturn($taskObject);

        $taskService = new TaskService($taskRepository, $this->_entityManager);

        $task = $taskService->getTask(1111);
        $this->assertInstanceOf(Task::class, $task);
    }

    public function testGetAllTask(): void
    {
        $taskRepository = $this->createMock(TaskRepository::class);
        $taskObject = new Task();
        $taskObject->setId(1);
        $taskRepository->expects($this->any())->method('findAll')->willReturn([$taskObject]);

        $taskService = new TaskService($taskRepository, $this->_entityManager);

        $tasks = $taskService->getAllTasks();
        $this->assertInternalType('array', $tasks);
        $this->assertInstanceOf(Task::class, $tasks[0]);
    }

    public function testUpdateTask(): void
    {
        $taskRepository = $this->createMock(TaskRepository::class);
        $taskObject = new Task();
        $taskObject->setId(1);
        $taskRepository->expects($this->any())->method('find')->willReturn($taskObject);

        $taskService = new TaskService($taskRepository, $this->_entityManager);

        $task = $taskService->updateTask(
            1,
            'name',
            'desc',
            '2019-03-16 11:08:04',
            'assignedTo'
        );

        $this->assertInstanceOf(Task::class, $task);
        $this->assertSame(1, $task->getId());
    }

    public function testAddTask(): void
    {
        $taskService = new TaskService($this->_taskRepository, $this->_entityManager);

        $task = $taskService->addTask(
            'name',
            'desc',
            '2019-03-16 11:08:04',
            'assignedTo'
        );

        $this->assertInstanceOf(Task::class, $task);
    }

    public function testDeleteTask(): void
    {
        $taskRepository = $this->createMock(TaskRepository::class);
        $taskObject = new Task();
        $taskObject->setId(1);
        $taskRepository->expects($this->any())->method('find')->willReturn($taskObject);

        $taskService = new TaskService($taskRepository, $this->_entityManager);

        $result = $taskService->deleteTask(1);
        $this->assertNull($result);
    }
}