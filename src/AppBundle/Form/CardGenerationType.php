<?php

namespace AppBundle\Form;

use AppBundle\Entity\Center;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardGenerationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('center', EntityType::class, array(
                "class" => Center::class,
                "choice_label" => function($center){
                    return $center->getCode()." - ".$center->getAdress();
                },
                "choice_value" => "code",
                "attr" => array(
                    "class" => 'browser-default'
                )
            ))
            ->add('number', TextType::class, array(
                "mapped" => false,
                "attr" => array(
                    "maxlength" => 5
                ),
            ))
            ->add('modulo', TextType::class, array(
                "mapped" => false,
    ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Card'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_card';
    }


}
