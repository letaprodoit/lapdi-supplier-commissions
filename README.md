{{TOC}}
= Supplier Commissions (CS-Cart Addon) 1.0 =

Supplier Commissions for CS-Cart allows you to add commissions to supplier products in the store and allow you to monitor the commissions in the admin area (Requires CS-Cart Professional 3.04 and higher and PayPal Pro with Mass Pay turned on).

== FEATURES ==
* Suppliers can sign up via membership
* Suppliers can choose number of products that can be sold (reflected in price of membership)
* Suppliers can choose the commission they receive (reflected in price of membership)
* Suppliers are notified whenever their products are sold
For up-to-date installation and setup notes, visit the FAQ:
[http://lab.thesoftwarepeople.com/tracker/wiki/cscart-sca-MainPage]

== GENERAL INSTALLATION NOTES ==
* Download from repository
* Unzip the zip file
* Open addons/ folder and copy the tsp_supplier_commissions to (your cscart install dir)/addons/
* Open the basic/admin/addons/ folder and copy the tsp_supplier_commissions/ folder to (your cscart install dir)/var/skins_repository/basic/admin/addons/ folder
* Open the basic/mail/addons/ folder and copy the tsp_supplier_commissions/ folder to (your cscart install dir)/var/skins_repository/basic/mail/addons/ folder
* Copy the paypal.php file to (your cscart install dir)/
* Open CS-Cart Administration Control Panel
* Navigate to Settings-> Addons
* Find the "The Software People: Supplier Commissions" addon and click "Install"
* After Install, from the Addons listing click on Settings for "The Software People: Supplier Commissions"
* Update The Supplier Commissions settings
== ADDON SETTINGS ==
* Supplier Settings
** Delete Commission Data on Uninstall - Sometimes it may be necessary to uninstall this addon and this setting protects your supplier data from being deleted once the addon is uninstalled if "No" is selected.
* Payment Settings
** Commissions Enabled - Select "Yes" to start calculating commissions generated from supplier products. If this setting is set to "No", no commission information will be gathered.
** MassPay Enabled - Select "Yes" to turn on PayPal MassPay functionality. If this setting is set "No", suppliers will not receive payments.
** Use Live MassPay Account - Select "Yes" make your site Live. Selecting "No", puts the site in Test mode.
** Pay Suppliers Automatically - Select "Yes" to pay your suppliers every time a product is sold. Selecting "No" allows you to pay suppliers all at once.
*** Default Settings for Payment Settings
**** Commissions Enabled - Yes
**** MassPay Enabled - Yes
**** Use Live MassPay Account - No (While testing…select Yes once site is Live)
**** Pay Suppliers Automatically - No (It is recommended that suppliers are paid all at once).
== PAYPAL SETTINGS ==

To verify that the addon is processing payments as expected, it is highly recommended that you first create a test paypal account by visiting (https://developer.paypal.com)[https://developer.paypal.com]. 

Once your account is setup you will need make the following changes in your PayPal account.

=== Set Instant Payment Notification Preferences ===

After logging into your account, click on "Profile->Instant Payment Notification Preferences" and add in your notification URL

 https://www.yourcompanysite.com/paypal.php?store_access_key=YOUR_ACCESS_KEY

and make sure "Receive IPN messages (Enabled)" is checked. The store access key can be found in the admin section of CS-Cart (Settings->General)

=== Enable PayPal MassPay ===

Allows you to pay multiple suppliers all at once, in one batch payment. 

To check your status login to your PayPal account and click on Send Money after the page loads click on "Make a Mass Payment". 

If you are able to make a mass payment then you are already set up and you do not have to do anything further. If not, you will need to contact PayPal directly to get it turned on.

== USING THE ADDON ==

=== Before You Begin ===

The Appointments module, upon install, adds customer profile fields and product global options to the database and adds a single settings to all products.

The Product->Global Options that are added include:
* PayPal Email Address - The PayPal address the where the customer commissions will be deposited.
* Maximum Number of Products - The maximum number or products the customer is allowed to sell in the store.
* Discount (%) - The percentage amount that will be deducted from the price of each product sold in the store.
Upon registration, the above information will be copied to the user's profile in the following fields.

The Customer->Profile Fields that are added include:
* PayPal Email Address - The PayPal address the where the customer commissions will be deposited.
* Maximum Number of Products - The maximum number or products the customer is allowed to sell in the store.
* Discount (%) - The percentage amount that will be deducted from the price of each product sold in the store.
Each product  has a settings that can be turned on if the admin wishes to to make the product a supplier membership:

Open Any Product->Addons Tab->"The Software People: Supplier Commissions" section:
* Supplier Membership? - Is the recurring membership a supplier membership?
=== Creating a Membership Product for Supplier Registrations ===

In order to create a product that is an membership you will need to perform the following steps:
* Create the product and save (after save the Options tab will be available to you.
* Navigate to the Options tab and add the Global options above to the product by clicking on "Add Global Option" (you can change the field names and drop down options by clicking edit).
* Next, navigate to the Addons tab and scroll to the "The Software People: Supplier Commissions" section and check "Supplier Membership?"
=== Create a Supplier Recurring Subscription Plan ===

Each membership product needs to have an associated recurring membership. To do this you will need to perform the following steps:
* In the admin section, navigate to Orders->Recurring Plans
* Suggested settings for a monthly membership is included below however you are free to change these values as necessary.
** Title: My Supplier Membership
** Recurring Period: By Period
** Recurring period value (days): 31
** Pay day: 1
** Recurring price: original
** Recurring duration (months): 120 (10 years)
** Recurring start price: original
** Recurring start duration: 0
** Allow customers to unsubscribe: Checked
* Save the plan
* After save, click on the "Products" tab and add the product created in the "Creating a Membership Product for Supplier Registrations" section
=== Create a Supplier Membership ===

Now that you have the product and the plan setup, you will need to assign privileges to any new registered suppliers. Follow these steps:
* In the admin section, navigate to Customers->User Groups
* Click on "Add User Group"
* Suggested settings for the user group are included below; however, you are free to change these values as necessary.
** General
*** User group: Supplier
*** Type: Administrator
** Privileges
*** Unselect all except Manage catalog and View catalog
** Recurring plans
*** Click on "Add Recurring Plans" and select the plan created in the section "Create a Supplier Recurring Subscription Plan" 
=== Managing Suppliers ===
* To display your list of suppliers, in the admin section, click on Supplier->Suppliers
** To manually add a supplier, click on "Add Supplier"
** To modify a supplier, click on "Edit" or click on the supplier's company name
*** To view a supplier's products, click on "View Supplier Products"
*** To view a supplier's orders, click on "View Supplier Orders"
=== Managing Supplier Commissions ===
* To display your list of supplier commissions, in the admin section, click on Supplier->Supplier Commissions
** To view a supplier, click on "More->View"
** To process a single supplier commission, click on "More->Process"
** To process multiple supplier commissions, select the suppliers to process and click on "Process Supplier Commissions"
Once a commission is successfully processed and paid the "Transaction date" and "Transaction ID" will be updated by the system.

== REPORTING ISSUES ==

Thank you for downloading Supplier Commissions for CS-Cart 1.0
If you find any issues, please report them in the issue tracker on our website:
[http://lab.thesoftwarepeople.com/tracker/cscart-sca]

== COPYRIGHT AND LICENSE ==

Copyright 2013 The Software People, LLC

Software is available under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License; additional terms may apply. See [http://creativecommons.org/licenses/by-nc-nd/3.0/ Terms of Use] for details.

[[Category:TheLab:Software]][[Category:TheLab:Software:Documentation]][[Category:TheLab:Software:CSCartAddons]]