<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

    class ApplicationType extends AbstractType
    {
        /**
         * Permet d'avoir la config de base d'un champ
         *
         * @param string $label
         * @param string $placeholder
         * @param array $options
         * @return array
         */
        protected function getConfiguration($label,$placeholder,$options=[])
        {
            return array_merge_recursive(
            [
                'label' => $label,
                'attr' => ['placeholder' => $placeholder]
            ],
                $options);
        }

        /**
         * Permet d'avoir la config de base d'un champ de type datepicker
         *
         * @param string $label
         * @param string $placeholder
         * @param array $options
         * @return array
         */
        protected function getConfigurationDatepickerInput($label,$options=[])
        {
            return array_merge(
            [
                'label' => $label,
                'attr' => ['class' => 'js-datepicker',
                           'placeholder' => 'Sélectionnez une date']
            ],
                $options);
        }
    }