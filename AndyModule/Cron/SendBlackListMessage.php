<?php

namespace Amasty\AndyModule\Cron;

use Amasty\AndyModule\Model\BlacklistRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Psr\Log\LoggerInterface;

class SendBlackListMessage
{
    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var FactoryInterface
     */
    private $templateFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        BlacklistRepository $blacklistRepository,
        TransportBuilder $transportBuilder,
        FactoryInterface $templateFactory,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->blacklistRepository = $blacklistRepository;
        $this->transportBuilder = $transportBuilder;
        $this->templateFactory = $templateFactory;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $templateId = trim($this->scopeConfig->getValue('andy_config/general/email_template'));
        $senderEmail = 'andy@amasty.com';
        $senderName = 'Admin';
        $toEmail = trim($this->scopeConfig->getValue('andy_config/general/to_email_value'));

        if (empty($templateId) || empty($toEmail)) {
            $this->logger->debug('Amasty AndyModule Email Job not done (too few arguments).');
            exit();
        }

        $blackList = $this->blacklistRepository->get('');
        $listIds = $blackList->getCollection()->getAllIds();

        $from = [
            'email' => $senderEmail,
            'name' => $senderName
        ];
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => 0
        ];

        foreach ($listIds as $item) {
            $blackList = $this->blacklistRepository->getId($item);

            $dataBlacklist = [
                'blackList' => $blackList,
                'sku' => $blackList->getSku(),
                'qty' => $blackList->getQty()
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($dataBlacklist)
                ->setFromByScope($from)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();

//            $bodyEmail = $transport->getMessage()->getBodyText();

            $template = $this->templateFactory
                    ->get($templateId)
                    ->setVars($dataBlacklist)
                    ->setOptions($templateOptions);

            $bodyMessage = $template->processTemplate();

            $blackList->setData('email_body', $bodyMessage)->save();
        }

        $this->logger->debug('Amasty AndyModule Email Job done.');
    }
}
