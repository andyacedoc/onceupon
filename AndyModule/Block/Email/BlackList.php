<?php

namespace Amasty\AndyModule\Block\Email;

use Magento\Framework\View\Element\Template;

class BlackList extends Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getBlackList()
    {
        return $this->getData('black_list'); //почему здесь не подхватывает blackList=$blackList из blacklist_template.html
    }                                            //модель почему-то формируется с именем black_list
}                                                //хотя в Amasty\AndyModule\Controller\Email\Index.php модель пишется в $blackList
