<?php

namespace CustomBundle\Form\Type;

use CustomBundle\Entity\Lyrics;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LyricsType extends AbstractType {

    public function buildForm(FormBuilderInterface $formBuilderInterface, array $options)
    {
        $formBuilderInterface->add('content', TextareaType::class)
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lyrics::class
        ]);
    }

}