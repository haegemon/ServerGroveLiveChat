<?php

namespace ServerGrove\LiveChatBundle\Command;

use ServerGrove\LiveChatBundle\Document\Operator;

/**
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
class AddAdministratorCommand extends AddOperatorCommand
{

    /**
     * @see Command
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('sglivechat:admin:add-administrator');
        $this->setDescription('Create new Administrator');
    }

    public function createOperator()
    {
        $operator = new Operator();
        $operator->promote();

        return $operator;
    }
}