<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfilType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
            ->add('username', "Symfony\Component\Form\Extension\Core\Type\TextType")
			->add('email', "Symfony\Component\Form\Extension\Core\Type\EmailType")
			//->add('password', "Symfony\Component\Form\Extension\Core\Type\RepeatedType", array(
			//	'type' => "Symfony\Component\Form\Extension\Core\Type\PasswordType",
			//	'invalid_message' => 'Die Passwörter müssen übereinstimmen!',
			//	'first_options'  => array('label' => 'Passwort'),
			//	'second_options' => array('label' => 'Passwort wiederholen')))
            ->add('prename', "Symfony\Component\Form\Extension\Core\Type\TextType", array(
				'required' => false))
            ->add('surname', "Symfony\Component\Form\Extension\Core\Type\TextType", array(
				'required' => false))
            ->add('street', "Symfony\Component\Form\Extension\Core\Type\TextType", array(
				'required' => false))
            ->add('city', "Symfony\Component\Form\Extension\Core\Type\TextType", array(
				'required' => false))
            ->add('postcode', "Symfony\Component\Form\Extension\Core\Type\TextType", array(
				'required' => false))
            ->add('phone', "Symfony\Component\Form\Extension\Core\Type\TextType", array(
				'required' => false));
	}
	
	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\User',));
	}
}