<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('code')
            ->add('startDateDate', TextType::class, array(
                "label" => "Start date",
                "attr" => array(
                    "class" => "datepicker"
                ),
                "mapped" => false,
                "required" => false
            ))
            ->add('startDateTime', TextType::class, array(
                "label" => "Start time",
                "attr" => array(
                    "class" => "timepicker"
                ),
                "mapped" => false,
                "required" => false
            ))
            ->add('endDateDate', TextType::class, array(
                "label" => "End date",
                "attr" => array(
                    "class" => "datepicker"
                ),
                "mapped" => false,
                "required" => false
            ))
            ->add('endDateTime', TextType::class, array(
                "label" => "End time",
                "attr" => array(
                    "class" => "timepicker"
                ),
                "mapped" => false,
                "required" => false
            ))
            ->add('offerType')
            ->add('count')
            ->add('level')
            ->add('description');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Offer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_offer';
    }


}
