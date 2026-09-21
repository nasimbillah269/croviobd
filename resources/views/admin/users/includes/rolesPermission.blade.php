<table class="table mt-1">
     <thead>
         <tr>
             <th>Module Permission </th>
             <th><small>Items Added & Updated</small> </th>
             <th><small>Items Deleted</small> </th>
             <th><small> All Users Items Permited </small></th>
             <th>
             	<label>
             	<input type="checkbox"  id="checkall" style="display: inline-block;">
             	<small> All (Leftside Show Menus) </small>
             	</label>
           		</th>
         </tr>
     </thead>
     <tbody>
         <tr>
             <td>Posts </td>
             <td>
             	<label>
                 	<input type="checkbox" name="permission[posts][add]" @isset(json_decode($role->permission, true)['posts']['add']) checked @endisset> Add/Update</label>
             </td>
             <td>
                 <label>
                 	<input type="checkbox" name="permission[posts][delete]" @isset(json_decode($role->permission, true)['posts']['delete']) checked @endisset> Delete</label>
             </td>
             <td>
                <label>
                 	<input type="checkbox" name="permission[posts][all]"  @isset(json_decode($role->permission, true)['posts']['all']) checked @endisset> All</label>
             </td>
             <td>
                 <label>
                 	<input type="checkbox" name="permission[posts][list]" @isset(json_decode($role->permission, true)['posts']['list']) checked @endisset> List</label>
             </td>
         </tr>
          <tr>
             <td>Posts Category </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[postsCtg][add]" @isset(json_decode($role->permission, true)['postsCtg']['add']) checked @endisset> Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[postsCtg][delete]"  @isset(json_decode($role->permission, true)['postsCtg']['delete']) checked @endisset> Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[postsCtg][all]" @isset(json_decode($role->permission, true)['postsCtg']['all']) checked @endisset> All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[postsCtg][list]" @isset(json_decode($role->permission, true)['postsCtg']['list']) checked @endisset> List</label>
             </td>
         </tr>
          <tr>
             <td>Posts Tag </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[postsTag][add]" @isset(json_decode($role->permission, true)['postsTag']['add']) checked @endisset> Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[postsTag][delete]" @isset(json_decode($role->permission, true)['postsTag']['delete']) checked @endisset> Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[postsTag][all]" @isset(json_decode($role->permission, true)['postsTag']['all']) checked @endisset> All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[postsTag][list]" @isset(json_decode($role->permission, true)['postsTag']['list']) checked @endisset> List</label>
             </td>
         </tr>
          <tr>
             <td>Posts Comments </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[postsComment][add]" @isset(json_decode($role->permission, true)['postsComment']['add']) checked @endisset> Add Comments</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[postsComment][edit]" @isset(json_decode($role->permission, true)['postsComment']['edit']) checked @endisset> Update/Replay</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[postsComment][delete]" @isset(json_decode($role->permission, true)['postsComment']['delete']) checked @endisset> Delete</label>
             </td>
             
             <td>
                 <label>
                    <input type="checkbox" name="permission[postsComment][list]" @isset(json_decode($role->permission, true)['postsComment']['list']) checked @endisset> List</label>
             </td>
         </tr>
          <tr>
             <td>Pages </td>
             <td>
             	<label>
                 	<input  type="checkbox" name="permission[pages][add]" @isset(json_decode($role->permission, true)['pages']['add']) checked @endisset> Add/Update</label>
             </td>
             <td>
                 <label>
                 	<input  type="checkbox" name="permission[pages][delete]" @isset(json_decode($role->permission, true)['pages']['delete']) checked @endisset> Delete</label>
             </td>
             <td>
                <label>
                 	<input  type="checkbox" name="permission[pages][all]" @isset(json_decode($role->permission, true)['pages']['all']) checked @endisset> All</label>
             </td>
             <td>
                 <label>
                 	<input type="checkbox" name="permission[pages][list]" @isset(json_decode($role->permission, true)['pages']['list']) checked @endisset> List</label>
             </td>
         </tr>
         <tr>
             <td>Medies Library </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[medies][add]" @isset(json_decode($role->permission, true)['medies']['add']) checked @endisset> Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[medies][delete]" @isset(json_decode($role->permission, true)['medies']['delete']) checked @endisset> Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[medies][all]" @isset(json_decode($role->permission, true)['medies']['all']) checked @endisset> All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[medies][list]" @isset(json_decode($role->permission, true)['medies']['list']) checked @endisset> List</label>
             </td>
         </tr>
        <tr>
             <td>Ecommerce Setting</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[ecommerSetting][update]" @isset(json_decode($role->permission, true)['ecommerSetting']['update']) checked @endisset > General Update</label>
             </td>
             <td>
                
             </td>
             <td>
                 
             </td>
             
             <td>
                 <label>
                    <input type="checkbox" name="permission[ecommerSetting][list]" @isset(json_decode($role->permission, true)['ecommerSetting']['list']) checked @endisset > List</label>
             </td>
         </tr>
         <tr>
             <td>Products</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[products][add]" @isset(json_decode($role->permission, true)['products']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[products][delete]" @isset(json_decode($role->permission, true)['products']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[products][all]" @isset(json_decode($role->permission, true)['products']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[products][list]" @isset(json_decode($role->permission, true)['products']['list']) checked @endisset > List</label>
             </td>
         </tr>
         <tr>
             <td>Products Category</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[productsCtg][add]" @isset(json_decode($role->permission, true)['productsCtg']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[productsCtg][delete]" @isset(json_decode($role->permission, true)['productsCtg']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[productsCtg][all]" @isset(json_decode($role->permission, true)['productsCtg']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[productsCtg][list]" @isset(json_decode($role->permission, true)['productsCtg']['list']) checked @endisset > List</label>
             </td>
         </tr>
         <tr>
             <td>Stock</td>
             <td>
                
             </td>
             <td>
                
             </td>
             <td>
                
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[stockList][list]" @isset(json_decode($role->permission, true)['stockList']['list']) checked @endisset > List</label>
             </td>
         </tr>
        
         
         <tr>
             <td>Order Management</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[orderManagement][add]" @isset(json_decode($role->permission, true)['orderManagement']['add']) checked @endisset > Manage</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[orderManagement][delete]" @isset(json_decode($role->permission, true)['orderManagement']['delete']) checked @endisset > Payments</label>
             </td>
             <td>
                
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[orderManagement][list]" @isset(json_decode($role->permission, true)['orderManagement']['list']) checked @endisset > List</label>
             </td>
         </tr>
         

         <tr>
             <td>Reports Management</td>
             <td>
                
             </td>
             <td>
                 
             </td>
             <td>
                
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[reports][list]" @isset(json_decode($role->permission, true)['reports']['list']) checked @endisset > List</label>
             </td>
         </tr>
         
         
        
         <tr>
             <td>Clients</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[clients][add]" @isset(json_decode($role->permission, true)['clients']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[clients][delete]" @isset(json_decode($role->permission, true)['clients']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[clients][all]" @isset(json_decode($role->permission, true)['clients']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[clients][list]" @isset(json_decode($role->permission, true)['clients']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr>
             <td>Brands</td>
             <td>
                <label>
                    <input  type="checkbox"  name="permission[brands][add]" @isset(json_decode($role->permission, true)['brands']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[brands][delete]" @isset(json_decode($role->permission, true)['brands']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[brands][all]" @isset(json_decode($role->permission, true)['brands']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[brands][list]" @isset(json_decode($role->permission, true)['brands']['list']) checked @endisset > List</label>
             </td>
         </tr>

          <tr>
             <td>Sliders</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[sliders][add]" @isset(json_decode($role->permission, true)['sliders']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[sliders][delete]" @isset(json_decode($role->permission, true)['sliders']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[sliders][all]" @isset(json_decode($role->permission, true)['sliders']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[sliders][list]" @isset(json_decode($role->permission, true)['sliders']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr>
             <td>Galleries</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[galleries][add]" @isset(json_decode($role->permission, true)['galleries']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[galleries][delete]" @isset(json_decode($role->permission, true)['galleries']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[galleries][all]" @isset(json_decode($role->permission, true)['galleries']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[galleries][list]" @isset(json_decode($role->permission, true)['galleries']['list']) checked @endisset > List</label>
             </td>
         </tr>

          <tr>
             <td>Menus Setting</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[menus][add]" @isset(json_decode($role->permission, true)['menus']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[menus][delete]" @isset(json_decode($role->permission, true)['menus']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[menus][all]" @isset(json_decode($role->permission, true)['menus']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[menus][list]" @isset(json_decode($role->permission, true)['menus']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr>
             <td>Theme Setting</td>
             <td>
                <label>
                    
             </td>
             <td>
                 
             </td>
             <td>
                
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[themeSetting][list]" @isset(json_decode($role->permission, true)['themeSetting']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr>
             <td>Administrator Users</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[adminUsers][add]" @isset(json_decode($role->permission, true)['adminUsers']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[adminUsers][delete]" @isset(json_decode($role->permission, true)['adminUsers']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[adminUsers][list]" @isset(json_decode($role->permission, true)['adminUsers']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr>
             <td>Roles User</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[adminRoles][add]" @isset(json_decode($role->permission, true)['adminRoles']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[adminRoles][delete]" @isset(json_decode($role->permission, true)['adminRoles']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[adminRoles][all]" @isset(json_decode($role->permission, true)['adminRoles']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[adminRoles][list]" @isset(json_decode($role->permission, true)['adminRoles']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr>
             <td>Customer Users</td>
             <td>
                <label>
                    <input type="checkbox" name="permission[users][add]" @isset(json_decode($role->permission, true)['users']['add']) checked @endisset > Add</label>
             </td>
             <td>
                <label>
                    <input type="checkbox" name="permission[users][update]" @isset(json_decode($role->permission, true)['users']['update']) checked @endisset > Update</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[users][delete]" @isset(json_decode($role->permission, true)['users']['delete']) checked @endisset > Delete</label>
             </td>
             
             <td>
                 <label>
                    <input type="checkbox" name="permission[users][list]" @isset(json_decode($role->permission, true)['users']['list']) checked @endisset > List</label>
             </td>
         </tr>
         
         

         <tr>
             <td>Subscribe Users</td>
             <td>

             </td>
             <td>

             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[subscribe][delete]" @isset(json_decode($role->permission, true)['subscribe']['delete']) checked @endisset > Delete</label>
             </td>
             
             <td>
                 <label>
                    <input type="checkbox" name="permission[subscribe][list]" @isset(json_decode($role->permission, true)['subscribe']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr>
             <td>Apps Setting</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[appsSetting][general]" @isset(json_decode($role->permission, true)['appsSetting']['general']) checked @endisset > General Setting </label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[appsSetting][mail]" @isset(json_decode($role->permission, true)['appsSetting']['mail']) checked @endisset > Mail Setting</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[appsSetting][sms]" @isset(json_decode($role->permission, true)['appsSetting']['sms']) checked @endisset > SMS Setting</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[appsSetting][social]" @isset(json_decode($role->permission, true)['appsSetting']['social']) checked @endisset > Social Setting</label>
             </td>
         </tr>


     </tbody>
 </table>