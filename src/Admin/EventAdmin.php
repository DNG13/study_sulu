<?php

namespace App\Admin;

use App\Entity\Event;
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;

class EventAdmin extends Admin
{
    const EVENT_LIST_VIEW = 'app.events_list';
    const EVENT_FORM_KEY = 'event_details';
    const EVENT_ADD_FORM_VIEW = 'app.event_add_form';
    const EVENT_EDIT_FORM_VIEW = 'app.event_edit_form';

    public function __construct(private ViewBuilderFactoryInterface $viewBuilderFactory)
    {
    }

    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        $eventNavigationItem = new NavigationItem('app.events');
        $eventNavigationItem->setView(static::EVENT_LIST_VIEW);
        $eventNavigationItem->setIcon('su-calendar');
        $eventNavigationItem->setPosition(30);

        $navigationItemCollection->add($eventNavigationItem);
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $listView = $this->viewBuilderFactory->createListViewBuilder(static::EVENT_LIST_VIEW, '/events')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setListKey('events')
            ->addListAdapters(['table'])
            ->setAddView(static::EVENT_ADD_FORM_VIEW)
            ->setEditView(static::EVENT_EDIT_FORM_VIEW)
            ->setTitle('app.events_list')
            ->addToolbarActions([new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')]);

        $viewCollection->add($listView);

        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::EVENT_ADD_FORM_VIEW, '/events/add')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setBackView(static::EVENT_LIST_VIEW);

        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::EVENT_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setFormKey(static::EVENT_FORM_KEY)
            ->setTabTitle('app.event_add_form')
            ->setEditView(static::EVENT_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save'), new ToolbarAction('sulu_admin.delete')])
            ->setParent(static::EVENT_ADD_FORM_VIEW);

        $viewCollection->add($addDetailsFormView);

        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::EVENT_EDIT_FORM_VIEW, '/events/:id')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setBackView(static::EVENT_LIST_VIEW);

        $viewCollection->add($editFormView);

        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::EVENT_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setFormKey(static::EVENT_FORM_KEY)
            ->setTabTitle('app.event_edit_form')
            ->addToolbarActions([new ToolbarAction('sulu_admin.save'), new ToolbarAction('sulu_admin.delete')])
            ->setParent(static::EVENT_EDIT_FORM_VIEW);

        $viewCollection->add($editDetailsFormView);
    }
}