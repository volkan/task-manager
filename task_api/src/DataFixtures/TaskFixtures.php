<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Task;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getTaskData() as [$name, $description, $dueDate, $user]) {
            $task = new Task();
            $task->setName($name);
            $task->setDescription($description);
            $task->setDueDate($dueDate);
            $task->setAssignedTo($user);            

            $manager->persist($task);
        }

        $manager->flush();
    }

    private function getTaskData(): array
    {
        return [
            ['lorem ip', 'lorem dips lorsadasd ', new \DateTime(), 'user1'],
            ['leks do', 'loere  losd sdsmd sdsd', new \DateTime(), 'user2', ],
            ['dorem asda ', 'lorem ipsudyed lor', new \DateTime(), 'user3', ],
        ];
    }
}
