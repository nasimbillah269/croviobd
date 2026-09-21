<?php

namespace App\Traits;
use App\Models\Permission;
use Auth;
trait UserPermission{
	public function checkRequestPermission(){

		if($activerole =Permission::find(Auth::user()->permission_id)){
			if(
				

				empty(json_decode($activerole->permission, true)['posts']['list']) && \Route::is('admin.posts')||
				empty(json_decode($activerole->permission, true)['posts']['add']) && \Route::is('admin.postsCreate') ||
				empty(json_decode($activerole->permission, true)['posts']['add']) && \Route::is('admin.postsUpdate') ||
				empty(json_decode($activerole->permission, true)['posts']['delete']) && \Route::is('admin.postsDelete') ||

				empty(json_decode($activerole->permission, true)['postsCtg']['list']) && \Route::is('admin.postsCategories')||
				empty(json_decode($activerole->permission, true)['postsCtg']['add']) && \Route::is('admin.postsCategoriesCreate') ||
				empty(json_decode($activerole->permission, true)['postsCtg']['add']) && \Route::is('admin.postsCategoriesUpdate') ||
				empty(json_decode($activerole->permission, true)['postsCtg']['delete']) && \Route::is('admin.postsCategoriesDelete') ||


				empty(json_decode($activerole->permission, true)['postsTag']['list']) && \Route::is('admin.postsTags')||
				empty(json_decode($activerole->permission, true)['postsTag']['add']) && \Route::is('admin.postsTagsCreate') ||
				empty(json_decode($activerole->permission, true)['postsTag']['add']) && \Route::is('admin.postsTagsUpdate') ||
				empty(json_decode($activerole->permission, true)['postsTag']['delete']) && \Route::is('admin.postsTagsDelete') ||

				empty(json_decode($activerole->permission, true)['postsComment']['list']) && \Route::is('admin.postsCommentsAll')||
				empty(json_decode($activerole->permission, true)['postsComment']['list']) && \Request::is('posts/comments/post*') ||
				empty(json_decode($activerole->permission, true)['postsComment']['add']) && \Request::is('posts/comments/create*') ||
				empty(json_decode($activerole->permission, true)['postsComment']['edit']) && \Request::is('posts/comments/replay*') ||
				empty(json_decode($activerole->permission, true)['postsComment']['edit']) && \Request::is('posts/comments/status*') ||
				empty(json_decode($activerole->permission, true)['postsComment']['edit']) && \Request::is('posts/comments/update*') ||
				empty(json_decode($activerole->permission, true)['postsComment']['delete']) && \Request::is('posts/comments/delete*') ||

				empty(json_decode($activerole->permission, true)['pages']['list']) && \Route::is('admin.pages')||
				empty(json_decode($activerole->permission, true)['pages']['add']) && \Route::is('admin.pagesCreate') ||
				empty(json_decode($activerole->permission, true)['pages']['add']) && \Route::is('admin.pagesUpdate') ||
				empty(json_decode($activerole->permission, true)['pages']['delete']) && \Route::is('admin.pagesDelete') ||

				empty(json_decode($activerole->permission, true)['products']['list']) && \Route::is('admin.services')||
				empty(json_decode($activerole->permission, true)['products']['add']) && \Route::is('admin.productsCreate') ||
				empty(json_decode($activerole->permission, true)['products']['add']) && \Route::is('admin.productsUpdate') ||
				empty(json_decode($activerole->permission, true)['products']['delete']) && \Route::is('admin.productsDelete') ||

				empty(json_decode($activerole->permission, true)['productsCtg']['list']) && \Route::is('admin.productsCategories')||
				empty(json_decode($activerole->permission, true)['productsCtg']['add']) && \Route::is('admin.productsCategoriesCreate') ||
				empty(json_decode($activerole->permission, true)['productsCtg']['add']) && \Route::is('admin.productsCategoriesUpdate') ||
				empty(json_decode($activerole->permission, true)['productsCtg']['delete']) && \Route::is('admin.productsCategoriesDelete') ||

				empty(json_decode($activerole->permission, true)['medies']['list']) && \Route::is('admin.medies')||
				empty(json_decode($activerole->permission, true)['medies']['add']) && \Route::is('admin.mediesCreate') ||
				empty(json_decode($activerole->permission, true)['medies']['add']) && \Route::is('admin.mediesUpdate') ||
				empty(json_decode($activerole->permission, true)['medies']['delete']) && \Route::is('admin.mediesDelete') || 

				empty(json_decode($activerole->permission, true)['clients']['list']) && \Route::is('admin.clients')||
				empty(json_decode($activerole->permission, true)['clients']['add']) && \Route::is('admin.clientsCreate') ||
				empty(json_decode($activerole->permission, true)['clients']['add']) && \Route::is('admin.clientsUpdate') ||
				empty(json_decode($activerole->permission, true)['clients']['delete']) && \Route::is('admin.clientsDelete') ||

				empty(json_decode($activerole->permission, true)['brands']['list']) && \Route::is('admin.brands')||
				empty(json_decode($activerole->permission, true)['brands']['add']) && \Route::is('admin.brandsCreate') ||
				empty(json_decode($activerole->permission, true)['brands']['add']) && \Route::is('admin.brandsUpdate') ||
				empty(json_decode($activerole->permission, true)['brands']['delete']) && \Route::is('admin.brandsDelete') ||

				empty(json_decode($activerole->permission, true)['sliders']['list']) && \Route::is('admin.sliders')||
				empty(json_decode($activerole->permission, true)['sliders']['add']) && \Route::is('admin.slidersCreate') ||
				empty(json_decode($activerole->permission, true)['sliders']['add']) && \Route::is('admin.slidersUpdate') ||
				empty(json_decode($activerole->permission, true)['sliders']['add']) && \Route::is('admin.slideCreate') ||
				empty(json_decode($activerole->permission, true)['sliders']['add']) && \Route::is('admin.slideUpdate') ||
				empty(json_decode($activerole->permission, true)['sliders']['add']) && \Route::is('admin.slideDrug') ||
				empty(json_decode($activerole->permission, true)['sliders']['delete']) && \Route::is('admin.slidersDelete') ||

				empty(json_decode($activerole->permission, true)['galleries']['list']) && \Route::is('admin.galleries')||
				empty(json_decode($activerole->permission, true)['galleries']['add']) && \Route::is('admin.galleriesCreate') ||
				empty(json_decode($activerole->permission, true)['galleries']['add']) && \Route::is('admin.galleriesUpdate') ||
				empty(json_decode($activerole->permission, true)['galleries']['add']) && \Route::is('admin.galleriesImagesCreate') ||
				empty(json_decode($activerole->permission, true)['galleries']['add']) && \Route::is('admin.galleriesImagesUpdate') ||
				empty(json_decode($activerole->permission, true)['galleries']['delete']) && \Route::is('admin.galleriesDelete') ||

				empty(json_decode($activerole->permission, true)['menus']['list']) && \Route::is('admin.menus')||
				empty(json_decode($activerole->permission, true)['menus']['add']) && \Route::is('admin.menusCreate') ||
				empty(json_decode($activerole->permission, true)['menus']['add']) && \Route::is('admin.menusUpdate') ||
				empty(json_decode($activerole->permission, true)['menus']['delete']) && \Route::is('admin.menusItemsDelete') ||
				empty(json_decode($activerole->permission, true)['menus']['delete']) && \Route::is('admin.menusDelete') ||

				empty(json_decode($activerole->permission, true)['users']['list']) && \Route::is('admin.customerUsers')||
				empty(json_decode($activerole->permission, true)['users']['add']) && \Route::is('admin.usersCustomerAdd') ||
				empty(json_decode($activerole->permission, true)['users']['add']) && \Route::is('admin.usersCustomerPost') ||
				empty(json_decode($activerole->permission, true)['users']['update']) && \Route::is('admin.usersCustomerUpdate') ||
				empty(json_decode($activerole->permission, true)['users']['delete']) && \Route::is('admin.usersCustomerDelete') ||

				empty(json_decode($activerole->permission, true)['adminUsers']['list']) && \Route::is('admin.adminUsers')||
				empty(json_decode($activerole->permission, true)['adminUsers']['add']) && \Route::is('admin.adminUsersCreate') ||
				empty(json_decode($activerole->permission, true)['adminUsers']['add']) && \Route::is('admin.adminUsersPost') ||
				empty(json_decode($activerole->permission, true)['adminUsers']['suspend']) && \Route::is('admin.adminUsersSuspend') ||

				empty(json_decode($activerole->permission, true)['adminRoles']['list']) && \Route::is('admin.userRoles') ||
				empty(json_decode($activerole->permission, true)['adminRoles']['add']) && \Route::is('admin.userRoleAction') ||
				empty(json_decode($activerole->permission, true)['adminRoles']['delete']) && \Route::is('admin.usersRolesDelete') ||

				empty(json_decode($activerole->permission, true)['appsSetting']['general']) && \Request::is('admin/setting/general*') ||
				empty(json_decode($activerole->permission, true)['appsSetting']['general']) && \Request::is('admin/setting/logo*') ||
				empty(json_decode($activerole->permission, true)['appsSetting']['general']) && \Request::is('admin/setting/favicon*') ||
				empty(json_decode($activerole->permission, true)['appsSetting']['mail']) && \Request::is('admin/setting/mail*') ||
				empty(json_decode($activerole->permission, true)['appsSetting']['sms']) && \Request::is('admin/setting/sms*') ||
				empty(json_decode($activerole->permission, true)['appsSetting']['social']) && \Request::is('admin/setting/social*')


			){
				return abort('401');
			}
		}
	}
}