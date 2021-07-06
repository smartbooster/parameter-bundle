<?php

namespace Smart\ParameterBundle\Admin;

use Smart\SonataBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
class ParameterAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection
            ->remove('create')
            ->remove('delete')
        ;
        parent::configureRoutes($collection);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'field.label_id'])
            ->addIdentifier('code', null, ['label' => 'field.label_code'])
            ->add('help', null, ['label' => 'field.label_help'])
            ->add('value', null, ['label' => 'field.label_value'])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('code', null, [
                'show_filter' => true,
                'label' => 'field.label_code'
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('fieldset.label_general')
                ->add('id', null, ['label' => 'field.label_id'])
                ->add('code', null, ['label' => 'field.label_code'])
                ->add('help', null, ['label' => 'field.label_help'])
                ->add('value', null, ['label' => 'field.label_value'])
            ->end()
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('fieldset.label_general')
                ->add('code', null, [
                    'label' => 'field.label_code',
                    'disabled' => true
                ])
                ->add('help', null, [
                    'label' => 'field.label_help',
                    'disabled' => true
                ])
                ->add('value', null, ['label' => 'field.label_value'])
            ->end()
        ;
    }

    public function getExportFormats(): array
    {
        return ['csv'];
    }

    public function getExportFields(): array
    {
        return [
            $this->trans('field.label_code') => 'code',
            $this->trans('field.label_value') => 'value',
            $this->trans('field.label_help') => 'help',
        ];
    }
}
