<?php

declare(strict_types=1);

namespace App\Form\Section;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('machine_name', TextType::class);
        $builder->add('name', TextType::class);
        $builder->add('enabled', CheckboxType::class, ['required' => false]);
        $builder->add('hidden', CheckboxType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => SectionDTO::class]);
    }
}
