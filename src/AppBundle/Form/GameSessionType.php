<?php

namespace AppBundle\Form;

use AppBundle\Entity\Center;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameSessionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('center', EntityType::class, array(
                "class" => Center::class,
                "choice_label" => "adress",
                "attr" => array(
                    "class" => "browser-default",
                    "name" => "select-center"
                )
            ))
            ->add('date', TextType::class, array(
                'attr' => array(
                    "class" => 'datepicker'
                ),
                "mapped" => false
            ))
            ->add('time', TimeType::class, array(
                "widget" => "single_text",
                'attr' => array(
                    "class" => 'timepicker'
                ),
                "mapped" => false
            ))
            ->add('numberPlayers', NumberType::class, array(
                "label" => "Number of players",
                "mapped" => false,
            ))
            ->add('gameScores', CollectionType::class, array(
                "entry_type" => GameScoreType::class,
                "allow_add" => true,
                "entry_options" => array(
                    "label" => "Player __name__"
                ),
                "prototype" => true,
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\GameSession'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_gamesession';
    }


}
