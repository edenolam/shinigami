<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('name')
			->add('title')
			->add('image', FileType::class, array(
				'label' => 'Image (jpg file)',
                'attr' => array(
                    "class" => "btn"
                )
			))
			->add('theme', ChoiceType::class, array(
				'choices' => array(
					'Red' => 'red',
					'Blue' => 'blue',
					'Green' => 'green',
					'Yellow' => 'yellow'
				),
				'attr' => array(
					'class' => 'browser-default'
				)
			))
			->add('content', TextareaType::class, array(
            ))
		;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Newsletter'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_newsletter';
    }


}
