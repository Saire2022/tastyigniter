<?php

namespace Igniter\Cart\Components;

use Admin\Models\Orders_model;
use Admin\Models\Stock_history_model;
use Admin\Models\Stocks_model;
use Exception;
use Igniter\Cart\Classes\CartManager;
use Igniter\Cart\Models\CartSettings;
use Igniter\Cart\Models\Menus_model;
use Igniter\Flame\Cart\Facades\Cart;
use Igniter\Flame\Exception\ApplicationException;
use Igniter\Local\Facades\Location;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Event;
use Igniter\Cart\Classes\OrderManager;
use Auth;



class CartBox extends \System\Classes\BaseComponent
{
    use \Main\Traits\UsesPage;

    /**
     * @var \Igniter\Cart\Classes\CartManager
     */
    protected $cartManager;
    protected $orderManager;


    public function initialize()
    {
        $this->cartManager = CartManager::instance()->checkStock(
            (bool)$this->property('checkStockCheckout', true)
        );
        //$this->orderManager = OrderManager::instance();
    }

    public function defineProperties()
    {
        return [
            'showCartItemThumb' => [
                'label' => 'Show cart menu item image in the popup',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'cartItemThumbWidth' => [
                'label' => 'Cart item image width',
                'type' => 'number',
                'span' => 'left',
                'validationRule' => 'nullable|integer',
            ],
            'cartItemThumbHeight' => [
                'label' => 'Cart item image height',
                'type' => 'number',
                'span' => 'right',
                'validationRule' => 'nullable|integer',
            ],
            'limitCartItemOptionsValues' => [
                'label' => 'Limit number of item option values',
                'type' => 'number',
                'default' => 0,
                'validationRule' => 'nullable|integer',
            ],
            'checkStockCheckout' => [
                'label' => 'lang:igniter.cart::default.help_stock_checkout',
                'type' => 'switch',
                'default' => true,
                'validationRule' => 'required|boolean',
            ],
            'pageIsCheckout' => [
                'label' => 'Whether this component is loaded on the checkout page',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'pageIsEdit' => [
                'label' => 'Whether this component is loaded on the edit page',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'pageIsCart' => [
                'label' => 'Whether this component is loaded on the cart page',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'hideZeroOptionPrices' => [
                'label' => 'Whether to hide zero prices on options',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'checkoutPage' => [
                'label' => 'Checkout Page',
                'type' => 'select',
                'options' => [static::class, 'getThemePageOptions'],
                'default' => 'checkout'.DIRECTORY_SEPARATOR.'checkout',
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'localBoxAlias' => [
                'label' => 'Specify the LocalBox component alias used to refresh the localbox after the order type is changed',
                'type' => 'text',
                'default' => 'localBox',
                'validationRule' => 'required|regex:/^[a-z0-9\-_]+$/i',
            ],
        ];
    }

    public function onRun()
    {
        $this->addJs('js/cartbox.js', 'cart-box-js');
        $this->addJs('js/cartitem.js', 'cart-item-js');
        $this->addJs('js/cartbox.modal.js', 'cart-box-modal-js');

        $this->prepareVars();

        $tableId = input('table_id') ?? $this->param('table_id');
        $this->page['tableId'] = $tableId;

        //Get order data
        $orderId = request()->query('order_id');

        if($orderId){
            $order = Orders_model::find($orderId);
            // Deserialize the cart data
            $cartContent = $order->cart;
            session()->put('cart.location-1.content', $cartContent);
        }
    }

    protected function prepareVars()
    {
        $this->page['showCartItemThumb'] = $this->property('showCartItemThumb', false);
        $this->page['cartItemThumbWidth'] = $this->property('cartItemThumbWidth');
        $this->page['cartItemThumbHeight'] = $this->property('cartItemThumbHeight');
        $this->page['pageIsCart'] = $this->property('pageIsCart');
        $this->page['pageIsCheckout'] = $this->property('pageIsCheckout');
        $this->page['pageIsEdit'] = $this->property('pageIsEdit');
        $this->page['hideZeroOptionPrices'] = (bool)$this->property('hideZeroOptionPrices');
        $this->page['limitCartItemOptionsValues'] = $this->property('limitCartItemOptionsValues', 0);

        //$this->page['checkoutEventHandler'] = $this->getEventHandler('onProceedToCheckout');
        $this->page['orderEventHandler'] = $this->getEventHandler('onSaveOrderWithoutCheckout');
        $this->page['editOrderHandler'] = $this->getEventHandler('onEditOrderWithoutCheckout');
        $this->page['updateCartItemEventHandler'] = $this->getEventHandler('onUpdateCart');
        $this->page['updateCartItemQtyEventHandler'] = $this->getEventHandler('onUpdateItemQuantity');
        $this->page['applyCouponEventHandler'] = $this->getEventHandler('onApplyCoupon');
        $this->page['applyTipEventHandler'] = $this->getEventHandler('onApplyTip');
        $this->page['loadCartItemEventHandler'] = $this->getEventHandler('onLoadItemPopup');
        $this->page['removeCartItemEventHandler'] = $this->getEventHandler('onUpdateItemQuantity');
        $this->page['removeConditionEventHandler'] = $this->getEventHandler('onRemoveCondition');
        $this->page['refreshCartEventHandler'] = $this->getEventHandler('onRefresh');

        $this->page['cart'] = $this->cartManager->getCart();
    }

    public function fetchPartials()
    {
        $this->prepareVars();

        return [
            '#cart-items' => $this->renderPartial('@items'),
            '#cart-coupon' => $this->renderPartial('@coupon_form'),
            '#cart-tip' => $this->renderPartial('@tip_form'),
            '#cart-totals' => $this->renderPartial('@totals'),
            '#cart-buttons' => $this->renderPartial('@buttons'),
            '[data-cart-total]' => currency_format(Cart::total()),
            '[data-cart-count]' => Cart::count(),
            '#notification' => $this->renderPartial('flash'),
        ];
    }

    public function onRefresh()
    {
        return $this->fetchPartials();
    }

    public function onLoadItemPopup()
    {
        $menuItem = $this->cartManager->findMenuItem(post('menuId'));

        $cartItem = null;
        if (strlen($rowId = post('rowId'))) {
            $cartItem = $this->cartManager->getCartItem($rowId);
            $menuItem = $cartItem->model;
        }

        $this->cartManager->validateLocation();

        $this->cartManager->validateOrderTime();

        $this->cartManager->validateMenuItem($menuItem);

        $this->cartManager->validateMenuItemStockQty($menuItem, $cartItem ? $cartItem->qty : 0);

        $this->controller->pageCycle();

        return $this->renderPartial('@item_modal', [
            'formHandler' => $this->getEventHandler('onUpdateCart'),
            'cartItem' => $cartItem,
            'menuItem' => $menuItem,
        ]);
    }

    public function onUpdateCart()
    {
        try {
            $postData = post();

            $this->cartManager->addOrUpdateCartItem($postData);

            $this->controller->pageCycle();

            return $this->fetchPartials();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    public function onUpdateItemQuantity()
    {
        try {
            $action = (string)post('action');
            $rowId = (string)post('rowId');
            $quantity = (int)post('quantity');

            $this->cartManager->updateCartItemQty($rowId, $action ?: $quantity);

            $this->controller->pageCycle();

            return $this->fetchPartials();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    public function onRemoveItem()
    {
        return $this->onUpdateItemQuantity();
    }

    public function onApplyCoupon()
    {
        try {
            $this->cartManager->applyCouponCondition(post('code'));

            $this->controller->pageCycle();

            return $this->fetchPartials();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    public function onApplyTip()
    {
        try {
            $amountType = post('amount_type');
            if (!in_array($amountType, ['none', 'amount', 'custom']))
                throw new ApplicationException(lang('igniter.cart::default.alert_tip_not_applied'));

            $amount = post('amount');
            if (preg_match('/^\d+([\.\d]{2})?([%])?$/', $amount) === false)
                throw new ApplicationException(lang('igniter.cart::default.alert_tip_not_applied'));

            $this->cartManager->applyCondition('tip', [
                'amountType' => $amountType,
                'amount' => $amount,
            ]);

            $this->controller->pageCycle();

            return $this->fetchPartials();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    public function onRemoveCondition()
    {
        try {
            if (!strlen($conditionId = post('conditionId')))
                return;

            $this->cartManager->removeCondition($conditionId);
            $this->controller->pageCycle();

            return $this->fetchPartials();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    //Save order without checkout View
    public function onSaveOrderWithoutCheckout()
    {
        if (!Auth::getUser()) {
            throw new ApplicationException(lang('igniter.cart::default.checkout.alert_customer_not_logged'));
        }
        $this->cartManager->validateContents();

        try {
            if (!is_numeric($id = post('locationId')) || !($location = Location::getById($id)) || !$location->location_status) {
                throw new ApplicationException(lang('igniter.local::default.alert_location_required'));
            }

            Location::setCurrent($location);
            $tableId = post('tableId');
            if (!is_numeric($tableId)) {
                throw new ApplicationException(lang('Please select a table on the Home page before placing an order.'));
            }

            // Create a new order instance
            $orderManager = OrderManager::instance();
            $order = $orderManager->loadOrderData();
            $cart = $this->cartManager->getCart();
            $orderData = [
                'location_id' => $location->location_id,
                'table_id' => $tableId,
                'cart' => $cart->content(),
                'total_items' => $cart->count(),
                'order_total' => $cart->total(),
            ];
            $order->status_id = 1;
            $order->location_id=$location->location_id;
            $order->cart = $cart->content();
            $order->total_items = $cart->count();
            $order->order_total = $cart->total();
            $order->payment = "cod";
            $order->order_time = now()->format('H:i');
            $order->ip_address = request()->ip();
            $order->order_type = "delivery";
            $order->order_date = now();
            $order->customer_id = Auth::getUser()->customer_id;
            $savedOrder = $orderManager->saveOrder($order, $orderData);

            $orderId = $savedOrder->order_id;

            $this->updateStock($cart, $orderId);

            Cart::destroy();

            flash()->success(lang('Order saved successfully. You can complete it later.'));

            // Redirect to the home page after saving
            return Redirect::to('/tables');
        } catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    protected function updateStock($cart, $orderId)
    {
        foreach ($cart->content() as $item) {
            $menuItemId = $item->id;
            $quantity = $item->qty;

            $stock = Stocks_model::where('stockable_id', $menuItemId)->first();
            if ($stock) {
                if ($stock->quantity >= $quantity) {
                    $stock->quantity -= $quantity;
                    $stock->is_tracked = true;
                    $stock->save();

                    $this->recordStockHistory($stock->id, $orderId, $quantity);
                } else {
                    throw new ApplicationException(lang('Not enough stock available for item: ' . $item->name));
                }
            }
        }
    }

    protected function recordStockHistory($stockId, $orderId, $quantity)
    {
        $stockHistory = new Stock_history_model();
        $stockHistory->stock_id = $stockId;
        $stockHistory->staff_id = Auth::getUser()->customer_id ?? null; // Set staff_id to logged-in user if available
        $stockHistory->order_id = $orderId;
        $stockHistory->state = 'sold';
        $stockHistory->quantity = $quantity; // Corrected typo
        $stockHistory->created_at = now();
        $stockHistory->updated_at = now();
        $stockHistory->save();
    }

    public function onEditOrderWithoutCheckout () {

        $orderManager = OrderManager::instance();
        $orderId = request()->query('order_id');
        $editOrder = $orderManager->editOrderWithoutCheckout($orderId);

        Cart::destroy();

        flash()->success(lang('Order edited successfully. You can complete it later.'));
    }

    public function onProceedToCheckout()
    {
        try {
            if (!is_numeric($id = post('locationId')) || !($location = Location::getById($id)) || !$location->location_status)
                throw new ApplicationException(lang('igniter.local::default.alert_location_required'));

            Location::setCurrent($location);
            $tableId = post('tableId');
            //dd($tableId);Statuses_model
            if (!is_numeric($tableId)) {
                throw new ApplicationException(lang('Please select a table on the Home page before placing an order.'));
            }

            //Save tableId  in all checkout session
            session()->put('tableId', $tableId);

            $redirectUrl = $this->controller->pageUrl($this->property('checkoutPage'));

            return Redirect::to($redirectUrl);
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    public function locationIsClosed()
    {
        return !Location::instance()->checkOrderTime() || Location::instance()->checkNoOrderTypeAvailable();
    }

    public function hasMinimumOrder()
    {
        return $this->cartManager->cartTotalIsBelowMinimumOrder()
            || $this->cartManager->deliveryChargeIsUnavailable();
    }

    public function buttonLabel($checkoutComponent = null)
    {
        if ($this->locationIsClosed())
            return lang('igniter.cart::default.text_is_closed');

        if (!$this->property('pageIsCheckout') && $this->cartManager->getCart()->count())
            return lang('igniter.cart::default.button_order').' · '.currency_format($this->cartManager->getCart()->total());
//
//        if (!$this->property('pageIsEdit') && $this->cartManager->getCart()->count())
//            return lang('Edit Order').' · '.currency_format($this->cartManager->getCart()->total());

        if (!$this->property('pageIsCheckout'))
            return lang('igniter.cart::default.button_order');

        if ($checkoutComponent && !$checkoutComponent->canConfirmCheckout())
            return lang('igniter.cart::default.button_payment');

        return lang('igniter.cart::default.button_confirm');
    }

    public function getLocationId()
    {
        return Location::instance()->getId();
    }

    public function tippingEnabled()
    {
        return (bool)CartSettings::get('enable_tipping');
    }

    public function tippingAmounts()
    {
        $result = [];

        $tipValueType = CartSettings::get('tip_value_type', 'F');
        $amounts = (array)CartSettings::get('tip_amounts', []);

        $amounts = sort_array($amounts, 'priority');

        foreach ($amounts as $index => $amount) {
            $amount['valueType'] = $tipValueType;
            $result[$index] = (object)$amount;
        }

        return $result;
    }

    public function tippingSelectedAmount()
    {
        return optional($this->cartManager->getCart()->getCondition('tip'))->getMetaData('amount', 0) ?? 0;
    }

    public function tippingSelectedType()
    {
        return optional($this->cartManager->getCart()->getCondition('tip'))->getMetaData('amountType');
    }

    public function getOptionQuantityTypeValue($cartItem, $optionValue)
    {
        $value = 0;
        $menuOptionValueId = $optionValue->menu_option_value_id;
        if ($cartItem && $cartItem->hasOptionValue($menuOptionValueId)) {
            $cartItem->options->search(function ($option) use ($menuOptionValueId, &$value) {
                $option->values->each(function ($opt) use ($menuOptionValueId, &$value) {
                    if ($opt->id == $menuOptionValueId) {
                        $value = $opt->qty;
                    }
                });
            });
        }

        return $value;
    }
}
