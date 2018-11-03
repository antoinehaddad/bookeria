<?php
namespace App\Command;

use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LibraryCommand extends Command
{

    private $bookRepository;
    private $authorRepository;
    public function __construct(BookRepository $bookRepository, AuthorRepository $authorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:library')
        ->setDescription('List authors and books.')
        ->setHelp('This command allows you to list authors and books...');
        $this->addOption('display', 'd',  InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "Display authors and/or books ",  ['authors', 'books']);  
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $displayOption = $input->getOption('display');
        if(in_array("books", $displayOption)){
            $books = $this->bookRepository->findAll();
            $titles = array_map(function ($object) { return $object->getTitle(); }, $books);
            $titlesLengths = array_map('strlen', $titles);
            $titleLength = max($titlesLengths)+10;
            $output->writeln(str_repeat(" ", 80));
            $output->writeln(str_repeat("-", 80));
            $output->writeln($this->strFixed("Title", $titleLength).$this->strFixed("ISBN").$this->strFixed("Author"));  
            $output->writeln(str_repeat("-", 80));
            foreach($books as $book){
                $output->writeln($this->strFixed($book->getTitle(), $titleLength).$this->strFixed($book->getIsbn()).$this->strFixed($book->GetAuthor()));    
            }
            $output->writeln(str_repeat(" ", 80));
        }
        
        if(in_array("authors", $displayOption)){
            $authors = $this->authorRepository->findAll();
            $output->writeln(str_repeat("-", 80));
            $output->writeln($this->strFixed("Author", 30).$this->strFixed("Nationality"));  
            $output->writeln(str_repeat("-", 80));
            foreach($authors as $author){
                $countryName= \Symfony\Component\Intl\Intl::getRegionBundle()->getCountryName($author->getNationality());
                $output->writeln($this->strFixed($author, 30).$this->strFixed($countryName));    
            }
        }
        $output->writeln(str_repeat(" ", 80));
    }

    private function strFixed($input, $length=20){
        return str_pad($input,  $length, " ", STR_PAD_RIGHT);
    }
}