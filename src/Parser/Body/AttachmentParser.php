<?php

namespace Weble\FatturaElettronica\Parser\Body;

use Weble\FatturaElettronica\Address;
use Weble\FatturaElettronica\Attachment;
use Weble\FatturaElettronica\Billable;
use Weble\FatturaElettronica\BillablePerson;
use Weble\FatturaElettronica\Contracts\AddressInterface;
use Weble\FatturaElettronica\Contracts\AttachmentInterface;
use Weble\FatturaElettronica\Contracts\BillableInterface;
use Weble\FatturaElettronica\Contracts\DigitalDocumentInstanceInterface;
use Weble\FatturaElettronica\Contracts\DigitalDocumentInterface;
use Weble\FatturaElettronica\Contracts\DigitalDocumentParserInterface;
use Weble\FatturaElettronica\Contracts\DiscountInterface;
use Weble\FatturaElettronica\Contracts\FundInterface;
use Weble\FatturaElettronica\Contracts\RelatedDocumentInterface;
use Weble\FatturaElettronica\Customer;
use Weble\FatturaElettronica\DigitalDocument;
use Weble\FatturaElettronica\DigitalDocumentInstance;
use Weble\FatturaElettronica\Discount;
use Weble\FatturaElettronica\Enums\DocumentFormat;
use Weble\FatturaElettronica\Enums\DocumentType;
use Weble\FatturaElettronica\Exceptions\InvalidFileNameExtension;
use Weble\FatturaElettronica\Exceptions\InvalidP7MFile;
use SimpleXMLElement;
use Weble\FatturaElettronica\Exceptions\InvalidXmlFile;
use Weble\FatturaElettronica\Fund;
use Weble\FatturaElettronica\Parser\XmlUtilities;
use Weble\FatturaElettronica\RelatedDocument;
use Weble\FatturaElettronica\Representative;
use Weble\FatturaElettronica\Shipment;
use Weble\FatturaElettronica\ShippingLabel;
use Weble\FatturaElettronica\Supplier;
use DateTime;
use TypeError;

class AttachmentParser extends AbstractBodyParser
{
    protected function performParsing ()
    {
        $attachments = (array)$this->extractValueFromXml('Allegati', false);
        foreach ($attachments as $attachment) {
            $instance = $this->extractAttachmentFrom($attachment);
            $this->digitalDocymentInstance->addAttachment($instance);
        }
    }

    /**
     * @param $attachment
     * @return Attachment
     */
    protected function extractAttachmentFrom ($attachment): AttachmentInterface
    {
        $instance = new Attachment();

        $value = $this->extractValueFromXmlElement($attachment, 'NomeAttachment');
        $instance->setName($value);

        $value = $this->extractValueFromXmlElement($attachment, 'AlgoritmoCompressione');
        $instance->setCompression($value);

        $value = $this->extractValueFromXmlElement($attachment, 'FormatoAttachment');
        $instance->setFormat($value);

        $value = $this->extractValueFromXmlElement($attachment, 'DescrizioneAttachment');
        $instance->setDescription($value);

        $value = $this->extractValueFromXmlElement($attachment, 'Attachment');
        $instance->setAttachment($value);
        return $instance;
    }
}
