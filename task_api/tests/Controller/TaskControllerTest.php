<?php
/**
 * Created by PhpStorm.
 * User: volkan
 * Date: 2019-03-16
 * Time: 11:11
 */

namespace App\Tests\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class TaskControllerTest extends WebTestCase
{
    const PREFIX = '/api/v1';

    const POST_PATH = '/tasks';
    const DELETE_PATH = '/tasks/1';
    const PUT_PATH = '/tasks/1';
    const GET_PATH = '/tasks/1';

    public function testGetTask(): void
    {
        $client = static::createClient([], []);
        $client->request('GET',
            self::PREFIX.
            self::GET_PATH
        );

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $task = $client->getContainer()->get('doctrine')->getRepository(Task::class)->find(1);
        $this->assertNotNull($task);
    }

    public function testCreateTask(): void
    {
        $client = static::createClient([], []);
        $dt = new \DateTime();
        $taskName = 'New task name '.mt_rand();
        $taskDesc = $this->generateRandomString(255);
        $taskDueDate = $dt->format('Y-m-d H:i:s');
        $assignedTo = $this->generateRandomString(24);

        $data = [
            'name' => $taskName,
            'description' => $taskDesc,
            'dueDate' => $taskDueDate,
            'user' => $assignedTo
        ];

        $client->request(
            'POST',
            self::PREFIX . self::POST_PATH,
            $data
        );

        $content = $client->getResponse()->getContent();
        $stdClass = json_decode($content);
        $taskId = $stdClass->id;

        $this->assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        /** @var Task $task */
        $task = $client->getContainer()->get('doctrine')->getRepository(Task::class)->find($taskId);
        $this->assertSame($taskName, $task->getName());
    }

    public function testUpdateTask(): void
    {
        $newTaskName = 'new name '.mt_rand();

        $client = static::createClient([], []);

        $data = [
            'name' => $newTaskName,
        ];

        $client->request(
            'PUT',
            self::PREFIX . self::PUT_PATH,
            $data
        );

        $this->assertSame(Response::HTTP_ACCEPTED, $client->getResponse()->getStatusCode());

        /** @var Task $task */
        $task = $client->getContainer()->get('doctrine')->getRepository(Task::class)->find(1);
        $this->assertSame($newTaskName, $task->getName());
    }

    public function testDeleteTask(): void
    {
        $client = static::createClient([], []);
        $client->request('DELETE',
            self::PREFIX.
            self::DELETE_PATH
        );

        $this->assertSame(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        $task = $client->getContainer()->get('doctrine')->getRepository(Task::class)->find(1);
        $this->assertNull($task);
    }

    private function generateRandomString(int $length): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return mb_substr(str_shuffle(str_repeat($chars, ceil($length / mb_strlen($chars)))), 1, $length);
    }
}