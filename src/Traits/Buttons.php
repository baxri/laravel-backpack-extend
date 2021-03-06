<?php

namespace Unipay\CustomCrud\Traits;

trait Buttons
{
    // ------------
    // BUTTONS
    // ------------

    // TODO: $this->crud->reorderButtons('stack_name', ['one', 'two']);

    /**
     * Add a button to the CRUD table view.
     *
     * @param string $stack Where should the button be visible? Options: top, line, bottom.
     * @param string $name The name of the button. Unique.
     * @param string $type Type of button: view or model_function.
     * @param string $content The HTML for the button.
     * @param bool|string $position Position on the stack: beginning or end. If false, the position will be
     *                                 'beginning' for the line stack or 'end' otherwise.
     * @param bool $replaceExisting True if a button with the same name on the given stack should be replaced.
     * @return \Backpack\CRUD\PanelTraits\CrudButton The new CRUD button.
     */
    public function addButton($stack, $name, $type, $content, $position = false, $replaceExisting = true, $route = null, $style = 'primary', $popup_title = null, $popup_description = null, $comment = false)
    {
        if ($position == false) {
            switch ($stack) {
                case 'line':
                    $position = 'beginning';
                    break;

                default:
                    $position = 'end';
                    break;
            }
        }

        if ($replaceExisting) {
            $this->removeButton($name, $stack);
        }

        $button = new CrudButton($stack, $name, $type, $content, $route, $style, $popup_title, $popup_description, $comment);
        switch ($position) {
            case 'beginning':
                $this->buttons->prepend($button);
                break;

            default:
                $this->buttons->push($button);
                break;
        }

        return $button;
    }

    public function addRouteButton($name, $route = null, $style = 'primary', $popup_title = null, $popup_description = null, $comment = false, $view = 'route_button', $method = 'POST'){

        $view = 'ccrud::inc.custom.' . $view;

        if($popup_title == null){
            $popup_title = $name;
        }

        if($popup_description == null){
            $popup_description = 'Continue?';
        }

        $this->addButton('line', $name, 'route', $view, false, true, $route, $style, $popup_title, $popup_description, $comment);
    }

    public function addButtonFromModelFunction($stack, $name, $model_function_name, $position = false)
    {
        $this->addButton($stack, $name, 'model_function', $model_function_name, $position);
    }

    public function addButtonFromView($stack, $name, $view, $position = false)
    {
        $view = 'vendor.backpack.crud.buttons.'.$view;

        $this->addButton($stack, $name, 'view', $view, $position);
    }

    public function buttons()
    {
        return $this->buttons;
    }

    public function initButtons()
    {
        $this->buttons = collect();

        // line stack
        $this->addButton('line', 'preview', 'view', 'crud::buttons.preview', 'end');
        $this->addButton('line', 'update', 'view', 'crud::buttons.update', 'end');
        $this->addButton('line', 'revisions', 'view', 'crud::buttons.revisions', 'end');
        $this->addButton('line', 'delete', 'view', 'crud::buttons.delete', 'end');

        // top stack
        $this->addButton('top', 'create', 'view', 'crud::buttons.create');
        $this->addButton('top', 'reorder', 'view', 'crud::buttons.reorder');
    }

    /**
     * Modify the attributes of a button.
     *
     * @param  string $name          The button name.
     * @param  array $modifications  The attributes and their new values.
     * @return button                The button that has suffered the changes, for daisychaining methods.
     */
    public function modifyButton($name, $modifications = null)
    {
        $button = $this->buttons()->firstWhere('name', $name);

        if (! $button) {
            abort(500, 'CRUD Button "'.$name.'" not found. Please check the button exists before you modify it.');
        }

        if (is_array($modifications)) {
            foreach ($modifications as $key => $value) {
                $button->{$key} = $value;
            }
        }

        return $button;
    }

    /**
     * Remove a button from the CRUD panel.
     *
     * @param string $name Button name.
     * @param string $stack Optional stack name.
     */
    public function removeButton($name, $stack = null)
    {
        $this->buttons = $this->buttons->reject(function ($button) use ($name, $stack) {
            return $stack == null ? $button->name == $name : ($button->stack == $stack) && ($button->name == $name);
        });
    }

    public function removeAllButtons()
    {
        $this->buttons = collect([]);
    }

    public function removeAllButtonsFromStack($stack)
    {
        $this->buttons = $this->buttons->reject(function ($button) use ($stack) {
            return $button->stack == $stack;
        });
    }

    public function removeButtonFromStack($name, $stack)
    {
        $this->buttons = $this->buttons->reject(function ($button) use ($name, $stack) {
            return $button->name == $name && $button->stack == $stack;
        });
    }
}

class CrudButton
{
    public $stack;
    public $name;
    public $type = 'view';
    public $content;
    public $route = null;
    public $style = null;
    public $popup_title = null;
    public $popup_description = null;
    public $comment = false;

    public function __construct($stack, $name, $type, $content, $route = null, $style = null, $popup_title = null, $popup_description = null, $comment = false)
    {
        $this->stack = $stack;
        $this->name = $name;
        $this->type = $type;
        $this->content = $content;
        $this->route = $route;
        $this->style = $style;
        $this->popup_title = $popup_title;
        $this->popup_description = $popup_description;
        $this->comment = $comment;
    }
}
