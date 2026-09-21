<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Welcome\WelcomeController;
use App\Http\Controllers\Welcome\CartController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\MenusController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\EcommerceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/',[WelcomeController::class,'index'])->name('index');

Route::get('/search',[WelcomeController::class,'search'])->name('search');
Route::post('/contact-mail',[WelcomeController::class,'contactMail'])->name('contactMail');
Route::get('/subscribe/{email}',[WelcomeController::class,'subscribe'])->name('subscribe');

Route::get('/geo/filter/{id}',[WelcomeController::class,'geo_filter'])->name('geo_filter');
Route::get('/payments/filter/{id}',[WelcomeController::class,'payment_filter'])->name('payment_filter');

Route::get('/promotion/{id}',[WelcomeController::class,'promotion'])->name('promotion');


Route::get('/product/category/{slug}',[WelcomeController::class,'productCategory'])->name('productCategory');
Route::get('/product/{slug}',[WelcomeController::class,'productView'])->name('productView');

Route::get('/product-search',[WelcomeController::class,'productSearch'])->name('productSearch');



Route::get('/blog/category/{slug}',[WelcomeController::class,'blogCategory'])->name('blogCategory');
Route::get('/blog/author/{id}/{slug}',[WelcomeController::class,'blogAuthor'])->name('blogAuthor');
Route::get('/blog/tag/{slug}',[WelcomeController::class,'blogTag'])->name('blogTag');
Route::get('/blog/search',[WelcomeController::class,'blogSearch'])->name('blogSearch');
Route::get('/blog/{slug}',[WelcomeController::class,'blogView'])->name('blogView');

Route::post('/blog-comments/{id}',[WelcomeController::class,'blogComments'])->name('blogComments');

//Auth Route Start

Route::any('/login',[AuthController::class,'login'])->name('login');
Route::any('/forgot-password',[AuthController::class,'forgotPassword'])->name('forgotPassword');
Route::get('/reset-password/{token}',[AuthController::class,'resetPassword'])->name('resetPassword');
Route::post('/reset-password-check',[AuthController::class,'resetPasswordCheck'])->name('resetPasswordCheck');
Route::any('/register',[AuthController::class,'register'])->name('register');
Route::post('/log-out',[AuthController::class,'logout'])->name('logout');

Route::any('add-to-cart/{id}',[CartController::class,'addToCart'])->name('addToCart');
Route::get('change-to-cart/{product}/{type}',[CartController::class,'changeToCart'])->name('changeToCart');
Route::get('select-delivery-area/{id}',[CartController::class,'selectDeliveryArea'])->name('selectDeliveryArea');

Route::get('carts',[CartController::class,'carts'])->name('carts');
Route::get('checkout',[CartController::class,'checkout'])->name('checkout');
Route::post('checkout-post',[CartController::class,'checkoutPost'])->name('checkoutPost');
Route::get('order-payment/{id}',[CartController::class,'orderPayment'])->name('orderPayment')->middleware('auth');;
Route::get('order-payment-send/{type}/{id}',[CartController::class,'orderPaymentSend'])->name('orderPaymentSend')->middleware('auth');;
Route::any('OrderTrack',[CartController::class,'OrderTrack'])->name('OrderTrack');
Route::get('order-invoice/{invoice}',[CartController::class,'invoiceView'])->name('invoiceView');

Route::get('wishlist-compare/update/{id}/{type}',[CartController::class,'wishlistCompareUpdate'])->name('wishlistCompareUpdate');
Route::get('my-wishlist',[CartController::class,'myWishlist'])->name('myWishlist');
Route::get('my-compare',[CartController::class,'myCompare'])->name('myCompare');
Route::post('my-coupon-apply}',[CartController::class,'couponApply'])->name('couponApply');
Route::post('order-now/{id}',[CartController::class,'orderNow'])->name('orderNow');

Route::get('/{slug}',[WelcomeController::class,'pageView'])->name('pageView');

//Customer Route Group Start

