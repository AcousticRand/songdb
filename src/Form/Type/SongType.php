<?php
/**
 * Created by PhpStorm.
 * User: randt
 * Date: 1/12/2019
 * Time: 7:00 PM
 */

namespace App\Form\Type;

use App\Document\Song;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SongType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artist', TextType::class)
            ->add('title', TextType::class)
            ->add('key', TextType::class)
            ->add('camelot', TextType::class)
            ->add('duration', TextType::class)
            ->add('bpm', NumberType::class, ['scale' => 0])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $song = $event->getData();
                $form = $event->getForm();

                if ($song && null !== $song->getId()) {
                    $form->add('id', HiddenType::class);
                }
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'data_class' => Song::class,
                'allow_extra_fields'=> true,
            ]
        );
    }
}