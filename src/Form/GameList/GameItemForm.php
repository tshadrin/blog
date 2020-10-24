<?php

declare(strict_types=1);

namespace App\Form\GameList;

use App\Entity\GameList\Format;
use App\Entity\GameList\OS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameItemForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);
        $builder->add('os', ChoiceType::class, ['choices' => OS::getConstants(), 'translation_domain' => false]);
        $builder->add('purchase_date', DateType::class, [
            'input' => 'datetime_immutable',
            'html5' => true,
            'widget' => 'single_text',
        ]);
        $builder->add('cost', NumberType::class);
        $builder->add('notes', TextareaType::class, ['required' => false]);
        $builder->add('exchange_rate', NumberType::class, [
            'data' => array_key_exists('data', $options) ? $options['data']->exchange_rate : 1,
        ]);
        $builder->add('format', ChoiceType::class, ['choices' => Format::getConstants()]);
        $builder->add('owned', CheckboxType::class, ['required' => false,]);
        $builder->add('deleted', CheckboxType::class, ['required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => GameItemDTO::class]);
    }
}
