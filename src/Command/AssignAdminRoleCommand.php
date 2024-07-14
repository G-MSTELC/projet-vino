<?php
// src/Command/AssignAdminRoleCommand.php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssignAdminRoleCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:assign-admin-role')
            ->setDescription('Assign ROLE_ADMIN to a user.')
            ->addArgument('username', InputArgument::REQUIRED, 'Username of the user to assign ROLE_ADMIN.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $output->writeln('User not found.');

            return Command::FAILURE;
        }

        $roles = $user->getRoles();
        if (!in_array('ROLE_ADMIN', $roles, true)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles($roles);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln(sprintf('ROLE_ADMIN assigned to user %s.', $username));
        } else {
            $output->writeln(sprintf('User %s already has ROLE_ADMIN.', $username));
        }

        return Command::SUCCESS;
    }
}
