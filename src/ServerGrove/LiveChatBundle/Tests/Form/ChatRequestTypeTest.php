<?php

namespace ServerGrove\LiveChatBundle\Tests\Form;

use Prophecy\Argument;
use ServerGrove\LiveChatBundle\Form\ChatRequestType;


class ChatRequestTypeTest extends \TestCase
{

    /**
     * @test
     */
    public function adding_fields()
    {
        $formBuilder = $this->prophet->prophesize('\\Symfony\\Component\\Form\\FormBuilderInterface');
        $formBuilder->add(Argument::exact('name'));
        $formBuilder->add(Argument::exact('email'));
        $formBuilder->add(Argument::exact('question'), Argument::exact('textarea'));

        $formType = new ChatRequestType();
        $formType->buildForm($formBuilder->reveal(), array());
    }

}