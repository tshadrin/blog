<?php
declare(strict_types=1);

namespace App\Form\Blog;

use App\Entity\Blog\Section;
use App\Entity\Blog\Status;
use App\Entity\Blog\Tag;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PostForm extends AbstractType
{
    /** @var TokenStorageInterface  */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);
        $builder->add('teaser', CKEditorType::class,
            [
                'config' => [
                    'image_previewText' => '',
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => [
                        'instance' => 'default',
                        'homeFolder' => $this->tokenStorage->getToken()->getUser()->getId()
                    ],
                ],
            ]
        );
        $builder->get('teaser')->addModelTransformer(new CallbackTransformer(
            function ($value) { return $value; },
            function ($value) {
                if(!is_null($value))
                return strip_tags($value, '<p><strong><em><s><ol><li><ul><a><blockquote><hr><pre><code><img>');
            }
        ));
        $builder->add('body', CKEditorType::class,
            [
                'config' => [
                    'image_previewText' => '',
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => [
                        'instance' => 'default',
                        'homeFolder' => $this->tokenStorage->getToken()->getUser()->getId()
                    ],
                ],
            ]
        );
        $builder->get('body')->addModelTransformer(new CallbackTransformer(
            function ($value) { return $value; },
            function ($value) {
                return strip_tags($value, '<p><strong><em><s><ol><li><ul><a><blockquote><hr><pre><code><img>');
            }
        ));
        $builder->add('section', EntityType::class, [
            'class' => Section::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                    ->where('s.enabled = 1');
            },
        ]);
        $builder->add('tags', EntityType::class, ['class' => Tag::class, 'multiple' => true]);
        $builder->add('status', ChoiceType::class, ['choices' => Status::getConstants()]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => PostDTO::class]);
    }
}