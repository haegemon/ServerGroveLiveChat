<?php

namespace ServerGrove\LiveChatBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use ServerGrove\LiveChatBundle\Document\Operator;
use Symfony\Component\Console\Input\InputArgument;
use MongoCursorException;

/**
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
class AddOperatorCommand extends BaseCommand
{

    /**
     * @see Command
     */
    protected function configure()
    {
        $this->setDefinition(
            array(
                new InputArgument('name', InputArgument::REQUIRED, 'The operator name'),
                new InputArgument('email', InputArgument::REQUIRED, 'The operator e-mail'),
                new InputArgument('password', InputArgument::REQUIRED, 'The admin password')
            )
        )->setName('sglivechat:admin:add-operator')->setDescription('Create new Operator');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $operator = $this->createOperator();

            $operator->setName($input->getArgument('name'));
            $operator->setEmail($input->getArgument('email'));

            $factory  = $this->getContainer()->get('security.encoder_factory');
            $encoder  = $factory->getEncoder($operator);
            $password = $encoder->encodePassword(
                $input->getArgument('password'),
                $operator->getSalt()
            );

            $operator->setPasswd($password);

            $this->getDocumentManager()->persist($operator);
            $this->getDocumentManager()->flush();
        } catch (MongoCursorException $e) {
            $output->write($e->getMessage(), true);
        }
    }

    public function createOperator()
    {
        return new Operator();
    }

}