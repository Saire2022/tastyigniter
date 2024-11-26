<?php

namespace Igniter\User\Components;

use Admin\Models\Customer_groups_model;
use Admin\Models\Customers_model;
use Admin\Traits\ValidatesForm;
use Exception;
use Igniter\Flame\Cart\Facades\Cart;
use Igniter\Flame\Exception\ApplicationException;
use Igniter\Flame\Exception\ValidationException;
use Igniter\User\ActivityTypes\CustomerRegistered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Main\Facades\Auth;

class Account extends \System\Classes\BaseComponent
{
    use ValidatesForm;
    use \Main\Traits\UsesPage;

    public function defineProperties()
    {
        return [
            'accountPage' => [
                'label' => 'The customer dashboard page',
                'type' => 'select',
                'default' => 'account'.DIRECTORY_SEPARATOR.'account',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'addressPage' => [
                'label' => 'The customer address page',
                'type' => 'select',
                'default' => 'account'.DIRECTORY_SEPARATOR.'address',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'ordersPage' => [
                'label' => 'The customer orders page',
                'type' => 'select',
                'default' => 'account'.DIRECTORY_SEPARATOR.'orders',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'reservationsPage' => [
                'label' => 'The customer reservations page',
                'type' => 'select',
                'default' => 'account'.DIRECTORY_SEPARATOR.'reservations',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'reviewsPage' => [
                'label' => 'The customer reviews page',
                'type' => 'select',
                'default' => 'account'.DIRECTORY_SEPARATOR.'reviews',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'inboxPage' => [
                'label' => 'The customer inbox page',
                'type' => 'select',
                'default' => 'account'.DIRECTORY_SEPARATOR.'inbox',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'loginPage' => [
                'label' => 'The account login page',
                'type' => 'select',
                'default' => 'account'.DIRECTORY_SEPARATOR.'login',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'activationPage' => [
                'label' => 'The account registration activation page',
                'type' => 'select',
                'default' => 'account'.DIRECTORY_SEPARATOR.'register',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'agreeRegistrationTermsPage' => [
                'label' => 'Registration Terms',
                'type' => 'select',
                'options' => [static::class, 'getStaticPageOptions'],
                'placeholder' => 'lang:admin::lang.text_please_select',
                'comment' => 'Require customers to agree to terms before an account is registered',
                'validationRule' => 'integer',
            ],
            'redirectPage' => [
                'label' => 'Page to redirect to after successful login or registration',
                'type' => 'select',
                'default' => DIRECTORY_SEPARATOR.'tables',
                'options' => [static::class, 'getThemePageOptions'],
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
        ];
    }

    public function onRun()
    {
        if ($code = $this->getActivationCode())
            $this->onActivate($code);

        $this->prepareVars();

        if ($this->page->getId() === $this->property('loginPage'))
            $this->setIntendedUrl();
    }

    public function prepareVars()
    {
        $this->page['accountPage'] = $this->property('accountPage');
        $this->page['detailsPage'] = $this->property('detailsPage');
        $this->page['addressPage'] = $this->property('addressPage');
        $this->page['ordersPage'] = $this->property('ordersPage');
        $this->page['reservationsPage'] = $this->property('reservationsPage');
        $this->page['reviewsPage'] = $this->property('reviewsPage');
        $this->page['inboxPage'] = $this->property('inboxPage');
        $this->page['requireRegistrationTerms'] = (bool)$this->property('agreeRegistrationTermsPage');
        $this->page['canRegister'] = (bool)setting('allow_registration', true);

        $this->page['customer'] = $this->customer();
    }

    public function cartCount()
    {
        return Cart::count();
    }

    public function cartTotal()
    {
        return Cart::total();
    }

    public function getRegistrationTermsPageSlug()
    {
        return $this->getStaticPagePermalink($this->property('agreeRegistrationTermsPage'));
    }

    public function getRegistrationTermsUrl()
    {
        return url($this->getRegistrationTermsPageSlug());
    }

    public function customer()
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::getUser();
    }

    public function getCustomerOrders()
    {
        return $this->customer()->orders()->with('status')->take(10)->get();
    }

    public function getCustomerReservations()
    {
        return $this->customer()->reservations()->with('status')->take(10)->get();
    }

    public function onLogin()
    {
        try {
            $namedRules = [
                //['email', 'lang:igniter.user::default.settings.label_email', 'required|email:filter|max:96'],
                //['password', 'lang:igniter.user::default.login.label_password', 'required|min:8|max:40'],
                ['identification', 'identification', 'required|min:10'],
                ['remember', 'lang:igniter.user::default.login.label_remember', 'integer'],
            ];

            $this->validate(post(), $namedRules);

            $remember = (bool)post('remember');
            $credentials = [
                //'email' => post('email'),
                //'password' => post('password'),
                'identification' => post('identification'),
            ];
            //dd($this);
            $identification = post('identification');

            Event::fire('igniter.user.beforeAuthenticate', [$this, $credentials]);
;
            $user = Customers_model::authenticateWithIdentification($identification);

            if (!$user) {
                throw new ApplicationException(lang('igniter.user::default.login.alert_invalid_login'));
            }
            Auth::login($user, $remember);

            session()->regenerate();

            Event::fire('igniter.user.login', [$this], true);

            if ($redirect = input('redirect'))
                return Redirect::to($this->controller->pageUrl($redirect));

            if ($redirectUrl = $this->controller->pageUrl($this->property('redirectPage')))
                return Redirect::intended($redirectUrl);
        } catch (ValidationException $ex) {
            throw new ApplicationException(implode(PHP_EOL, $ex->getErrors()->all()));
        }
    }

    public function onRegister()
    {
        try {
            if (!(bool)setting('allow_registration', true))
                throw new ApplicationException(lang('igniter.user::default.login.alert_registration_disabled'));

            $data = post();

            $rules = [
                ['first_name', 'lang:igniter.user::default.settings.label_first_name', 'required|between:1,48'],
                ['last_name', 'lang:igniter.user::default.settings.label_last_name', 'required|between:1,48'],
                ['email', 'lang:igniter.user::default.settings.label_email', 'required|email:filter|max:96|unique:customers,email'],
                ['identification', 'identification', 'required|max:10|unique:customers,identification'],
                //['password', 'lang:igniter.user::default.login.label_password', 'required|min:6|max:32|same:password_confirm'],
                //['password_confirm', 'lang:igniter.user::default.login.label_password_confirm', 'required'],
                ['telephone', 'lang:igniter.user::default.settings.label_telephone', 'required'],
                ['newsletter', 'lang:igniter.user::default.login.label_subscribe', 'integer'],
            ];

            //if (strlen($this->getRegistrationTermsPageSlug()))
              //  $rules[] = ['terms', 'lang:igniter.user::default.login.label_i_agree', 'required|integer'];

            $this->validate($data, $rules);

            //dd($data);
            Event::fire('igniter.user.beforeRegister', [&$data]);

            $data['status'] = true;

            $customerGroup = Customer_groups_model::getDefault();
            $data['customer_group_id'] = $customerGroup->getKey();
            $requireActivation = ($customerGroup && $customerGroup->requiresApproval());
            $autoActivation = !$requireActivation;

            $customer = Auth::register(
                array_except($data, ['password_confirm', 'terms']), $autoActivation
            );

            Event::fire('igniter.user.register', [$customer, $data]);
            //if (strlen($this->getRegistrationTermsPageSlug()))

            $redirectUrl = $this->controller->pageUrl($this->property('redirectPage'));

            if ($requireActivation) {            //if (strlen($this->getRegistrationTermsPageSlug()))

                $this->sendActivationEmail($customer);
                flash()->success(lang('igniter.user::default.login.alert_account_activation'));
                $redirectUrl = $this->controller->pageUrl($this->property('loginPage'));
            }

            if (!$requireActivation) {
                $this->sendRegistrationEmail($customer);
                Auth::login($customer);
                flash()->success(lang('igniter.user::default.login.alert_account_created'));
            }

            CustomerRegistered::log($customer);

            if ($redirectUrl = get('redirect', $redirectUrl))
                return Redirect::intended($redirectUrl);
        } catch (ValidationException $ex) {
            throw new ApplicationException(implode(PHP_EOL, $ex->getErrors()->all()));
        }
    }

    public function onUpdate()
    {
        if (!$customer = $this->customer())
            return;

        try {
            $data = post();

            $rules = [
                ['first_name', 'lang:igniter.user::default.settings.label_first_name', 'required|between:1,48'],
                ['last_name', 'lang:igniter.user::default.settings.label_last_name', 'required|between:1,48'],
                ['old_password', 'lang:igniter.user::default.settings.label_old_password', 'required_with:new_password'],
                ['new_password', 'lang:igniter.user::default.settings.label_password', 'required_with:old_password|min:8|max:40|same:confirm_new_password'],
                ['confirm_new_password', 'lang:igniter.user::default.settings.label_password_confirm', 'required_with:old_password'],
                ['telephone', 'lang:igniter.user::default.settings.label_telephone', 'required'],
                ['newsletter', 'lang:igniter.user::default.login.label_subscribe', 'integer'],
            ];

            $this->validateAfter(function ($validator) {
                if ($message = $this->passwordDoesNotMatch()) {
                    $validator->errors()->add('old_password', $message);
                }
            });

            $this->validate($data, $rules);

            $passwordChanged = false;
            if (strlen(post('old_password')) && strlen(post('new_password'))) {
                $data['password'] = post('new_password');
                $passwordChanged = true;
            }

            if (!array_key_exists('newsletter', $data))
                $data['newsletter'] = 0;

            $customer->fill(array_except($data, ['old_password', 'new_password', 'confirm_new_password']));
            $customer->save();

            if ($passwordChanged) {
                Auth::login($customer, true);
            }

            flash()->success(lang('igniter.user::default.settings.alert_updated_success'));

            return Redirect::back();
        } catch (Exception $ex) {
            flash()->warning($ex->getMessage());

            return Redirect::back()->withInput();
        }
    }

    public function onActivate($code = null)
    {
        try {
            $code = post('code', $code);

            $namedRules = [
                ['code', 'lang:igniter.user::default.login.label_activation', 'required'],
            ];

            $this->validate(['code' => $code], $namedRules);

            $customer = Customers_model::whereActivationCode($code)->first();
            if (!$customer || !$customer->completeActivation($code))
                throw new ApplicationException(lang('igniter.user::default.reset.alert_activation_failed'));

            $this->sendRegistrationEmail($customer);

            Auth::login($customer);

            $redirectUrl = $this->controller->pageUrl($this->property('accountPage'));

            return Redirect::to($redirectUrl);
        } catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->error($ex->getMessage());
        }
    }

    public function onDelete()
    {
        if (!$customer = $this->customer())
            return;

        $customer->delete();

        Auth::logout();

        flash()->success(lang('igniter.user::default.settings.alert_deleted_success'));

        return Redirect::to($this->controller->pageUrl($this->property('loginPage')));
    }

    public function getActivationCode()
    {
        $param = $this->property('paramCode');
        if ($param && $code = $this->param($param))
            return $code;

        return input('activate');
    }

    protected function sendRegistrationEmail($customer)
    {
        $data = [
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'account_login_link' => $this->controller->pageUrl($this->property('loginPage')),
        ];

        $settingRegistrationEmail = setting('registration_email');
        is_array($settingRegistrationEmail) || $settingRegistrationEmail = [];

        if (in_array('customer', $settingRegistrationEmail)) {
            Mail::queue('igniter.user::mail.registration', $data, function ($message) use ($customer) {
                $message->to($customer->email, $customer->name);
            });
        }

        if (in_array('admin', $settingRegistrationEmail)) {
            Mail::queue('igniter.user::mail.registration_alert', $data, function ($message) {
                $message->to(setting('site_email'), setting('site_name'));
            });
        }
    }

    protected function passwordDoesNotMatch()
    {
        if (!strlen($password = post('old_password')))
            return false;

        $credentials = ['password' => $password];
        if (!Auth::validateCredentials($this->customer(), $credentials)) {
            return 'Password does not match';
        }

        return false;
    }

    protected function sendActivationEmail($customer)
    {
        $link = $this->makeActivationUrl($customer->getActivationCode());
        $data = [
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'account_activation_link' => $link,
        ];

        Mail::queue('igniter.user::mail.activation', $data, function ($message) use ($customer) {
            $message->to($customer->email, $customer->name);
        });
    }

    protected function makeActivationUrl($code)
    {
        $params = [
            $this->property('paramName') => $code,
        ];

        $url = ($pageName = $this->property('activationPage'))
            ? $this->controller->pageUrl($pageName, $params)
            : $this->controller->currentPageUrl($params);

        if (strpos($url, $code) === false) {
            $url .= '?activate='.$code;
        }

        return $url;
    }

    protected function setIntendedUrl()
    {
        $previousUrl = url()->previous();
        if (!session()->has('url.intended') && $previousUrl && $previousUrl !== url()->current() && str_starts_with($previousUrl, url('/'))) {
            session(['url.intended' => $previousUrl]);
        }
    }
}
