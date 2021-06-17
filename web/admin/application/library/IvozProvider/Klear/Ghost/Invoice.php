<?php

use Ivoz\Core\Application\Service\DataGateway;
use Ivoz\Provider\Domain\Model\Company\Company;
use Ivoz\Provider\Domain\Model\Invoice\InvoiceDto;

class IvozProvider_Klear_Ghost_Invoice extends KlearMatrix_Model_Field_Ghost_Abstract
{
    /**
     * @param InvoiceDto $invoice
     * @return mixed|string
     * @throws Exception
     */
    public function getStatus(InvoiceDto $invoice)
    {
        // Status translated to web language
        $status = Klear_Model_Gettext::gettextCheck(
            sprintf(
                "_('%s')",
                ucfirst($invoice->getStatus())
            )
        );

        if ($invoice->getStatus() === 'error') {
            $statusMsg = htmlspecialchars(
                $invoice->getStatusMsg(),
                ENT_QUOTES
            );

            return sprintf(
                "%s <span class='ui-silk inline ui-silk-exclamation' title='%s'></span>",
                $status,
                $statusMsg
            );
        }

        return $status;
    }

    /**
     * * Return total with currency symbol suffix
     *
     * @param InvoiceDto $invoice
     * @return string
     */
    public function getTotal(InvoiceDto $invoice)
    {
        $currencySymbol = $this->getCompanyCurrencySymbol(
            $invoice->getCompanyId()
        );

        return sprintf(
            "%.2f %s",
            $invoice->getTotal(),
            $currencySymbol
        );
    }

    /**
     * Return total with tax and currency symbol suffix
     *
     * @param InvoiceDto $invoice
     * @return string
     */
    public function getTotalWithTax(InvoiceDto $invoice)
    {
        $currencySymbol = $this->getCompanyCurrencySymbol(
            $invoice->getCompanyId()
        );

        return sprintf(
            "%.2f %s",
            $invoice->getTotalWithTax(),
            $currencySymbol
        );
    }

    private function getCompanyCurrencySymbol($companyId)
    {
        /** @var DataGateway $dataGateway */
        $dataGateway = \Zend_Registry::get('data_gateway');

        return $dataGateway->remoteProcedureCall(
            Company::class,
            $companyId,
            'getCurrencySymbol',
            []
        );
    }
}
