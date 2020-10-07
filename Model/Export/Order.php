<?php
namespace Consumewithadele\ExportOrder\Model\Export;

class Order
{
    protected $orderInfoLine = [
        'header-line',
        'increment_id',
        'status',
        'date',
        'Customer Name',
        'Customer Email',
        'subtotal',
        'grand total',
        'shipping'
    ];
    protected $adddressLine = [
        'header-line',
        'Customer Name',
        'Street',
        'City',
        'Region',
        'Postcode',
        'Country',
        'Phone'
    ];
    protected $itemLine = [
        'header-line',
        'SKU',
        'QTY',
        'Row Total'
    ];

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    public function exportOrder(\Magento\Sales\Model\Order $order)
    {
        $csv = [];
        $orderData = $this->getOrderDetails($order);
        $csv[] = $this->orderInfoLine;
        $csv[] = $orderData;
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        $csv[] = $this->adddressLine;
        $csv[] = $this->getAdressDetails($billingAddress);
        $csv[] = $this->getAdressDetails($shippingAddress);
        $csv[] = $this->itemLine;
        $csv = array_merge($csv, $this->getOrderItemsDetails($order));
        return $csv;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    private function getOrderDetails(\Magento\Sales\Model\Order $order)
    {
        $data = [];
        $data[] = $order->getIncrementId();
        $data[] = $order->getState();
        $data[] = $order->getCreatedAt();
        $data[] = $order->getCustomerName();
        $data[] = $order->getCustomerEmail();
        $data[] = $order->getSubtotal();
        $data[] = $order->getShippingAmount();
        $data[] = $order->getGrandTotal();

        return $data;
    }

    /**
     * @param \Magento\Sales\Model\Order\Address $address
     * @return array
     */
    private function getAdressDetails(\Magento\Sales\Model\Order\Address $address)
    {
        $data = [];
        $data[] = $address->getFirstname() . ' ' . $address->getLastname();
        $street = $address->getStreet();
        $data[] = is_array($street) ? implode(', ', $street) : $street;
        $data[] = $address->getCity();
        $data[] = $address->getRegion();
        $data[] = $address->getPostcode();
        $data[] = $address->getCountryId();
        $data[] = $address->getTelephone();
        return $data;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    private function getOrderItemsDetails(\Magento\Sales\Model\Order $order)
    {
        $data = [];
        foreach ($order->getAllVisibleItems() as $item) {
            /** @var \Magento\Sales\Model\Order\Item $item */
            $_data = [];
            $_data[] = $item->getSku();
            $_data[] = $item->getQtyOrdered();
            $_data[] = $item->getRowTotal();
            $data[] = $_data;
        }
        return $data;
    }
}