Route::group(['prefix'=>'customer', 'as'=>'customer.','middleware'=>['auth','role:customer']], function(){

Route::get('/dashboard',[CustomerController::class,'dashboard'])->name('dashboard');

Route::get('/profile-edit',[CustomerController::class,'profileEdit'])->name('profileEdit');
Route::post('/profile-update',[CustomerController::class,'profileUpdate'])->name('profileUpdate');
Route::get('/profile-verify-update/{type}',[CustomerController::class,'profileVerifyUpdate'])->name('profileVerifyUpdate');
Route::get('/profile-update-verify-code/{data}',[CustomerController::class,'profileUpdateVerifyCode'])->name('profileUpdateVerifyCode');
Route::post('/profile-verify-update-post',[CustomerController::class,'profileVerifyUpdatePost'])->name('profileVerifyUpdatePost');
Route::post('/profile-update',[CustomerController::class,'profileUpdate'])->name('profileUpdate');


Route::get('/change-password',[CustomerController::class,'changePassword'])->name('changePassword');
Route::post('/change-password',[CustomerController::class,'changePasswordUpdate'])->name('changePasswordUpdate');

Route::get('/my-orders',[CustomerController::class,'myOrders'])->name('myOrders');
Route::get('/return-cancellations-orders',[CustomerController::class,'returnCancellations'])->name('returnCancellations');
Route::get('/orders-details/{id}',[CustomerController::class,'orderDetails'])->name('orderDetails');
Route::get('/orders-pdf/{id}',[CustomerController::class,'orderDetailsPDf'])->name('orderDetailsPDf');
Route::get('/orders-cancel/{id}',[CustomerController::class,'orderCancel'])->name('orderCancel');
Route::post('/orders-cancel/post/{id}',[CustomerController::class,'orderCancelPost'])->name('orderCancelPost');
Route::get('/orders-return/{id}',[CustomerController::class,'orderReturn'])->name('orderReturn');
Route::post('/orders-return/post/{id}',[CustomerController::class,'orderReturnPost'])->name('orderReturnPost');
Route::get('/my-reviews',[CustomerController::class,'myReviews'])->name('myReviews');
Route::get('/order-review/{id}',[CustomerController::class,'orderReview'])->name('orderReview');
Route::post('/order-review/post/{id}',[CustomerController::class,'orderReviewPost'])->name('orderReviewPost');

Route::get('/my-balance',[CustomerController::class,'myBalance'])->name('myBalance');
Route::post('/add-balance',[CustomerController::class,'addBalanceToWallet'])->name('addBalanceToWallet');

});


// Admin Route Group Start

