<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\Type\TagType;
use App\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttributes(array(
                'novalidate'=>'novalidate',
                ))
            ->add('title', TextType::class, ['error_bubbling' => false])
            ->add('author', EntityType::class, ['class'=>Author::class, 'placeholder' => 'Veuillez choisir un auteur'])
            ->add('coverPicture', MediaType::class, ["label" => "Photo de couverture"])
            ->add('isbn')
            ->add('tags', TagType::class)
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class
        ]);
    }
}
