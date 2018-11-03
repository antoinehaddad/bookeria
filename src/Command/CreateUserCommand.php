<?php
namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{

    private $passwordEncoder;
    private $manager;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
        parent::__construct();
    }
    protected function configure()
    {
        $this->setName('app:create-user')
        ->setDescription('Creates a new user.')
        ->setHelp('This command allows you to create a user...');

        $this->addArgument('email', InputArgument::REQUIRED, 'The email of the user.');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $user = new User();
        $user->setEmail($input->getArgument('email'));
       
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $input->getArgument('password')
        ));
        $this->manager->persist($user);

        $this->manager->flush();    
        $output->writeln('User successfully generated!');
    }
}