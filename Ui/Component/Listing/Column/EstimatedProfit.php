<?php

namespace Selecto\TaskBackend\Ui\Component\Listing\Column;

use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Catalog\Model\ProductRepository;

/**
 * Use for to show estimated profit of products in grid
 */
class EstimatedProfit extends Column
{
    /**
     * Product Repository
     *
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ProductRepository $productRepository,
        array $components = [],
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource Grid Row Data
     * 
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $esitmatedProfit = "";
                if (isset($item['cost'])) {
                    $esitmatedProfit = (float)$this->getEstimatedProfit($item['entity_id'], $item['qty']);
                }
                $item[$this->getData('name')] = $esitmatedProfit;
            }
        }

        return $dataSource;
    }

    /**
     * Get estimated profit for product
     *
     * @param int $id        Product Entity Id
     * @param int $stock_qty Product Stock Qty
     *
     * @return float
     */
    public function getEstimatedProfit($id, $stock_qty)
    {
        $product = $this->productRepository->getById($id);
        return ($product->getPrice() - $product->getCost()) *  $stock_qty;
    }
}
