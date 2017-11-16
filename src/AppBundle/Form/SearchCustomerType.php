<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 16/11/2017
 * Time: 12:30
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCustomerType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
			->add('firstname', TextType::class,  array(
				'required'   => true,
			))
			->add('lastname', TextType::class,  array(
					'required'   => true,
			))
			->add('phone', TextType::class,  array(
					'required'   => true,
			))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array());
	}



}
