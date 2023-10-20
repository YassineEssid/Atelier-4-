<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category',ChoiceType::class,['choices'=>['Science'=>'science',  //choiceType pour statique
                                                'Maths'=>'maths',
                                                'History'=>'history'],
                                                'expanded'=>false],//expended list Select  (false) 
                                                ['multiple'=>false]//check box true , radio false
                                                )
                                               
            ->add('publicationDate')
            ->add('published')
            ->add('author',EntityType::class,['class'=>Author::class, //EntityType pour dynamic(database)
                                             'choice_label'=>'username'])//expended,multiple par defaut false
            ->add('Add',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}