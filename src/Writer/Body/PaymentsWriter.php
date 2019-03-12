<?php

namespace Weble\FatturaElettronica\Writer\Body;

use Weble\FatturaElettronica\Utilities\SimpleXmlExtended;

class PaymentsWriter extends AbstractBodyWriter
{
    protected function performWrite ()
    {
        /** @var \Weble\FatturaElettronica\Contracts\PaymentInfoInterface $payment */
        foreach( $this->body->getPaymentInformations() as $payment ) {
            $datiPagamento = $this->xml->addChild( 'DatiPagamento' );
            $datiPagamento->addChild( 'CondizioniPagamento', (string) $payment->getTerms() );

            /** @var \Weble\FatturaElettronica\Contracts\PaymentDetailsInterface $details */
            foreach ($payment->getDetails() as $details) {
                $dettalioPagamento = $datiPagamento->addChild('DettaglioPagamento');

                if ($details->getPayee() !== null) {
                    $dettalioPagamento->addChild('Beneficiario', SimpleXmlExtended::sanitizeText($details->getPayee()));
                }

                $dettalioPagamento->addChild('ModalitaPagamento', $details->getMethod());

                if ($details->getDueDateFrom() !== null) {
                    $dettalioPagamento->addChild('DataRiferimentoTerminiPagamento', $details->getDueDateFrom()->format('Y-m-d'));
                }

                if ($details->getDueDays() !== null) {
                    $dettalioPagamento->addChild('GiorniTerminiPagamento', $details->getDueDays());
                }

                if ($details->getDueDate() !== null) {
                    $dettalioPagamento->addChild('DataScadenzaPagamento', $details->getDueDate()->format('Y-m-d'));
                }

                $dettalioPagamento->addChild('ImportoPagamento', empty($details->getAmount()) ? '0.00' : $details->getAmount());

                if ($details->getPostalOfficeCode() !== null) {
                    $dettalioPagamento->addChild('CodUfficioPostale', SimpleXmlExtended::sanitizeText($details->getPostalOfficeCode()));
                }

                if ($details->getPayerSurname() !== null) {
                    $dettalioPagamento->addChild('CognomeQuietanzante', SimpleXmlExtended::sanitizeText($details->getPayerSurname()));
                }

                if ($details->getPayerName() !== null) {
                    $dettalioPagamento->addChild('NomeQuietanzante', SimpleXmlExtended::sanitizeText($details->getPayerName()));
                }

                if ($details->getPayerFiscalCode() !== null) {
                    $dettalioPagamento->addChild('CFQuietanzante', SimpleXmlExtended::sanitizeText($details->getPayerFiscalCode()));
                }

                if ($details->getPayerTitle() !== null) {
                    $dettalioPagamento->addChild('TitoloQuietanzante', SimpleXmlExtended::sanitizeText($details->getPayerTitle()));
                }

                if ($details->getBankName() !== null) {
                    $dettalioPagamento->addChild('IstitutoFinanziario', SimpleXmlExtended::sanitizeText($details->getBankName()));
                }

                if ($details->getIban() !== null) {
                    $dettalioPagamento->addChild('IBAN', SimpleXmlExtended::sanitizeText($details->getIban()));
                }

                if ($details->getAbi() !== null) {
                    $dettalioPagamento->addChild('ABI', SimpleXmlExtended::sanitizeText($details->getAbi()));
                }

                if ($details->getCab() !== null) {
                    $dettalioPagamento->addChild('CAB', SimpleXmlExtended::sanitizeText($details->getCab()));
                }

                if ($details->getBic() !== null) {
                    $dettalioPagamento->addChild('BIC', SimpleXmlExtended::sanitizeText($details->getBic()));
                }

                if ($details->getEarlyPaymentDiscount()) {
                    $dettalioPagamento->addChild('ScontoPagamentoAnticipato', $details->getEarlyPaymentDiscount());
                }

                if ($details->getEarlyPaymentDateLimit() !== null) {
                    $dettalioPagamento->addChild('DataLimitePagamentoAnticipato', $details->getEarlyPaymentDateLimit()->format('Y-m-d'));
                }

                if ($details->getLatePaymentFee()) {
                    $dettalioPagamento->addChild('PenalitaPagamentiRitardati', $details->getLatePaymentFee());
                }

                if ($details->getLatePaymentDateLimit() !== null) {
                    $dettalioPagamento->addChild('DataDecorrenzaPenale', $details->getLatePaymentDateLimit()->format('Y-m-d'));
                }

                if ($details->getPaymentCode()) {
                    $dettalioPagamento->addChild('CodicePagamento', SimpleXmlExtended::sanitizeText($details->getPaymentCode()));
                }
            }
        }
    }
}
