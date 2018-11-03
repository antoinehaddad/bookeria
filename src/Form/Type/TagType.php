<?php
namespace App\Form\Type;

use App\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\TagsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;

class TagType extends AbstractType {

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(ObjectManager $manager, TagRepository $tagRepository)
    {
        $this->manager = $manager;
        $this->tagRepository = $tagRepository;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new TagsTransformer($this->manager, $this->tagRepository), true);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('attr', [
            'class' => 'tag-input'
        ]);
        $resolver->setDefault('required', false);
    }

    public function getParent (): string {
        return TextType::class;
    }

}