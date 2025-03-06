<?php
namespace Nxtech\RequestForQuote\Model\Mail;

use Magento\Framework\Mail\Template\TransportBuilder as MagentoTransportBuilder;
use Laminas\Mime\Part as MimePart;
use Laminas\Mime\Message as MimeMessage;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\MessageInterfaceFactory;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Mail\TransportInterfaceFactory;

use Magento\Framework\Mail\MimeInterface;
use Magento\Framework\Mail\MimePartInterfaceFactory;
use Magento\Framework\Mail\MimeMessageInterfaceFactory;
use Magento\Framework\Mail\EmailMessageInterfaceFactory;
use Magento\Framework\Mail\AddressConverter;

class TransportBuilder extends MagentoTransportBuilder
{
    protected $parts = [];

    private $messageData = [];
    //protected $messageFactory;

    public function __construct(
        FactoryInterface $templateFactory,
        MessageInterface $message,
        SenderResolverInterface $senderResolver,
        ObjectManagerInterface $objectManager,
        TransportInterfaceFactory $mailTransportFactory,
        protected MessageInterfaceFactory $messageFactory,
        protected MimePartInterfaceFactory $mimePartInterfaceFactory,
        protected MimeMessageInterfaceFactory $mimeMessageInterfaceFactory,
        protected EmailMessageInterfaceFactory $emailMessageInterfaceFactory,
        protected AddressConverter $addressConverter
    ) {
        parent::__construct($templateFactory, $message, $senderResolver, $objectManager, $mailTransportFactory);
        //$this->messageFactory = $messageFactory;
    }

    public function addAttachment($content, $fileName, $type = 'application/pdf')
    {
        $attachment = new MimePart($content);
        $attachment->type = $type;
        $attachment->disposition = 'attachment';
        $attachment->encoding = 'base64';
        $attachment->filename = $fileName;

        $this->parts[] = $attachment;

        return $this;
    }

    protected function prepareMessage()
    {
        if ($this->message === null) {
            $this->message = $this->messageFactory->create();
        }


        $template = $this->getTemplate();
        $content = $template->processTemplate();
        $partType = MimeInterface::TYPE_HTML;

        array_unshift($this->parts, $content);

        $mimePart = $this->mimePartInterfaceFactory->create(
            [
                'content' => $content,
                'type' => $partType
            ]
        );

        array_unshift($this->parts, $content);

        $this->messageData['encoding'] = $mimePart->getCharset();
        $this->messageData['body'] = $this->mimeMessageInterfaceFactory->create(
            ['parts' => [$mimePart]]
        );

        // $this->messageData['to'] = array('email' => 'testnxtechnolab@gmail.com');
        // $this->messageData['from'] = array('email' => 'testnxtechnolab@gmail.com');

        $this->messageData['subject'] = html_entity_decode(
            (string) $template->getSubject(),
            ENT_QUOTES
        );

        $this->message = $this->emailMessageInterfaceFactory->create($this->messageData);

        //$bodyPart = new MimePart($body);
        //$bodyPart->type = 'text/html';

        /*array_unshift($this->parts, $bodyPart);
        $mimeMessage = new MimeMessage();
        $mimeMessage->setParts($this->parts);
        $this->message->setBody($mimeMessage);*/
        //return $this;
        return parent::prepareMessage();
    }
}
