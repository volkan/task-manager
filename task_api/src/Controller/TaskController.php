<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\Version;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\TaskService;

/**
 * @Version("v1")
 * @Route("/api/v1") 
 */
class TaskController extends AbstractFOSRestController
{
    /**
     * @Route("/tasks", name="get_task_list", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the task list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Task::class))
     *     )
     * )
     * @param TaskService $taskService
     * @return JsonResponse
     */
    public function listTaskAction(TaskService $taskService): JsonResponse
    {
        return $this->json($taskService->getAllTasks());
    }

    /**
     * @Route("/tasks/{id}", name="get_task", methods={"GET"})
     * @param int $id
     * @param TaskService $taskService
     * @return JsonResponse
     * @throws \App\Exception\TaskNotFoundException;
     * @SWG\Response(
     *     response=200,
     *     description="",
     *     @SWG\Schema(ref=@Model(type=Task::class))
     * )
     */
    public function getTaskAction(int $id, TaskService $taskService): JsonResponse
    {
        return $this->json($taskService->getTask($id));
    }

    /**
     * @Route("/tasks", name="create_task", methods={"POST", "OPTIONS"})
     * @SWG\Response(
     *     response=201,
     *     description="",
     *     @SWG\Schema(ref=@Model(type=Task::class))
     * )
     * @SWG\Parameter(
     *     name="request",
     *     in="body",
     *     @SWG\Schema(ref=@Model(type=Task::class, groups={"upsert_dto"}))
     * )
     * @param Request $request
     * @param TaskService $taskService
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createTaskAction(Request $request, TaskService $taskService): JsonResponse
    {
        $task = $taskService->addTask(
            $request->get('name'),
            $request->get('description'),
            $request->get('dueDate'),
            $request->get('user')
        );

        return $this->json($task, JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/tasks/{id}", name="update_task", methods={"PUT", "OPTIONS"})
     * @SWG\Response(
     *     response=202,
     *     description="",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Task::class))
     *     )
     * )
     * @SWG\Parameter(
     *     name="request",
     *     in="body",
     *     @SWG\Schema(ref=@Model(type=Task::class, groups={"upsert_dto"}))
     * )
     * @param int $id
     * @param Request $request
     * @param TaskService $taskService
     * @return JsonResponse
     * @throws \App\Exception\TaskNotFoundException;
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateTaskAction(int $id, Request $request, TaskService $taskService): JsonResponse
    {
        $task = $taskService->updateTask(
            $id,
            $request->get('name'),
            $request->get('description'),
            $request->get('dueDate'),
            $request->get('user')
        );

        return $this->json($task, JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * @Route("/tasks/{id}", name="delete_task", methods={"DELETE", "OPTIONS"})
     * @param int $id
     * @param TaskService $taskService
     * @return JsonResponse
     * @throws \App\Exception\TaskNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteTaskAction(int $id, TaskService $taskService): JsonResponse
    {
        $taskService->deleteTask($id);

        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