Route::group(['prefix'=>'admin', 'as'=>'admin.','middleware'=>['auth','role:admin','permission']], function(){


Route::get('/dashboard',[AdminController::class,'dashboard'])->name('dashboard');
Route::get('/data',[AdminController::class,'dashboardData'])->name('dashboardData');
Route::get('/data-reaction',[AdminController::class,'dashboardDataReaction'])->name('dashboardDataReaction');

Route::get('/my-profile',[AdminController::class,'myProfile'])->name('myProfile');
Route::post('/my-profile/update',[AdminController::class,'myProfileUpdate'])->name('myProfileUpdate');
Route::post('/my-profile/change-password',[AdminController::class,'myProfileChangePassword'])->name('myProfileChangePassword');


// Medies Library Route
Route::get('/medies',[AdminController::class,'medies'])->name('medies');
Route::post('/medies/create',[AdminController::class,'mediesCreate'])->name('mediesCreate');
Route::get('/medies/edit/{id}',[AdminController::class,'mediesEdit'])->name('mediesEdit');
Route::post('/medies/update/{id}',[AdminController::class,'mediesUpdate'])->name('mediesUpdate');
Route::post('/medies/deleteall',[AdminController::class,'mediesDeleteAll'])->name('mediesDeleteAll');
Route::get('/medies/delete/{id}',[AdminController::class,'mediesDelete'])->name('mediesDelete');
// Medies Library Route End


//Page Management
Route::get('/pages',[AdminController::class,'pages'])->name('pages');
Route::get('/pages/create',[AdminController::class,'pagesCreate'])->name('pagesCreate');
Route::get('/pages/edit/{id}',[AdminController::class,'pagesEdit'])->name('pagesEdit');
Route::post('/pages/update/{id}',[AdminController::class,'pagesUpdate'])->name('pagesUpdate');
Route::get('/pages/delete/{id}',[AdminController::class,'pagesDelete'])->name('pagesDelete');
//Page Management End

// Posts Route
Route::get('/posts',[PostsController::class,'posts'])->name('posts');
Route::get('/posts/create',[PostsController::class,'postsCreate'])->name('postsCreate');
Route::get('/posts/edit/{id}',[PostsController::class,'postsEdit'])->name('postsEdit');
Route::post('/posts/update/{id}',[PostsController::class,'postsUpdate'])->name('postsUpdate');
Route::get('/posts/delete/{id}',[PostsController::class,'postsDelete'])->name('postsDelete');
// Posts Route End

// Posts Comments Route End
Route::get('/posts/comments/all',[PostsController::class,'postsCommentsAll'])->name('postsCommentsAll');
Route::get('/posts/comments/post/{id}',[PostsController::class,'postsComments'])->name('postsComments');
Route::get('/posts/comments/create/{id}',[PostsController::class,'postsCommentsCreate'])->name('postsCommentsCreate');
Route::get('/posts/comments/edit/{id}',[PostsController::class,'postsCommentsEdit'])->name('postsCommentsEdit');
Route::post('/posts/comments/update/{id}',[PostsController::class,'postsCommentsUpdate'])->name('postsCommentsUpdate');
Route::get('/posts/comments/delete/{id}',[PostsController::class,'postsCommentsDelete'])->name('postsCommentsDelete');
Route::get('/posts/comments/replay/{id}',[PostsController::class,'postsCommentsReplay'])->name('postsCommentsReplay');
Route::post('/posts/comments/replay/post/{id}',[PostsController::class,'postsCommentsReplayPost'])->name('postsCommentsReplayPost');
Route::get('/posts/comments/status/{id}',[PostsController::class,'postsCommentsStatus'])->name('postsCommentsStatus');
// Posts Comments Route End

// Posts Categories Route
Route::get('/posts/categories',[PostsController::class,'postsCategories'])->name('postsCategories');
Route::get('/posts/categories/create',[PostsController::class,'postsCategoriesCreate'])->name('postsCategoriesCreate');
Route::get('/posts/categories/edit/{id}',[PostsController::class,'postsCategoriesEdit'])->name('postsCategoriesEdit');
Route::post('/posts/categories/update/{id}',[PostsController::class,'postsCategoriesUpdate'])->name('postsCategoriesUpdate');
Route::get('/posts/categories/delete/{id}',[PostsController::class,'postsCategoriesDelete'])->name('postsCategoriesDelete');
// Posts Categories Route End

// Posts Tags Route
Route::get('/posts/tags',[PostsController::class,'postsTags'])->name('postsTags');
Route::get('/posts/tags/Create',[PostsController::class,'postsTagsCreate'])->name('postsTagsCreate');
Route::get('/posts/tags/edit/{id}',[PostsController::class,'postsTagsEdit'])->name('postsTagsEdit');
Route::post('/posts/tags/update/{id}',[PostsController::class,'postsTagsUpdate'])->name('postsTagsUpdate');
Route::get('/posts/tags/delete/{id}',[PostsController::class,'postsTagsDelete'])->name('postsTagsDelete');
// Posts Tags Route End

//Ecommerce Mangement Start

//Products Management
Route::get('/products',[EcommerceController::class,'products'])->name('products');
Route::get('/products/create',[EcommerceController::class,'productsCreate'])->name('productsCreate');
Route::get('/products/edit/{id}',[EcommerceController::class,'productsEdit'])->name('productsEdit');
Route::get('/products/view/{id}',[EcommerceController::class,'productsView'])->name('productsView');
Route::post('/products/update/{id}',[EcommerceController::class,'productsUpdate'])->name('productsUpdate');
Route::any('/products/update/ajax/{column}/{id}',[EcommerceController::class,'productsUpdateAjax'])->name('productsUpdateAjax');
Route::get('/products/delete/{id}',[EcommerceController::class,'productsDelete'])->name('productsDelete');

Route::any('/products/singleview/{id}',[EcommerceController::class,'productsSingleView'])->name('productsSingleView');
//Products Management End

//Purchase Products Management
Route::get('/purchase/products',[OrdersController::class,'purchaseProducts'])->name('purchaseProducts');
Route::get('/purchase/products/edit/{id}',[OrdersController::class,'purchaseProductsEdit'])->name('purchaseProductsEdit');
Route::post('/purchase/products/update/{id}',[OrdersController::class,'purchaseProductsUpdate'])->name('purchaseProductsUpdate');
Route::get('/purchase/products/invoice/{id}',[OrdersController::class,'purchaseProductsInvoice'])->name('purchaseProductsInvoice');
Route::get('/purchase/products/delete/{id}',[OrdersController::class,'purchaseProductsDelete'])->name('purchaseProductsDelete');

//Purchase Products Management


// Products Categories Route
Route::get('/products/categories',[EcommerceController::class,'productsCategories'])->name('productsCategories');
Route::get('/products/categories/create',[EcommerceController::class,'productsCategoriesCreate'])->name('productsCategoriesCreate');
Route::get('/products/categories/edit/{id}',[EcommerceController::class,'productsCategoriesEdit'])->name('productsCategoriesEdit');
Route::post('/products/categories/update/{id}',[EcommerceController::class,'productsCategoriesUpdate'])->name('productsCategoriesUpdate');
Route::get('/products/categories/delete/{id}',[EcommerceController::class,'productsCategoriesDelete'])->name('productsCategoriesDelete');
// Products Categories Route End

// Products Tags Route
Route::get('/products/tags',[EcommerceController::class,'productsTags'])->name('productsTags');
Route::get('/products/tags/create',[EcommerceController::class,'productsTagsCreate'])->name('productsTagsCreate');
Route::get('/products/tags/edit/{id}',[EcommerceController::class,'productsTagsEdit'])->name('productsTagsEdit');
Route::post('/products/tags/update/{id}',[EcommerceController::class,'productsTagsUpdate'])->name('productsTagsUpdate');
Route::get('/products/tags/delete/{id}',[EcommerceController::class,'productsTagsDelete'])->name('productsTagsDelete');
// Products Tags Route End

// Products Attributes Route
Route::get('/products/attributes',[EcommerceController::class,'productsAttributes'])->name('productsAttributes');
Route::get('/products/attributes/create',[EcommerceController::class,'productsAttributesCreate'])->name('productsAttributesCreate');
Route::get('/products/attributes/config/{id}',[EcommerceController::class,'productsAttributesEdit'])->name('productsAttributesEdit');
Route::post('/products/attributes/update/{id}',[EcommerceController::class,'productsAttributesUpdate'])->name('productsAttributesUpdate');
Route::get('/products/attributes/delete/{id}',[EcommerceController::class,'productsAttributesDelete'])->name('productsAttributesDelete');

Route::get('/products/attributes/item-add/{id}',[EcommerceController::class,'productsAttributesItemAdd'])->name('productsAttributesItemAdd');
Route::get('/products/attributes/item-edit/{id}',[EcommerceController::class,'productsAttributesItemEdit'])->name('productsAttributesItemEdit');
Route::post('/products/attributes/item-update/{id}',[EcommerceController::class,'productsAttributesItemUpdate'])->name('productsAttributesItemUpdate');
// Products Attributes Route End

// Ecommerce Setting Route
Route::get('/ecommerce/coupons',[EcommerceController::class,'ecommerceCoupons'])->name('ecommerceCoupons');
Route::get('/ecommerce/promotions',[EcommerceController::class,'ecommercePromotions'])->name('ecommercePromotions');
Route::get('/ecommerce/setting/{type}',[EcommerceController::class,'ecommerceSetting'])->name('ecommerceSetting');

Route::get('/ecommerce/setting/{type}/create',[EcommerceController::class,'ecommerceSettingCreate'])->name('ecommerceSettingCreate');
Route::post('/ecommerce/setting/{type}/post',[EcommerceController::class,'ecommerceSettingPost'])->name('ecommerceSettingPost');
Route::get('/ecommerce/setting/{type}/edit/{id}',[EcommerceController::class,'ecommerceSettingEdit'])->name('ecommerceSettingEdit');
Route::post('/ecommerce/setting/{type}/update/{id}',[EcommerceController::class,'ecommerceSettingUpdate'])->name('ecommerceSettingUpdate');

// Ecommerce Setting Route End


//Stock Manage Route
Route::get('/stock/list',[EcommerceController::class,'stockList'])->name('stockList');

//Stock Manage Route End

// POS Order Route Start
Route::get('/pos-invoice/{id}',[OrdersController::class,'posInvoice'])->name('posInvoice');
Route::get('/pos-orders/create',[OrdersController::class,'posOrdersCreate'])->name('posOrdersCreate');

Route::get('/pos-orders/invoice/{id}',[OrdersController::class,'posOrdersInvoice'])->name('posOrdersInvoice');

Route::get('/pos-orders/delete/{id}',[OrdersController::class,'posOrdersDelete'])->name('posOrdersDelete');

Route::get('/pos-orders/{status?}',[OrdersController::class,'posOrders'])->name('posOrders');
Route::any('/pos-orders/update/{id}',[OrdersController::class,'posOrdersManageUpdate'])->name('posOrdersManageUpdate');
Route::any('/pos-orders/payments/{id}',[OrdersController::class,'posOrdersPaymentsUpdate'])->name('posOrdersPaymentsUpdate');
Route::post('/pos-orders/return/{id}',[OrdersController::class,'posOrdersReturnUpdate'])->name('posOrdersReturnUpdate');
// POS Order Route Start

// Order Management Route End
Route::get('/invoice/{id}',[OrdersController::class,'invoice'])->name('invoice');
Route::get('order-multi-invoices',[OrdersController::class,'multiInvoicesView'])->name('multiInvoicesView');
Route::get('/orders/{status?}',[OrdersController::class,'orders'])->name('orders');
Route::get('/orders/manage/{id}',[OrdersController::class,'ordersManage'])->name('ordersManage');

Route::post('/orders/update/{id}',[OrdersController::class,'ordersManageUpdate'])->name('ordersManageUpdate');
Route::post('/orders/payments/{id}',[OrdersController::class,'ordersPaymentsUpdate'])->name('ordersPaymentsUpdate');
Route::post('/orders/return/{id}',[OrdersController::class,'ordersReturnUpdate'])->name('ordersReturnUpdate');
// Order Management Route End


// Return Order Route Start
Route::get('/return-orders/{status?}',[OrdersController::class,'returnOrders'])->name('returnOrders');
Route::get('/return-orders/manage/{id}',[OrdersController::class,'returnOrdersManage'])->name('returnOrdersManage');
Route::post('/return-orders/update/{id}',[OrdersController::class,'returnOrdersUpdate'])->name('returnOrdersUpdate');
Route::post('/return-orders/refund/{id}',[OrdersController::class,'returnOrdersRefund'])->name('returnOrdersRefund');
// Return Order Route End

// Coin Management  Route
Route::get('/point-management/users',[OrdersController::class,'coinUsers'])->name('coinUsers');

Route::any('/point-management/setting',[OrdersController::class,'coinSetting'])->name('coinSetting');

//  Coin Management Route End

// Expenses Start
Route::any('/expenses/list',[AdminController::class,'expensesList'])->name('expensesList');
Route::any('/expenses/list/{id}/{type}',[AdminController::class,'expensesListManage'])->name('expensesListManage');

Route::any('/expenses/list',[AdminController::class,'expensesList'])->name('expensesList');

Route::any('/expenses/types/',[AdminController::class,'expensesTypes'])->name('expensesTypes');
Route::any('/expenses/types/{id}/{type?}',[AdminController::class,'expensesTypesManage'])->name('expensesTypesManage');
// Expenses Start


// Accounts History Start
Route::any('/accounts/list',[AdminController::class,'accountsList'])->name('accountsList');
Route::any('/accounts/list/{type}/{id}',[AdminController::class,'accountsEdit'])->name('accountsEdit');
Route::any('/accounts/transfer',[AdminController::class,'accountsTransfer'])->name('accountsTransfer');
Route::any('/accounts/transfer/{type}/{id}',[AdminController::class,'accountsTransferUpdate'])->name('accountsTransferUpdate');
Route::any('/accounts/balance',[AdminController::class,'accountsBalance'])->name('accountsBalance');


// Payment History Start
Route::get('/accounts/customer-payments',[AdminController::class,'customerPayments'])->name('customerPayments');
Route::get('/accounts/recharge-payments',[AdminController::class,'rechargePayments'])->name('rechargePayments');
Route::get('/accounts/refund-payments',[AdminController::class,'refoundPayments'])->name('refoundPayments');
// Payment History End

// Reports Route Start
Route::get('/reports/{type}',[AdminController::class,'reportsAll'])->name('reportsAll');
// Reports Route End

// Posts Route
Route::get('/clients',[AdminController::class,'clients'])->name('clients');
Route::get('/clients/create',[AdminController::class,'clientsCreate'])->name('clientsCreate');
Route::get('/clients/edit/{id}',[AdminController::class,'clientsEdit'])->name('clientsEdit');
Route::post('/clients/update/{id}',[AdminController::class,'clientsUpdate'])->name('clientsUpdate');
Route::get('/clients/delete/{id}',[AdminController::class,'clientsDelete'])->name('clientsDelete');
// Posts Route End

// Brands Route
Route::get('/brands',[AdminController::class,'brands'])->name('brands');
Route::get('/brands/create',[AdminController::class,'brandsCreate'])->name('brandsCreate');
Route::get('/brands/edit/{id}',[AdminController::class,'brandsEdit'])->name('brandsEdit');
Route::post('/brands/update/{id}',[AdminController::class,'brandsUpdate'])->name('brandsUpdate');
Route::get('/brands/delete/{id}',[AdminController::class,'brandsDelete'])->name('brandsDelete');
// Brands Route End

// Slider Route
Route::get('/sliders',[AdminController::class,'sliders'])->name('sliders');
Route::get('/sliders/create',[AdminController::class,'slidersCreate'])->name('slidersCreate');
Route::get('/sliders/edit/{id}',[AdminController::class,'slidersEdit'])->name('slidersEdit');
Route::post('/sliders/update/{id}',[AdminController::class,'slidersUpdate'])->name('slidersUpdate');
Route::get('/sliders/delete/{id}',[AdminController::class,'slidersDelete'])->name('slidersDelete');

Route::get('/sliders/slide/create/{id}',[AdminController::class,'slideCreate'])->name('slideCreate');
Route::get('/sliders/slide/edit/{id}',[AdminController::class,'slideEdit'])->name('slideEdit');
Route::post('/sliders/slide/update/{id}',[AdminController::class,'slideUpdate'])->name('slideUpdate');
Route::post('/sliders/slide/drug/{id}',[AdminController::class,'slideDrug'])->name('slideDrug');
Route::get('/sliders/slide-ajax/{id}',[AdminController::class,'slideAjax'])->name('slideAjax');
Route::get('/sliders/slide/delete/{id}',[AdminController::class,'slideDelete'])->name('slideDelete');

// Slider Route End

// Gallery Route
Route::get('/galleries',[AdminController::class,'galleries'])->name('galleries');
Route::get('/galleries/create',[AdminController::class,'galleriesCreate'])->name('galleriesCreate');
Route::get('/galleries/edit/{id}',[AdminController::class,'galleriesEdit'])->name('galleriesEdit');
Route::post('/galleries/update/{id}',[AdminController::class,'galleriesUpdate'])->name('galleriesUpdate');
Route::get('/galleries/delete/{id}',[AdminController::class,'galleriesDelete'])->name('galleriesDelete');

Route::post('/galleries/images/create/{id}',[AdminController::class,'galleriesImagesCreate'])->name('galleriesImagesCreate');
Route::post('/galleries/images/update/{id}',[AdminController::class,'galleriesImagesUpdate'])->name('galleriesImagesUpdate');
// Gallery Route End


// Theme Route
Route::get('/theme-setting',[AdminController::class,'themeSetting'])->name('themeSetting');
Route::get('/theme-setting/edit/{id}',[AdminController::class,'themeSettingEdit'])->name('themeSettingEdit');
Route::any('/theme-setting/update/{id}',[AdminController::class,'themeSettingUpdate'])->name('themeSettingUpdate');
// Theme Route End

// Menus Route
Route::get('/menus',[MenusController::class,'menus'])->name('menus');
Route::get('/menus/create',[MenusController::class,'menusCreate'])->name('menusCreate');
Route::get('/menus/config/{id}',[MenusController::class,'menusEdit'])->name('menusEdit');
Route::post('/menus/update/{id}',[MenusController::class,'menusUpdate'])->name('menusUpdate');
Route::get('/menus/delete/{id}',[MenusController::class,'menusDelete'])->name('menusDelete');

Route::get('/menus/items-ajax/{id}',[MenusController::class,'menusItemsAjax'])->name('menusItemsAjax');
Route::post('/menus/items/post/{id}',[MenusController::class,'menusItemsPost'])->name('menusItemsPost');
Route::get('/menus/items/edit/{id}',[MenusController::class,'menusItemsEdit'])->name('menusItemsEdit');
Route::post('/menus/items/update/{id}',[MenusController::class,'menusItemsUpdate'])->name('menusItemsUpdate');
Route::get('/menus/items/delete/{id}',[MenusController::class,'menusItemsDelete'])->name('menusItemsDelete');
// Menus Route End


//User Management
Route::get('/users/admin/',[AdminController::class,'usersAdmin'])->name('usersAdmin');
Route::post('/users/admin/add',[AdminController::class,'usersAdminAdd'])->name('usersAdminAdd');
Route::get('/users/admin/edit/{id}/{type}',[AdminController::class,'usersAdminEdit'])->name('usersAdminEdit');
Route::post('/users/admin/update/{id}/{type}',[AdminController::class,'usersAdminUpdate'])->name('usersAdminUpdate');
Route::get('/users/admin/delete/{id}',[AdminController::class,'usersAdminDelete'])->name('usersAdminDelete');

Route::get('/users/customer/',[AdminController::class,'usersCustomer'])->name('usersCustomer');
Route::get('/users/customer/add',[AdminController::class,'usersCustomerAdd'])->name('usersCustomerAdd');
Route::post('/users/customer/post',[AdminController::class,'usersCustomerPost'])->name('usersCustomerPost');
Route::get('/users/customer/edit/{id}/{type}',[AdminController::class,'usersCustomerEdit'])->name('usersCustomerEdit');
Route::post('/users/customer/update/{id}/{type}',[AdminController::class,'usersCustomerUpdate'])->name('usersCustomerUpdate');
Route::get('/users/customer/delete/{id}',[AdminController::class,'usersCustomerDelete'])->name('usersCustomerDelete');


Route::get('/users/role/list',[AdminController::class,'userRoles'])->name('userRoles');
Route::any('/users/role/{action}/{id}',[AdminController::class,'userRoleAction'])->name('userRoleAction');


Route::get('/subscribes/',[AdminController::class,'subscribes'])->name('subscribes');

Route::any('/employee/users',[AdminController::class,'employeeUser'])->name('employeeUser');
Route::any('/employee/users/edit/{id}',[AdminController::class,'employeeUserEdit'])->name('employeeUserEdit');
Route::get('/employee/users/delete/{id}',[AdminController::class,'employeeUserDelete'])->name('employeeUserDelete');

Route::any('/suppliers/users',[AdminController::class,'supplierUsers'])->name('supplierUsers');
Route::any('/suppliers/users/edit/{id}',[AdminController::class,'supplierUsersEdit'])->name('supplierUsersEdit');
Route::get('/suppliers/users/delete/{id}',[AdminController::class,'supplierUsersDelete'])->name('supplierUsersDelete');



// Apps Setting
Route::get('/setting/{type}',[AdminController::class,'setting'])->name('setting');
Route::post('/setting/{type}/update',[AdminController::class,'setitngUpdate'])->name('setitngUpdate');

});